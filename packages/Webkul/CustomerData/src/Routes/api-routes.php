<?php

use Illuminate\Support\Facades\Route;
use Webkul\CustomerData\Http\Controllers\Api\CustomerDataApiController;

/*
 * Các route API cho module CustomerData
 * 
 * Các endpoint này có thể được gọi từ:
 * - Landing page
 * - Form bên ngoài
 * - Các dịch vụ bên thứ ba (Zapier, Typeform, v.v.)
 */

Route::group([
    'prefix' => 'api/v1/customer-data',
    'middleware' => ['api'],
], function () {
    
    /**
     * Gửi dữ liệu khách hàng từ form
     * POST /api/v1/customer-data/submit
     * Body JSON:
     * {
     *   "name": "Nguyễn Văn A",
     *   "email": "email@example.com",
     *   "phone": "0901234567",
     *   "source": "website",
     *   "title": "Quan tâm sản phẩm X",
     *   "customer_type": "retail",
     *   "metadata": {}
     * }
     */
    Route::post('/submit', [CustomerDataApiController::class, 'submit']);

    /**
     * Xác thực dữ liệu khách hàng
     * POST /api/v1/customer-data/validate
     * Body JSON:
     * {
     *   "email": "test@example.com",
     *   "phone": "0901234567"
     * }
     */
    Route::post('/validate', [CustomerDataApiController::class, 'validateCustomerData']);

    /**
     * Endpoint Webhook
     * POST /api/v1/customer-data/webhook
     * Nhận dữ liệu từ các nguồn bên ngoài
     */
    Route::post('/webhook', [CustomerDataApiController::class, 'webhook']);
});