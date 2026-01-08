<?php

namespace Webkul\EmailExtended\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupEmailTracking extends Command
{
    protected $signature = 'email:cleanup-tracking {--days=90} {--dry-run}';
    protected $description = 'Clean up old email tracking records';

    public function handle()
    {
        $this->info('Starting cleanup...');
        
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Cutoff date: {$cutoffDate->format('Y-m-d')}");
        
        // Đếm records sẽ xóa
        $trackingCount = DB::table('email_tracking')
            ->where('created_at', '<', $cutoffDate)
            ->count();
        
        $failedCount = DB::table('email_scheduled')
            ->where('status', 'failed')
            ->where('created_at', '<', $cutoffDate)
            ->count();
        
        $this->table(
            ['Type', 'Count'],
            [
                ['Tracking Records', number_format($trackingCount)],
                ['Failed Emails', number_format($failedCount)],
            ]
        );
        
        if ($dryRun) {
            $this->warn('DRY RUN - No data deleted');
            return Command::SUCCESS;
        }
        
        if (!$this->confirm('Delete these records?')) {
            $this->info('Cancelled');
            return Command::SUCCESS;
        }
        
        try {
            // Xóa tracking cũ
            $deleted1 = DB::table('email_tracking')
                ->where('created_at', '<', $cutoffDate)
                ->delete();
            
            // Xóa failed emails cũ
            $deleted2 = DB::table('email_scheduled')
                ->where('status', 'failed')
                ->where('created_at', '<', $cutoffDate)
                ->delete();
            
            $this->info("Deleted {$deleted1} tracking records");
            $this->info("Deleted {$deleted2} failed emails");
            $this->info('Cleanup completed!');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}