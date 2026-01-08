<?php

namespace Webkul\EmailExtended\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\EmailExtended\Http\Controllers\EmailComposerController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendScheduledEmails extends Command
{
    protected $signature = 'email:send-scheduled {--limit=50}';
    protected $description = 'Gửi các email đã lên lịch đến thời gian';

    public function __construct(
        protected EmailRepository $emailRepository
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Checking for scheduled emails...');
        
        $limit = (int) $this->option('limit');
        
        $scheduledEmails = DB::table('emails')
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', Carbon::now())
            ->whereNotNull('user_id')
            ->orderBy('scheduled_at', 'asc')
            ->limit($limit)
            ->lockForUpdate() 
            ->get();
        
        if ($scheduledEmails->isEmpty()) {
            $this->info('No scheduled emails to send.');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$scheduledEmails->count()} scheduled email(s)");
        $this->newLine();
        
        $successCount = 0;
        $failedCount = 0;
        
        foreach ($scheduledEmails as $emailData) {
            try {
                $currentStatus = DB::table('emails')
                    ->where('id', $emailData->id)
                    ->value('status');
                
                if ($currentStatus !== 'scheduled') {
                    $this->line("Email #{$emailData->id} already processed (status: {$currentStatus})");
                    continue;
                }
                
                $email = $this->emailRepository->find($emailData->id);
                
                if (!$email) {
                    $this->error("Email #{$emailData->id} not found in repository");
                    $failedCount++;
                    continue;
                }
                
                $this->line("Processing email #{$email->id}...");
                $this->line("   To: " . $this->formatEmailAddresses($email->to));
                $this->line("   Subject: {$email->subject}");
                $this->line("   Scheduled: {$email->scheduled_at}");
                
                $controller = app(EmailComposerController::class);
                $controller->sendEmail($email);
                
                $successCount++;
                $this->info("Sent successfully!");
                $this->newLine();
                
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("Failed: {$e->getMessage()}");
                $this->newLine();
                
                Log::error('Scheduled email send failed', [
                    'email_id' => $emailData->id ?? 'unknown',
                    'scheduled_at' => $emailData->scheduled_at ?? 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                DB::table('emails')
                    ->where('id', $emailData->id)
                    ->update([
                        'status' => 'failed',
                        'folders' => json_encode(['failed']),
                        'updated_at' => now()
                    ]);
            }
        }
        
        $this->newLine();
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('SUMMARY:');
        $this->info("   Success: {$successCount}");
        $this->info("   Failed: {$failedCount}");
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        return Command::SUCCESS;
    }
    
    /**
     * Format email addresses for display
     */
    protected function formatEmailAddresses($addresses): string
    {
        if (empty($addresses)) {
            return '(none)';
        }
        
        if (is_string($addresses)) {
            $addresses = json_decode($addresses, true);
        }
        
        if (!is_array($addresses)) {
            return $addresses;
        }
        
        $emails = [];
        foreach ($addresses as $addr) {
            if (is_array($addr) && isset($addr['email'])) {
                $emails[] = $addr['email'];
            } elseif (is_string($addr)) {
                $emails[] = $addr;
            }
        }
        
        return implode(', ', $emails);
    }
}