<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Nhớ dòng này

class WhatsAppTestController extends Controller
{
    public function testSend()
    {
        // 1. Lấy thông số trực tiếp từ env để chắc chắn nó nhận
        $url = env('WHATSAPP_API_URL') . '/' . env('WHATSAPP_PHONE_NUMBER_ID') . '/messages';
        $token = env('WHATSAPP_ACCESS_TOKEN');
        
        // 2. Gọi API (Thêm withoutVerifying để bỏ qua lỗi SSL nếu chạy localhost)
        $response = Http::withoutVerifying() 
            ->withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to'                => '84336632069', // Số điện thoại cứng của bạn
                'type'              => 'template',
                'template'          => [
                    'name'     => 'hello_world',
                    'language' => ['code' => 'en_US']
                ]
            ]);

        // 3. In kết quả ra màn hình để xem lỗi là gì
        return $response->body(); 
    }
}