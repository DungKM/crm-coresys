<?php

namespace Webkul\GoogleAds\Services;

use Webkul\GoogleAds\Contracts\GoogleAdsServiceContract;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V22\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\V22\Services\SearchGoogleAdsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleAdsService implements GoogleAdsServiceContract
{
    /**
     * Get config value from config file (which reads from .env)
     */
    private function getConfig(string $key, $default = null)
    {
        return config('google-ads.' . $key, $default);
    }

    public function testConnection(): array
    {
        try {
            $developerToken = $this->getConfig('developer_token');
            $clientId = $this->getConfig('client_id');
            $clientSecret = $this->getConfig('client_secret');
            $refreshToken = $this->getConfig('refresh_token');
            $customerId = $this->getConfig('customer_id');
            $loginCustomerId = $this->getConfig('login_customer_id');

            // Build OAuth2 credential
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId($clientId)
                ->withClientSecret($clientSecret)
                ->withRefreshToken($refreshToken)
                ->build();

            // Build Google Ads client
            $googleAdsClient = (new GoogleAdsClientBuilder())
                ->withDeveloperToken($developerToken)
                ->withOAuth2Credential($oAuth2Credential)
                ->withLoginCustomerId(str_replace('-', '', $loginCustomerId))
                ->build();

            // Query
            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $customerId = str_replace('-', '', $customerId);

            $request = new SearchGoogleAdsRequest([
                'customer_id' => $customerId,
                'query' => 'SELECT customer.id, customer.descriptive_name FROM customer',
            ]);

            $callOptions = [
                'headers' => ['login-customer-id' => str_replace('-', '', $loginCustomerId)]
            ];

            $response = $googleAdsServiceClient->search($request, $callOptions);

            foreach ($response->iterateAllElements() as $googleAdsRow) {
                return [
                    'success' => true,
                    'account_name' => $googleAdsRow->getCustomer()->getDescriptiveName(),
                    'customer_id' => $googleAdsRow->getCustomer()->getId(),
                ];
            }

            return [
                'success' => false,
                'message' => 'No data returned from API',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getCampaigns(): array
    {
        // Cache campaigns for 5 minutes to reduce API calls
        return Cache::remember('google_ads_campaigns', 300, function () {
            return $this->fetchCampaignsFromAPI();
        });
    }

    /**
     * Fetch campaigns from Google Ads API
     */
    private function fetchCampaignsFromAPI(): array
    {
        try {
            Log::info('Fetching campaigns from Google Ads API...');
            $developerToken = $this->getConfig('developer_token');
            $clientId = $this->getConfig('client_id');
            $clientSecret = $this->getConfig('client_secret');
            $refreshToken = $this->getConfig('refresh_token');
            $customerId = $this->getConfig('customer_id');
            $loginCustomerId = $this->getConfig('login_customer_id');

            // Build OAuth2 credential
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId($clientId)
                ->withClientSecret($clientSecret)
                ->withRefreshToken($refreshToken)
                ->build();

            // Build Google Ads client
            $googleAdsClient = (new GoogleAdsClientBuilder())
                ->withDeveloperToken($developerToken)
                ->withOAuth2Credential($oAuth2Credential)
                ->withLoginCustomerId(str_replace('-', '', $loginCustomerId))
                ->build();

            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $customerId = str_replace('-', '', $customerId);

            // Query account info for display
            $accountName = null;
            $accountCustomerId = null;
            $accountQuery = 'SELECT customer.id, customer.descriptive_name FROM customer';
            $accountRequest = new SearchGoogleAdsRequest([
                'customer_id' => $customerId,
                'query' => $accountQuery,
            ]);
            $accountResponse = $googleAdsServiceClient->search($accountRequest);
            foreach ($accountResponse->iterateAllElements() as $row) {
                $accountName = $row->getCustomer()->getDescriptiveName();
                $accountCustomerId = $row->getCustomer()->getId();
                break;
            }

            // Query campaigns with full metrics and budget info
            $query = "SELECT 
                campaign.id,
                campaign.name,
                campaign.status,
                campaign.start_date,
                campaign.end_date,
                campaign.campaign_budget,
                campaign_budget.amount_micros,
                campaign_budget.period,
                metrics.impressions,
                metrics.clicks,
                metrics.cost_micros,
                metrics.conversions
            FROM campaign 
            WHERE segments.date DURING LAST_30_DAYS
            ORDER BY campaign.name";

            $request = new SearchGoogleAdsRequest([
                'customer_id' => $customerId,
                'query' => $query,
            ]);

            $response = $googleAdsServiceClient->search($request);

            $campaigns = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $campaign = $googleAdsRow->getCampaign();
                $metrics = $googleAdsRow->getMetrics();
                $campaignBudget = $googleAdsRow->getCampaignBudget();

                // Convert micros to dollars (1 million micros = 1 dollar)
                $cost = $metrics ? ($metrics->getCostMicros() / 1000000) : 0;
                $budgetAmount = $campaignBudget ? ($campaignBudget->getAmountMicros() / 1000000) : 0;
                $budgetPeriod = $campaignBudget ? $campaignBudget->getPeriod() : null;

                $campaigns[] = [
                    'id' => $campaign->getId(),
                    'name' => $campaign->getName(),
                    'status' => $campaign->getStatus(),
                    'impressions' => $metrics ? $metrics->getImpressions() : 0,
                    'clicks' => $metrics ? $metrics->getClicks() : 0,
                    'cost' => $cost,
                    'conversions' => $metrics ? $metrics->getConversions() : 0,
                    'start_date' => $campaign->getStartDate(),
                    'end_date' => $campaign->getEndDate(),
                    'budget_amount' => $budgetAmount,
                    'budget_period' => $budgetPeriod,
                ];
            }

            return [
                'success' => true,
                'campaigns' => $campaigns,
                'total' => count($campaigns),
                'account_name' => $accountName,
                'customer_id' => $accountCustomerId,
            ];
        } catch (\Exception $e) {
            Log::error('Google Ads API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'campaigns' => [],
            ];
        }
    }

    public function getCampaignById($campaignId): array
    {
        try {
            $result = $this->getCampaigns();

            if (!$result['success']) {
                return $result;
            }

            $campaigns = $result['campaigns'] ?? [];
            $campaign = collect($campaigns)->firstWhere('id', $campaignId);

            if (!$campaign) {
                return [
                    'success' => false,
                    'message' => 'Campaign not found',
                ];
            }

            return [
                'success' => true,
                'campaign' => $campaign,
                'account_name' => $result['account_name'] ?? null,
                'customer_id' => $result['customer_id'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Get Campaign By ID Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function createCampaign(array $data): array
    {
        try {
            $developerToken = $this->getConfig('developer_token');
            $clientId = $this->getConfig('client_id');
            $clientSecret = $this->getConfig('client_secret');
            $refreshToken = $this->getConfig('refresh_token');
            $customerId = $this->getConfig('customer_id');
            $loginCustomerId = $this->getConfig('login_customer_id');

            // Build OAuth2 credential
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId($clientId)
                ->withClientSecret($clientSecret)
                ->withRefreshToken($refreshToken)
                ->build();

            // Build Google Ads client
            $googleAdsClient = (new GoogleAdsClientBuilder())
                ->withDeveloperToken($developerToken)
                ->withOAuth2Credential($oAuth2Credential)
                ->withLoginCustomerId(str_replace('-', '', $loginCustomerId))
                ->build();

            $customerId = str_replace('-', '', $customerId);

            // TODO: Implement actual campaign creation using Google Ads API
            // This requires:
            // 1. Create campaign budget first
            // 2. Create campaign with the budget
            // 3. Set campaign settings (targeting, bidding, etc.)

            Log::info('Campaign creation requested', ['data' => $data]);

            // Clear cache after creation
            Cache::forget('google_ads_campaigns');

            return [
                'success' => true,
                'message' => 'Campaign creation is in development. Data received: ' . json_encode($data),
            ];

        } catch (\Exception $e) {
            Log::error('Create Campaign Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function updateCampaign($campaignId, array $data): array
    {
        try {
            $developerToken = $this->getConfig('developer_token');
            $clientId = $this->getConfig('client_id');
            $clientSecret = $this->getConfig('client_secret');
            $refreshToken = $this->getConfig('refresh_token');
            $customerId = $this->getConfig('customer_id');
            $loginCustomerId = $this->getConfig('login_customer_id');

            // Build OAuth2 credential
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId($clientId)
                ->withClientSecret($clientSecret)
                ->withRefreshToken($refreshToken)
                ->build();

            // Build Google Ads client
            $googleAdsClient = (new GoogleAdsClientBuilder())
                ->withDeveloperToken($developerToken)
                ->withOAuth2Credential($oAuth2Credential)
                ->withLoginCustomerId(str_replace('-', '', $loginCustomerId))
                ->build();

            $customerId = str_replace('-', '', $customerId);

            // Get Campaign Service Client
            $campaignServiceClient = $googleAdsClient->getCampaignServiceClient();

            // Create campaign resource name
            $campaignResourceName = "customers/{$customerId}/campaigns/{$campaignId}";

            // Create Campaign object with updated fields
            $campaign = new \Google\Ads\GoogleAds\V22\Resources\Campaign();
            $campaign->setResourceName($campaignResourceName);

            // Update fields based on provided data
            $fieldMask = [];

            if (isset($data['campaign_name'])) {
                $campaign->setName($data['campaign_name']);
                $fieldMask[] = 'name';
            }

            if (isset($data['status'])) {
                // Map string status to enum value
                $statusMap = [
                    'ENABLED' => \Google\Ads\GoogleAds\V22\Enums\CampaignStatusEnum\CampaignStatus::ENABLED,
                    'PAUSED' => \Google\Ads\GoogleAds\V22\Enums\CampaignStatusEnum\CampaignStatus::PAUSED,
                    'REMOVED' => \Google\Ads\GoogleAds\V22\Enums\CampaignStatusEnum\CampaignStatus::REMOVED,
                ];

                if (isset($statusMap[$data['status']])) {
                    $campaign->setStatus($statusMap[$data['status']]);
                    $fieldMask[] = 'status';
                }
            }

            // Update budget if provided
            if (isset($data['daily_budget']) && $data['daily_budget'] > 0) {
                try {
                    // First, get the campaign's budget resource name
                    $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
                    $query = "SELECT campaign.id, campaign.campaign_budget FROM campaign WHERE campaign.id = {$campaignId}";
                    $searchRequest = new SearchGoogleAdsRequest([
                        'customer_id' => $customerId,
                        'query' => $query,
                    ]);
                    $searchResponse = $googleAdsServiceClient->search($searchRequest);

                    $budgetResourceName = null;
                    foreach ($searchResponse->iterateAllElements() as $row) {
                        $budgetResourceName = $row->getCampaign()->getCampaignBudget();
                        break;
                    }

                    if ($budgetResourceName) {
                        // Update the campaign budget
                        $campaignBudgetServiceClient = $googleAdsClient->getCampaignBudgetServiceClient();

                        $campaignBudget = new \Google\Ads\GoogleAds\V22\Resources\CampaignBudget();
                        $campaignBudget->setResourceName($budgetResourceName);
                        // Convert dollars to micros (1 dollar = 1,000,000 micros)
                        $campaignBudget->setAmountMicros(intval($data['daily_budget'] * 1000000));

                        $budgetOperation = new \Google\Ads\GoogleAds\V22\Services\CampaignBudgetOperation();
                        $budgetOperation->setUpdate($campaignBudget);
                        $budgetOperation->setUpdateMask(new \Google\Protobuf\FieldMask(['paths' => ['amount_micros']]));

                        $budgetRequest = new \Google\Ads\GoogleAds\V22\Services\MutateCampaignBudgetsRequest([
                            'customer_id' => $customerId,
                            'operations' => [$budgetOperation],
                        ]);

                        $campaignBudgetServiceClient->mutateCampaignBudgets($budgetRequest);

                        Log::info('Campaign budget updated', [
                            'budget_resource' => $budgetResourceName,
                            'new_amount' => $data['daily_budget']
                        ]);
                    }
                } catch (\Exception $budgetError) {
                    Log::warning('Failed to update campaign budget', [
                        'error' => $budgetError->getMessage(),
                        'campaign_id' => $campaignId
                    ]);
                    // Continue with campaign update even if budget update fails
                }
            }

            // Create campaign operation
            $campaignOperation = new \Google\Ads\GoogleAds\V22\Services\CampaignOperation();
            $campaignOperation->setUpdate($campaign);
            $campaignOperation->setUpdateMask(new \Google\Protobuf\FieldMask(['paths' => $fieldMask]));

            // Create the mutate request
            $request = new \Google\Ads\GoogleAds\V22\Services\MutateCampaignsRequest([
                'customer_id' => $customerId,
                'operations' => [$campaignOperation],
            ]);

            // Execute the mutation
            $response = $campaignServiceClient->mutateCampaigns($request);

            Log::info('Campaign updated successfully', [
                'campaign_id' => $campaignId,
                'updated_fields' => $fieldMask,
                'data' => $data
            ]);

            // Clear cache after update
            Cache::forget('google_ads_campaigns');

            return [
                'success' => true,
                'message' => 'Campaign updated successfully via Google Ads API',
                'campaign_id' => $campaignId,
            ];

        } catch (\Exception $e) {
            Log::error('Update Campaign Error: ' . $e->getMessage(), [
                'campaign_id' => $campaignId,
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function deleteCampaign($campaignId): array
    {
        try {
            $developerToken = $this->getConfig('developer_token');
            $clientId = $this->getConfig('client_id');
            $clientSecret = $this->getConfig('client_secret');
            $refreshToken = $this->getConfig('refresh_token');
            $customerId = $this->getConfig('customer_id');
            $loginCustomerId = $this->getConfig('login_customer_id');

            // Build OAuth2 credential
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId($clientId)
                ->withClientSecret($clientSecret)
                ->withRefreshToken($refreshToken)
                ->build();

            // Build Google Ads client
            $googleAdsClient = (new GoogleAdsClientBuilder())
                ->withDeveloperToken($developerToken)
                ->withOAuth2Credential($oAuth2Credential)
                ->withLoginCustomerId(str_replace('-', '', $loginCustomerId))
                ->build();

            $customerId = str_replace('-', '', $customerId);

            // Get Campaign Service Client
            $campaignServiceClient = $googleAdsClient->getCampaignServiceClient();

            // Create campaign resource name
            $campaignResourceName = "customers/{$customerId}/campaigns/{$campaignId}";

            // Create Campaign object with REMOVED status
            $campaign = new \Google\Ads\GoogleAds\V22\Resources\Campaign();
            $campaign->setResourceName($campaignResourceName);
            $campaign->setStatus(\Google\Ads\GoogleAds\V22\Enums\CampaignStatusEnum\CampaignStatus::REMOVED);

            // Create campaign operation
            $campaignOperation = new \Google\Ads\GoogleAds\V22\Services\CampaignOperation();
            $campaignOperation->setUpdate($campaign);
            $campaignOperation->setUpdateMask(new \Google\Protobuf\FieldMask(['paths' => ['status']]));

            // Create the mutate request
            $request = new \Google\Ads\GoogleAds\V22\Services\MutateCampaignsRequest([
                'customer_id' => $customerId,
                'operations' => [$campaignOperation],
            ]);

            // Execute the mutation
            $response = $campaignServiceClient->mutateCampaigns($request);

            Log::info('Campaign deleted (status set to REMOVED)', ['campaign_id' => $campaignId]);

            // Clear cache after deletion
            Cache::forget('google_ads_campaigns');

            return [
                'success' => true,
                'message' => 'Campaign deleted successfully (status set to REMOVED)',
            ];

        } catch (\Exception $e) {
            Log::error('Delete Campaign Error: ' . $e->getMessage(), [
                'campaign_id' => $campaignId,
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getAdsStats(): array
    {
        // TODO: Implement getAdsStats
        return [];
    }
}
