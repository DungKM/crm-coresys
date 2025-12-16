<?php

namespace Webkul\LeadAssignment\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Lead\Models\Lead;
use Webkul\LeadAssignment\Services\LeadAssignmentService;

class BackfillLeadAssignment extends Command
{
    protected $signature = 'lead-assignment:backfill {--chunk=200 : Số bản ghi xử lý mỗi lần} {--dry-run : Chỉ hiển thị, không cập nhật}';

    protected $description = 'Gán user_id cho các lead chưa có người phụ trách dựa trên cấu hình phân bổ lead';

    public function handle(LeadAssignmentService $service): int
    {
        $chunk = (int) $this->option('chunk');
        $dry = (bool) $this->option('dry-run');

        $this->info('Bắt đầu backfill lead assignment...');

        $count = 0;

        Lead::whereNull('user_id')
            ->orderBy('id')
            ->chunkById($chunk, function ($leads) use ($service, $dry, &$count) {
                foreach ($leads as $lead) {
                    $userId = $service->assignUserId();

                    if (!$userId) {
                        $this->warn("Lead #{$lead->id}: không tìm được user_id (config tắt hoặc không có active_users)");
                        continue;
                    }

                    if ($dry) {
                        $this->line("[DRY-RUN] Lead #{$lead->id} -> user_id {$userId}");
                        $count++;
                        continue;
                    }

                    $lead->user_id = $userId;
                    $lead->save();
                    $count++;
                }
            });

        $this->info("Hoàn thành. Đã xử lý {$count} lead.");

        return Command::SUCCESS;
    }
}
