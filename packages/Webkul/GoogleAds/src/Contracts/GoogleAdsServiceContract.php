<?php

namespace Webkul\GoogleAds\Contracts;

interface GoogleAdsServiceContract
{
    /**
     * Test kết nối tới Google Ads API
     * @return array
     */
    public function testConnection(): array;

    /**
     * Lấy danh sách campaigns
     * @return array
     */
    public function getCampaigns(): array;

    /**
     * Lấy thống kê ads
     * @return array
     */
    public function getAdsStats(): array;
}
