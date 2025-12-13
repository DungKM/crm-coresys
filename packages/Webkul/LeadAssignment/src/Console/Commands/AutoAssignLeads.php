<?php

namespace Webkul\LeadAssignment\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Lead\Models\Lead;
use Webkul\LeadAssignment\Services\LeadAssignmentService;

class AutoAssignLeads extends Command
{
    protected $signature = 'lead-assignment:auto-assign {--limit=50 : Số lead tối đa xử lý mỗi lần chạy}';

    protected $description = 'Tự động gán user_id cho các lead chưa được phân bổ (chạy định kỳ qua scheduler)';

    public function handle(LeadAssignmentService $service): int
    {
        $limit = (int) $this->option('limit');

        $leads = Lead::whereNull('user_id')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();

        if ($leads->isEmpty()) {
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($leads as $lead) {
            $userId = $service->assignUserId();

            if (!$userId) {
                continue;
            }

            $lead->user_id = $userId;
            $lead->save();
            $count++;
        }

        if ($count > 0) {
            $this->info("Đã tự động gán {$count} lead.");
        }

        return Command::SUCCESS;
    }
}
