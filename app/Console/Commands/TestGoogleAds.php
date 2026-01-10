<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\GoogleAds\Http\Controllers\GoogleAdsController;

class TestGoogleAds extends Command
{
    protected $signature = 'google-ads:test-connection';

    protected $description = 'Test Google Ads API connection';

    public function handle()
    {
        $this->info('Testing Google Ads Connection...');
        $this->info('');

        // Debug: Check env loading
        $developerToken = env('GOOGLE_ADS_DEVELOPER_TOKEN');
        $this->info('Developer Token Loaded: ' . (!empty($developerToken) ? 'YES' : 'NO'));
        if (!empty($developerToken)) {
            $this->info('  Value: ' . substr($developerToken, 0, 15) . '***');
        }

        $this->info('');
        $controller = new GoogleAdsController();
        $response = $controller->testConnection();

        $this->info('Response:');
        $this->info($response->getContent());

        return 0;
    }
}
