<?php

namespace Webkul\GoogleAds\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Webkul\GoogleAds\Contracts\GoogleAdsServiceContract;

class GoogleAdsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(
        private GoogleAdsServiceContract $googleAdsService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch real campaigns from Google Ads API
        $result = $this->googleAdsService->getCampaigns();

        $campaigns = $result['campaigns'] ?? [];
        $error = isset($result['success']) && !$result['success'] ? $result['message'] : null;
        $account_name = $result['account_name'] ?? null;
        $customer_id = $result['customer_id'] ?? null;

        // Log for debugging
        if ($error) {
            Log::warning('Google Ads API Error in index: ' . $error);
        }

        return view('admin::google-ads.index', compact('campaigns', 'error', 'account_name', 'customer_id'));
    }

    /**
     * Test connection to Google Ads API and get account info.
     *
     * @return \Illuminate\Http\Response
     */
    public function testConnection()
    {
        try {
            // Load credentials from env
            $developerToken = env('GOOGLE_ADS_DEVELOPER_TOKEN');
            $clientId = env('GOOGLE_ADS_CLIENT_ID');
            $clientSecret = env('GOOGLE_ADS_CLIENT_SECRET');
            $refreshToken = env('GOOGLE_ADS_REFRESH_TOKEN');
            $customerId = env('GOOGLE_ADS_CUSTOMER_ID');
            $loginCustomerId = env('GOOGLE_ADS_LOGIN_CUSTOMER_ID'); // Login Customer ID (Manager/MCC Account)

            // Format customer IDs (remove hyphens)
            $customerId = str_replace('-', '', $customerId);
            $loginCustomerId = str_replace('-', '', $loginCustomerId);

            // Log credentials for debugging
            Log::info('Google Ads Test Connection', [
                'developer_token' => substr($developerToken, 0, 10) . '***',
                'client_id' => substr($clientId, 0, 10) . '***',
                'customer_id' => $customerId,
                'login_customer_id' => $loginCustomerId,
                'note' => 'login_customer_id (MCC) will be set in header, customer_id is the account to query'
            ]);

            // Build OAuth2 credentials
            $oAuth2Credential = (new \Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder())
                ->withClientId($clientId)
                ->withClientSecret($clientSecret)
                ->withRefreshToken($refreshToken)
                ->build();

            // Build Google Ads client
            // withLoginCustomerId() should be set to Login Customer ID (MCC Account) to manage accounts under this MCC
            $googleAdsClient = (new \Google\Ads\GoogleAds\Lib\V22\GoogleAdsClientBuilder())
                ->withDeveloperToken($developerToken)
                ->withOAuth2Credential($oAuth2Credential)
                ->withLoginCustomerId($loginCustomerId)
                ->build();

            // Query account info
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $query = 'SELECT customer.id, customer.descriptive_name FROM customer';

            // Build SearchGoogleAdsRequest
            $request = (new \Google\Ads\GoogleAds\V22\Services\SearchGoogleAdsRequest())
                ->setCustomerId($customerId)
                ->setQuery($query);

            // Set login-customer-id in request options/headers
            // Google Ads API requires login-customer-id in the header when accessing client customers
            $callOptions = [
                'headers' => [
                    'login-customer-id' => $loginCustomerId,
                ]
            ];

            $response = $googleAdsServiceClient->search($request, $callOptions);

            $accountName = null;
            $accountCustomerId = null;
            foreach ($response->getIterator() as $googleAdsRow) {
                $accountName = $googleAdsRow->getCustomer()->getDescriptiveName();
                $accountCustomerId = $googleAdsRow->getCustomer()->getId();
                break;
            }

            Log::info('Google Ads Connection Success', [
                'account_name' => $accountName,
                'customer_id' => $accountCustomerId,
            ]);

            if ($accountName) {
                return response()->json([
                    'success' => true,
                    'account_name' => $accountName,
                    'customer_id' => $accountCustomerId,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy tên tài khoản.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Google Ads Connection Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'debug_info' => [
                    'login_customer_id_should_be' => env('GOOGLE_ADS_LOGIN_CUSTOMER_ID'),
                    'customer_id_querying' => env('GOOGLE_ADS_CUSTOMER_ID'),
                ]
            ]);

            $message = $e->getMessage();

            if (strpos($message, 'DEVELOPER_TOKEN_PROVISIONAL') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Developer token đang chờ duyệt, vẫn có thể dùng cho tài khoản test.'
                ], 403);
            }

            if (strpos($message, 'SSL') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi SSL: Có thể cấu hình verify => false hoặc thêm file cacert.pem vào PHP.'
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::google-ads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // Validate request
        $validated = request()->validate([
            'campaign_name' => 'required|string|max:255',
            'campaign_goal' => 'required|string',
            'campaign_type' => 'required|string',
            'daily_budget' => 'required|numeric|min:0',
            'bidding_strategy' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Call service to create campaign
        $result = $this->googleAdsService->createCampaign($validated);

        if ($result['success']) {
            return redirect()->route('admin.google_ads.index')
                ->with('success', trans('google_ads::app.google-ads.campaign-created'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $result['message'] ?? trans('google_ads::app.google-ads.campaign-create-failed'));
    }

    /**
     * Display the specified campaign.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Fetch campaign details from service
        $result = $this->googleAdsService->getCampaignById($id);

        if (!$result['success']) {
            return redirect()->route('admin.google_ads.index')
                ->with('error', $result['message'] ?? 'Campaign not found.');
        }

        $campaign = $result['campaign'];
        $account_name = $result['account_name'] ?? null;
        $customer_id = $result['customer_id'] ?? null;

        return view('admin::google-ads.show', compact('campaign', 'account_name', 'customer_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Fetch campaign details from service
        $result = $this->googleAdsService->getCampaignById($id);

        if (!$result['success']) {
            return redirect()->route('admin.google_ads.index')
                ->with('error', $result['message'] ?? 'Campaign not found.');
        }

        $campaign = $result['campaign'];
        $account_name = $result['account_name'] ?? null;
        $customer_id = $result['customer_id'] ?? null;

        return view('admin::google-ads.edit', compact('campaign', 'account_name', 'customer_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // Validate request
        $validated = request()->validate([
            'campaign_name' => 'required|string|max:255',
            'status' => 'required|in:ENABLED,PAUSED,REMOVED',
            'daily_budget' => 'required|numeric|min:0',
        ]);

        // Call service to update campaign
        $result = $this->googleAdsService->updateCampaign($id, $validated);

        if ($result['success']) {
            return redirect()->route('admin.google_ads.campaigns.show', $id)
                ->with('success', trans('google_ads::app.google-ads.campaign-updated'));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $result['message'] ?? trans('google_ads::app.google-ads.campaign-update-failed'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Call service to delete campaign (set status to REMOVED)
        $result = $this->googleAdsService->deleteCampaign($id);

        if ($result['success']) {
            return redirect()->route('admin.google_ads.index')
                ->with('success', trans('google_ads::app.google-ads.campaign-deleted'));
        }

        return redirect()->back()
            ->with('error', $result['message'] ?? trans('google_ads::app.google-ads.campaign-delete-failed'));
    }

    /**
     * Display campaigns page.
     *
     * @return \Illuminate\View\View
     */
    public function campaigns()
    {
        return view('admin::google-ads.index');
    }
}