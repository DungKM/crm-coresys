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
     * Lấy chi tiết campaign theo ID
     * @param int|string $campaignId
     * @return array
     */
    public function getCampaignById($campaignId): array;

    /**
     * Tạo campaign mới
     * @param array $data
     * @return array
     */
    public function createCampaign(array $data): array;

    /**
     * Cập nhật campaign
     * @param int|string $campaignId
     * @param array $data
     * @return array
     */
    public function updateCampaign($campaignId, array $data): array;

    /**
     * Xóa campaign (set status = REMOVED)
     * @param int|string $campaignId
     * @return array
     */
    public function deleteCampaign($campaignId): array;

    /**
     * Lấy thống kê ads
     * @return array
     */
    public function getAdsStats(): array;
}
