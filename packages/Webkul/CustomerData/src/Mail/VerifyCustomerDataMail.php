<?php

namespace Webkul\CustomerData\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\CustomerData\Models\CustomerData;

class VerifyCustomerDataMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerData;

    // Tạo một phiên tin nhắn mới 
    public function __construct(CustomerData $customerData)
    {
        $this->customerData = $customerData;
    }

    // Xây dựng thông điêp 
    public function build()
    {
        return $this->subject('Xác nhận thông tin đăng ký')
                    ->view('customer-data::emails.verify')
                    ->with([
                        'name' => $this->customerData->name,
                        'verifyUrl' => $this->customerData->verify_url,
                        'expiresAt' => $this->customerData->verify_token_expires_at->format('d/m/Y H:i'),
                    ]);
    }
}