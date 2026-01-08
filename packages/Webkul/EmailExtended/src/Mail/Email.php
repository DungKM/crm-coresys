<?php

namespace Webkul\EmailExtended\Mail;

use Webkul\Email\Mails\Email as BaseEmail;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email as MimeEmail;

class Email extends BaseEmail
{
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Tự động tạo tracking_token nếu chưa có
        if (!$this->email->tracking_token) {
            $this->email->tracking_token = Str::random(32);
            $this->email->save();
        }

        // Build email như bình thường
        parent::build();

        // Thêm SendGrid custom args cho webhook
        $this->withSymfonyMessage(function (MimeEmail $message) {
            // Thêm custom args vào headers (SendGrid sẽ đọc)
            $message->getHeaders()->addTextHeader('X-SMTPAPI', json_encode([
                'unique_args' => [
                    'email_id' => (string)$this->email->id,
                    'thread_id' => (string)$this->email->thread_id,
                    'user_id' => (string)$this->email->user_id,
                ]
            ]));
        });

        return $this;
    }
}