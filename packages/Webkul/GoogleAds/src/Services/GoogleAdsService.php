<?php

namespace Webkul\GoogleAds\Services;

use Webkul\GoogleAds\Contracts\GoogleAdsServiceContract;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V22\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\V22\Services\SearchGoogleAdsRequest;
use Illuminate\Support\Facades\DB;

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

            $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
            $customerId = str_replace('-', '', $customerId);

            // Query campaigns WITHOUT metrics first to test if account is MCC
            $query = "SELECT 
                campaign.id,
                campaign.name,
                campaign.status
            FROM campaign 
            ORDER BY campaign.name";

            $request = new SearchGoogleAdsRequest([
                'customer_id' => $customerId,
                'query' => $query,
            ]);

            $response = $googleAdsServiceClient->search($request);

            $campaigns = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $campaign = $googleAdsRow->getCampaign();

                $campaigns[] = [
                    'id' => $campaign->getId(),
                    'name' => $campaign->getName(),
                    'status' => $campaign->getStatus(),
                    'impressions' => 0,
                    'clicks' => 0,
                    'cost' => 0,
                    'conversions' => 0,
                ];
            }

            return [
                'success' => true,
                'campaigns' => $campaigns,
                'total' => count($campaigns),
            ];
        } catch (\Exception $e) {
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
