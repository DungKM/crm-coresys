<?php

namespace Webkul\EmailExtended\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessEmailTracking extends Command
{
    protected $signature = 'email:process-tracking {--days=7}';
    protected $description = 'Process and aggregate email tracking data';

    public function handle()
    {
        $this->info('Processing email tracking data...');
        
        $days = $this->option('days');
        $startDate = Carbon::now()->subDays($days);
        
        try {
            // Tính open rates 
            $openStats = DB::table('email_tracking')
                ->where('created_at', '>=', $startDate)
                ->where('event_type', 'open')
                ->select(
                    'email_thread_id',
                    DB::raw('COUNT(DISTINCT ip_address) as unique_opens'),
                    DB::raw('COUNT(*) as total_opens')
                )
                ->groupBy('email_thread_id')
                ->get();
            
            foreach ($openStats as $stat) {
                DB::table('email_threads')
                    ->where('id', $stat->email_thread_id)
                    ->update([
                        'total_opens' => $stat->total_opens,
                        'updated_at' => Carbon::now(),
                    ]);
            }
            
            $this->info("Processed {$openStats->count()} open statistics");
            
            // Tính click rates
            $clickStats = DB::table('email_tracking')
                ->where('created_at', '>=', $startDate)
                ->where('event_type', 'click')
                ->select(
                    'email_thread_id',
                    DB::raw('COUNT(*) as total_clicks')
                )
                ->groupBy('email_thread_id')
                ->get();
            
            foreach ($clickStats as $stat) {
                DB::table('email_threads')
                    ->where('id', $stat->email_thread_id)
                    ->update([
                        'total_clicks' => $stat->total_clicks,
                        'updated_at' => Carbon::now(),
                    ]);
            }
            
            $this->info("Processed {$clickStats->count()} click statistics");
            
            $this->info('Tracking data processed successfully!');
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}