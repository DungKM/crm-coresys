<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsAppService;
use Webkul\Contact\Models\Person;

class CheckWhatsappSystem extends Command
{
    protected $signature = 'whatsapp:check';
    protected $description = 'Kiểm tra toàn bộ hệ thống WhatsApp CRM';

    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->line("\n════════════════════════════════════════════════════════════");
        $this->line("  🔍 KIỂM TRA HỆ THỐNG WHATSAPP CRM");
        $this->line("════════════════════════════════════════════════════════════\n");

        // TEST 1: Kiểm tra .env
        $this->line("📋 TEST 1: Kiểm tra cấu hình .env");
        $this->line("─────────────────────────────────────────────────────────────");

        $apiUrl = env('WHATSAPP_API_URL');
        $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');
        $token = env('WHATSAPP_ACCESS_TOKEN');

        $this->line("✓ WHATSAPP_API_URL: " . ($apiUrl ? "✅ " . $apiUrl : "❌ KHÔNG CÓ"));
        $this->line("✓ WHATSAPP_PHONE_NUMBER_ID: " . ($phoneId ? "✅ " . $phoneId : "❌ KHÔNG CÓ"));
        $this->line("✓ WHATSAPP_ACCESS_TOKEN: " . ($token ? "✅ " . substr($token, 0, 20) . "..." : "❌ KHÔNG CÓ"));

        if (!$apiUrl || !$phoneId || !$token) {
            $this->error("\n❌ LỖI: Thiếu cấu hình WhatsApp! Kiểm tra file .env");
            return 1;
        }

        $this->line("\n✅ Cấu hình .env OK!\n");

        // TEST 2: Kiểm tra Service
        $this->line("📋 TEST 2: Kiểm tra WhatsAppService");
        $this->line("─────────────────────────────────────────────────────────────");

        $this->line("✓ WhatsAppService instance: ✅");

        // Test formatPhoneNumber
        $reflection = new \ReflectionClass($this->whatsAppService);
        $formatMethod = $reflection->getMethod('formatPhoneNumber');
        $formatMethod->setAccessible(true);

        $testPhones = ['0336632069', '84336632069', '+84336632069'];
        foreach ($testPhones as $phone) {
            $formatted = $formatMethod->invoke($this->whatsAppService, $phone);
            $this->line("✓ Format {$phone} → {$formatted}");
        }

        $this->line("\n✅ WhatsAppService OK!\n");

        // TEST 3: Kiểm tra findPersonByPhone
        $this->line("📋 TEST 3: Kiểm tra findPersonByPhone() và database");
        $this->line("─────────────────────────────────────────────────────────────");

        $testPhone = '0336632069';
        $personsCount = DB::table('persons')->count();
        $this->line("✓ Tổng số Person trong database: {$personsCount}");

        // Tìm persons có số điện thoại test
        $personWithPhone = Person::whereJsonContains('contact_numbers', [['value' => $testPhone]])
            ->orWhereJsonContains('contact_numbers', [['value' => '84' . substr($testPhone, 1)]])
            ->orWhereJsonContains('contact_numbers', [['value' => '+84' . substr($testPhone, 1)]])
            ->first();

        if ($personWithPhone) {
            $this->line("✓ Tìm thấy Person: {$personWithPhone->first_name} (ID: {$personWithPhone->id})");
            $this->line("✓ Contact numbers: " . json_encode($personWithPhone->contact_numbers));

            // Test findPersonByPhone
            $found = $this->whatsAppService->findPersonByPhone($testPhone);
            if ($found && $found->id === $personWithPhone->id) {
                $this->line("✅ findPersonByPhone('{$testPhone}') → Person ID {$found->id} ✓");
            } else {
                $this->line("❌ findPersonByPhone('{$testPhone}') → Không tìm thấy hoặc sai");
            }
        } else {
            $this->warn("⚠️  Không tìm thấy Person với số {$testPhone}");
            $this->line("   Hãy tạo Lead mới hoặc thêm số điện thoại vào contact");
        }

        $this->line("");

        // TEST 4: Kiểm tra Database Tables
        $this->line("📋 TEST 4: Kiểm tra Database Tables");
        $this->line("─────────────────────────────────────────────────────────────");

        $tables = [
            'persons' => 'Khách hàng',
            'leads' => 'Lead/Cơ hội',
            'whatsapp_messages' => 'Tin nhắn WhatsApp',
            'activities' => 'Hoạt động',
        ];

        foreach ($tables as $table => $desc) {
            $count = DB::table($table)->count();
            $this->line("✓ {$table} ({$desc}): {$count} records");
        }

        $this->line("");

        // TEST 5: Kiểm tra Webhook
        $this->line("📋 TEST 5: Kiểm tra Webhook");
        $this->line("─────────────────────────────────────────────────────────────");

        $webhookUrl = env('APP_URL') . '/webhook/whatsapp';
        $this->line("✓ Webhook URL: {$webhookUrl}");
        $this->line("✓ Verify Token: krayin_crm_secret_123");

        $this->line("");

        // TEST 6: Kiểm tra Event Listeners
        $this->line("📋 TEST 6: Kiểm tra Event Listeners");
        $this->line("─────────────────────────────────────────────────────────────");

        $eventServiceProvider = new \App\Providers\EventServiceProvider(app());
        $listeners = $eventServiceProvider->listen ?? [];

        $this->line("✓ lead.create.after listeners:");
        if (isset($listeners['lead.create.after'])) {
            foreach ($listeners['lead.create.after'] as $listener) {
                $this->line("   - {$listener}");
            }
        } else {
            $this->error("   ❌ Không có listener nào!");
        }

        $this->line("");

        // SUMMARY
        $this->line("════════════════════════════════════════════════════════════");
        $this->line("  ✅ KIỂM TRA HOÀN TẤT");
        $this->line("════════════════════════════════════════════════════════════\n");

        $this->info("📝 CÁC BƯỚC TIẾP THEO:\n");

        $this->info("1️⃣  TẠO LEAD MỚI VỚI SỐ 0336632069:");
        $this->line("   - Vào CRM → Leads → New Lead");
        $this->line("   - Nhập số điện thoại: 0336632069");
        $this->line("   - Lưu Lead");
        $this->line("   - Kiểm tra storage/logs/laravel.log xem event được dispatch\n");

        $this->info("2️⃣  TEST WEBHOOK INCOMING MESSAGE:");
        $this->line("   - Dùng curl hoặc Postman POST tới {$webhookUrl}");
        $this->line("   - Kiểm tra log xem webhook nhận được message không\n");

        $this->info("3️⃣  KIỂM TRA LOGS:");
        $this->line("   - tail -f storage/logs/laravel.log | grep -i whatsapp");
        $this->line("   - Tìm các dòng [WhatsApp] để debug\n");

        $this->info("4️⃣  CHẠY LỆNH KIỂM TRA LẠI:");
        $this->line("   - php artisan whatsapp:check\n");

        return 0;
    }
}
