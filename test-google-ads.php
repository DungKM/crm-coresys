<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V22\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\V22\Services\SearchGoogleAdsRequest;

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== TEST GOOGLE ADS API CONNECTION ===\n\n";

// Get credentials from .env
$developerToken = $_ENV['GOOGLE_ADS_DEVELOPER_TOKEN'];
$clientId = $_ENV['GOOGLE_ADS_CLIENT_ID'];
$clientSecret = $_ENV['GOOGLE_ADS_CLIENT_SECRET'];
$refreshToken = $_ENV['GOOGLE_ADS_REFRESH_TOKEN'];
$customerId = str_replace('-', '', $_ENV['GOOGLE_ADS_CUSTOMER_ID']);
$loginCustomerId = str_replace('-', '', $_ENV['GOOGLE_ADS_LOGIN_CUSTOMER_ID']);

echo "Credentials loaded:\n";
echo "- Developer Token: " . substr($developerToken, 0, 10) . "...\n";
echo "- Client ID: " . substr($clientId, 0, 20) . "...\n";
echo "- Customer ID: $customerId\n";
echo "- Login Customer ID: $loginCustomerId\n\n";

try {
    // Build OAuth2 credential
    echo "Building OAuth2 credential...\n";
    $oAuth2Credential = (new OAuth2TokenBuilder())
        ->withClientId($clientId)
        ->withClientSecret($clientSecret)
        ->withRefreshToken($refreshToken)
        ->build();
    echo "✓ OAuth2 credential built successfully\n\n";

    // Build Google Ads client
    echo "Building Google Ads client...\n";
    $googleAdsClient = (new GoogleAdsClientBuilder())
        ->withDeveloperToken($developerToken)
        ->withOAuth2Credential($oAuth2Credential)
        ->withLoginCustomerId($loginCustomerId)
        ->build();
    echo "✓ Google Ads client built successfully\n\n";

    // Test 1: Try to access the new client account directly
    echo "=== TEST 1: Access Client Account Directly ===\n";
    $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();

    echo "Trying to access account: {$customerId}\n";

    try {
        $query = "SELECT customer.id, customer.descriptive_name, customer.manager FROM customer LIMIT 1";
        $request = new SearchGoogleAdsRequest([
            'customer_id' => $customerId,
            'query' => $query,
        ]);

        $response = $googleAdsServiceClient->search($request);

        foreach ($response->iterateAllElements() as $googleAdsRow) {
            $customer = $googleAdsRow->getCustomer();
            echo "✓ Successfully accessed account!\n";
            echo "  - Account Name: " . $customer->getDescriptiveName() . "\n";
            echo "  - Customer ID: " . $customer->getId() . "\n";
            echo "  - Is Manager: " . ($customer->getManager() ? 'Yes (MCC)' : 'No (Client Account)') . "\n\n";
        }
    } catch (\Exception $e) {
        echo "✗ Cannot access account: " . $e->getMessage() . "\n\n";
        echo "This might mean:\n";
        echo "1. Account is still being set up (wait a few minutes)\n";
        echo "2. Account ID is incorrect\n";
        echo "3. OAuth credentials don't have access to this account\n\n";
        exit(1);
    }

    // Test 2: List all client accounts under MCC
    echo "=== TEST 2: List All Client Accounts Under MCC ===\n";

    $query = "SELECT 
        customer_client.client_customer,
        customer_client.descriptive_name,
        customer_client.id,
        customer_client.manager,
        customer_client.status
    FROM customer_client 
    WHERE customer_client.status = 'ENABLED'";

    $request = new SearchGoogleAdsRequest([
        'customer_id' => $loginCustomerId, // Query MCC to see all sub-accounts
        'query' => $query,
    ]);

    $response = $googleAdsServiceClient->search($request);

    $clientAccounts = [];
    foreach ($response->iterateAllElements() as $googleAdsRow) {
        $client = $googleAdsRow->getCustomerClient();
        $clientId = $client->getId();
        $isManager = $client->getManager() ? 'MCC' : 'Client';

        echo "Account: {$client->getDescriptiveName()}\n";
        echo "  - ID: {$clientId}\n";
        echo "  - Type: {$isManager}\n";
        echo "  - Status: {$client->getStatus()}\n\n";

        if (!$client->getManager()) {
            $clientAccounts[] = $clientId;
        }
    }

    if (empty($clientAccounts)) {
        echo "⚠ No client accounts visible from MCC query (might take time to sync)\n\n";
    } else {
        echo "✓ Found " . count($clientAccounts) . " client account(s)\n";
        echo "Available client IDs: " . implode(', ', $clientAccounts) . "\n\n";
    }

    // Test 2: Get account info
    echo "=== TEST 2: Get Account Info ===\n";
    $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();

    $query = "SELECT customer.id, customer.descriptive_name FROM customer LIMIT 1";
    $request = new SearchGoogleAdsRequest([
        'customer_id' => $customerId,
        'query' => $query,
    ]);

    $response = $googleAdsServiceClient->search($request);

    foreach ($response->iterateAllElements() as $googleAdsRow) {
        $customer = $googleAdsRow->getCustomer();
        echo "✓ Account Name: " . $customer->getDescriptiveName() . "\n";
        echo "✓ Customer ID: " . $customer->getId() . "\n";
    }
    echo "\n";

    // Test 2: Get campaigns (without metrics)
    echo "=== TEST 2: Get Campaigns (No Metrics) ===\n";
    $query = "SELECT campaign.id, campaign.name, campaign.status FROM campaign ORDER BY campaign.name";
    $request = new SearchGoogleAdsRequest([
        'customer_id' => $customerId,
        'query' => $query,
    ]);

    $response = $googleAdsServiceClient->search($request);

    $campaignCount = 0;
    foreach ($response->iterateAllElements() as $googleAdsRow) {
        $campaign = $googleAdsRow->getCampaign();
        $campaignCount++;
        echo "Campaign #{$campaignCount}:\n";
        echo "  - ID: " . $campaign->getId() . "\n";
        echo "  - Name: " . $campaign->getName() . "\n";
        echo "  - Status: " . $campaign->getStatus() . "\n";
    }

    if ($campaignCount === 0) {
        echo "⚠ No campaigns found in this account\n";
    } else {
        echo "\n✓ Found {$campaignCount} campaign(s)\n";
    }
    echo "\n";

    // Test 3: Try to get campaigns WITH metrics (will fail if MCC)
    echo "=== TEST 3: Get Campaigns WITH Metrics ===\n";
    try {
        $query = "SELECT 
            campaign.id,
            campaign.name,
            campaign.status,
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

        $metricsCount = 0;
        foreach ($response->iterateAllElements() as $googleAdsRow) {
            $campaign = $googleAdsRow->getCampaign();
            $metrics = $googleAdsRow->getMetrics();
            $metricsCount++;

            echo "Campaign #{$metricsCount}:\n";
            echo "  - Name: " . $campaign->getName() . "\n";
            echo "  - Impressions: " . $metrics->getImpressions() . "\n";
            echo "  - Clicks: " . $metrics->getClicks() . "\n";
            echo "  - Cost: $" . ($metrics->getCostMicros() / 1000000) . "\n";
            echo "  - Conversions: " . $metrics->getConversions() . "\n";
        }

        if ($metricsCount === 0) {
            echo "⚠ No campaigns found (or no data in last 30 days)\n";
        } else {
            echo "\n✓ Successfully retrieved metrics for {$metricsCount} campaign(s)\n";
        }

    } catch (\Exception $e) {
        echo "✗ Error getting metrics: " . $e->getMessage() . "\n";
        echo "  (This means account might be MCC or campaigns have no data)\n";
    }

    echo "\n=== ALL TESTS COMPLETED ===\n";
    echo "✓ API Connection: SUCCESS\n";
    echo "✓ Authentication: SUCCESS\n";
    echo "✓ Account Access: SUCCESS\n";

} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
