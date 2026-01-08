<?php

namespace Webkul\EmailExtended\Console\Commands;

use Illuminate\Console\Command;
use Webkul\EmailExtended\Repositories\EmailScheduledRepository;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Exception;

class RetryFailedEmails extends Command
{
    protected $signature = 'email:retry-failed {--limit=20}';
    protected $description = 'Retry sending failed scheduled emails';
    protected $emailScheduledRepository;

    public function __construct(EmailScheduledRepository $emailScheduledRepository)
    {
        parent::__construct();
        $this->emailScheduledRepository = $emailScheduledRepository;
    }

    public function handle()
    {
        $this->info('Retrying failed emails...');
        
        $limit = $this->option('limit');
        
        // Lấy failed emails 
        $failedEmails = $this->emailScheduledRepository->getModel()
            ->where('status', 'failed')
            ->where('attempts', '<', 3)
            ->limit($limit)
            ->get();
        
        if ($failedEmails->isEmpty()) {
            $this->info('No failed emails to retry');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$failedEmails->count()} failed emails");
        
        $successCount = 0;
        $failedCount = 0;
        $permanentFailed = 0;
        
        foreach ($failedEmails as $email) {
            // Kiểm tra backoff time (5 phút cho lần retry đầu)
            if ($email->last_attempt_at) {
                $lastAttempt = Carbon::parse($email->last_attempt_at);
                if ($lastAttempt->diffInMinutes(Carbon::now()) < 5) {
                    continue; // Chưa đủ thời gian chờ
                }
            }
            
            try {
                $data = json_decode($email->email_data, true);
                
                // Retry gửi email
                Mail::send([], [], function ($message) use ($data, $email) {
                    $message->to($email->recipient_email)
                        ->subject($data['subject'] ?? 'Email')
                        ->html($data['body'] ?? '');
                });
                
                // Thành công
                $this->emailScheduledRepository->update([
                    'status' => 'sent',
                    'sent_at' => Carbon::now(),
                    'attempts' => $email->attempts + 1,
                    'error_message' => null,
                ], $email->id);
                
                $successCount++;
                $this->line("✓ Retry success: {$email->recipient_email}");
                
            } catch (Exception $e) {
                $attempts = $email->attempts + 1;
                
                if ($attempts >= 3) {
                    // Permanent failure
                    $this->emailScheduledRepository->update([
                        'status' => 'permanent_failure',
                        'attempts' => $attempts,
                        'error_message' => $e->getMessage(),
                        'last_attempt_at' => Carbon::now(),
                    ], $email->id);
                    
                    $permanentFailed++;
                    $this->error("✗ Permanent failure: {$email->recipient_email}");
                } else {
                    // Vẫn còn cơ hội retry
                    $this->emailScheduledRepository->update([
                        'status' => 'failed',
                        'attempts' => $attempts,
                        'error_message' => $e->getMessage(),
                        'last_attempt_at' => Carbon::now(),
                    ], $email->id);
                    
                    $failedCount++;
                    $this->warn("⚠ Still failed: {$email->recipient_email} (attempt {$attempts}/3)");
                }
            }
        }
        
        $this->newLine();
        $this->info("Success: {$successCount} | Still Failed: {$failedCount} | Permanent: {$permanentFailed}");
        
        return Command::SUCCESS;
    }
}