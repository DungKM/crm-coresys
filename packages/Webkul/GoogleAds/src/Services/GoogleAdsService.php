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
     * Get config value from database or fallback to .env
     */
    private function getConfig(string $key, $default = null)
    {
        $value = DB::table('core_config')
            ->where('code', 'google_ads.' . $key)
            ->value('value');

        // If value is empty string, treat as null
        if ($value === '' || $value === null) {
            return env('GOOGLE_ADS_' . strtoupper($key), $default);
        }

        return $value;
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

    public function getAdsStats(): array
    {
        // TODO: Implement getAdsStats
        return [];
    }
}
