<?php

namespace Webkul\EmailExtended\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateEmailStats extends Command
{
    protected $signature = 'email:generate-stats {--period=daily}';
    protected $description = 'Generate email statistics and reports';

    public function handle()
    {
        $this->info('Generating statistics...');
        
        $period = $this->option('period');
        $dateRange = $this->getDateRange($period);
        
        try {
            // Thống kê tổng quan - SỬ DỤNG BẢNG CÓ SẴN
            $totalEmails = DB::table('email_threads')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->count();
            
            $totalOpens = DB::table('email_tracking')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->where('event_type', 'open')
                ->count();
            
            $totalClicks = DB::table('email_tracking')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->where('event_type', 'click')
                ->count();
            
            $scheduledSent = DB::table('email_scheduled')
                ->whereBetween('sent_at', [$dateRange['start'], $dateRange['end']])
                ->where('status', 'sent')
                ->count();
            
            $scheduledFailed = DB::table('email_scheduled')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->where('status', 'failed')
                ->count();
            
            // Hiển thị thống kê
            $this->newLine();
            $this->info("=== Email Statistics ({$period}) ===");
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Total Emails', number_format($totalEmails)],
                    ['Total Opens', number_format($totalOpens)],
                    ['Total Clicks', number_format($totalClicks)],
                    ['Open Rate', $totalEmails > 0 ? round(($totalOpens / $totalEmails) * 100, 2) . '%' : '0%'],
                    ['Click Rate', $totalEmails > 0 ? round(($totalClicks / $totalEmails) * 100, 2) . '%' : '0%'],
                    ['Scheduled Sent', number_format($scheduledSent)],
                    ['Scheduled Failed', number_format($scheduledFailed)],
                    ['Success Rate', ($scheduledSent + $scheduledFailed) > 0 
                        ? round(($scheduledSent / ($scheduledSent + $scheduledFailed)) * 100, 2) . '%'
                        : '0%'
                    ],
                ]
            );
            
            $this->info('Statistics generated successfully!');
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }

    protected function getDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'weekly':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek(),
                ];
            case 'monthly':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                ];
            default: // daily
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                ];
        }
    }
}