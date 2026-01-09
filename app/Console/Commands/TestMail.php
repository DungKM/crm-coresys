<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class TestMail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Test send mail';

    public function handle()
    {
        $email = $this->argument('email');

        Mail::raw('TEST MAIL OK', function ($m) use ($email) {
            $m->to($email)
              ->subject('Test Mail from Laravel');
        });

        $this->info('Mail sent to ' . $email);
    }
}
