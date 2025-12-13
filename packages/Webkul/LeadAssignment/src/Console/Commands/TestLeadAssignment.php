<?php

namespace Webkul\LeadAssignment\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Webkul\Lead\Models\Lead;

class TestLeadAssignment extends Command
{
    protected $signature = 'lead-assignment:test {count=10 : Số lead tạo để test}';

    protected $description = 'Tạo lead test và hiển thị kết quả phân bổ theo cấu hình hiện tại';

    public function handle(): int
    {
        $count = (int) $this->argument('count');

        // Kiểm tra cấu hình hiện tại
        $config = DB::table('core_config')
            ->whereIn('code', ['lead_assignment.enabled', 'lead_assignment.method', 'lead_assignment.active_users'])
            ->pluck('value', 'code');

        $enabled = (int) ($config['lead_assignment.enabled'] ?? 0);
        $method = $config['lead_assignment.method'] ?? 'round_robin';
        $activeUsers = json_decode($config['lead_assignment.active_users'] ?? '[]', true) ?: [];

        $this->info("=== Cấu hình hiện tại ===");
        $this->line("Trạng thái: " . ($enabled ? '🟢 BẬT' : '🔴 TẮT'));
        $this->line("Phương thức: {$method}");
        $this->line("Sales users: " . (count($activeUsers) ? implode(', ', $activeUsers) : '(không có)'));
        $this->newLine();

        if (!$enabled || empty($activeUsers)) {
            $this->warn('⚠️  Tính năng đang TẮT hoặc chưa có sales user. Lead sẽ có user_id = NULL');
        }

        // Lấy pipeline và stage mặc định
        $pipeline = DB::table('lead_pipelines')->first();
        $stage = $pipeline ? DB::table('lead_pipeline_stages')->where('lead_pipeline_id', $pipeline->id)->first() : null;

        if (!$pipeline || !$stage) {
            $this->error('Không tìm thấy pipeline/stage. Chạy seeder trước: php artisan db:seed');
            return Command::FAILURE;
        }

        $this->info("Đang tạo {$count} lead test...");
        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $createdIds = [];
        for ($i = 1; $i <= $count; $i++) {
            $lead = Lead::create([
                'title' => "Test Lead Auto-Assign #{$i} - " . now()->format('H:i:s'),
                'lead_value' => rand(10000, 100000),
                'status' => 1,
                'lead_pipeline_id' => $pipeline->id,
                'lead_pipeline_stage_id' => $stage->id,
            ]);
            $createdIds[] = $lead->id;
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->newLine(2);

        // Thống kê phân bổ
        $this->info("=== Kết quả phân bổ ===");

        $stats = DB::table('leads')
            ->whereIn('id', $createdIds)
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->get();

        $table = [];
        foreach ($stats as $stat) {
            $userId = $stat->user_id;
            $userName = $userId ? (DB::table('users')->where('id', $userId)->value('name') ?? "User #{$userId}") : '(Chưa phân bổ)';
            $percent = round(($stat->total / $count) * 100, 1);
            $table[] = [
                'User ID' => $userId ?? 'NULL',
                'Tên' => $userName,
                'Số lead' => $stat->total,
                'Phần trăm' => "{$percent}%",
            ];
        }

        $this->table(['User ID', 'Tên', 'Số lead', 'Phần trăm'], $table);

        // Hiển thị danh sách lead vừa tạo
        $this->newLine();
        $this->line("IDs vừa tạo: " . implode(', ', $createdIds));
        $this->info("✅ Hoàn thành! Kiểm tra trong CRM hoặc dùng: php artisan tinker");
        $this->line("   Lead::whereIn('id', [" . implode(',', $createdIds) . "])->get(['id','title','user_id'])");

        return Command::SUCCESS;
    }
}
