<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




// Route::get('/test-translate', function () {
//     try {
//         $tr = new GoogleTranslate();
//         $tr->setSource('en'); 
//         $tr->setTarget('vi');
        
//         // Thử dịch đúng 1 từ
//         $result = $tr->translate('Dashboard');
        
//         return "<h1>Kết quả: $result</h1> (Nếu thấy 'Bảng điều khiển' là ngon, nếu thấy 'Dashboard' là chưa được)";
//     } catch (\Exception $e) {
//         // In lỗi chi tiết ra màn hình
//         dd("LỖI KẾT NỐI: " . $e->getMessage());
//     }
// });

// Route::get('/auto-translate-krayin', function () {
//     set_time_limit(3000);
//     $tr = new GoogleTranslate();
//     $tr->setSource('en');
//     $tr->setTarget('vi');

//     // Đường dẫn gốc
//     $path = base_path('packages/Webkul');

//     if (!File::exists($path)) {
//         // Trường hợp cài qua Composer mà không có thư mục packages
//         $path = base_path('vendor/krayin'); 
//         if (!File::exists($path)) {
//              $path = base_path('vendor/webkul'); // Fallback tìm trong vendor
//         }
//     }

//     echo "<h1>Đang quét tại thư mục: $path</h1>";

//     $files = File::allFiles($path);
//     $count = 0;
    
//     echo "<div style='font-family: monospace; line-height: 1.5;'>";

//     foreach ($files as $file) {
//         $realPath = $file->getPathname();
        
//         // --- FIX QUAN TRỌNG CHO WINDOWS ---
//         // Đổi tất cả dấu gạch ngược (\\) thành gạch xuôi (/) để so sánh chuẩn xác
//         $normalizedPath = str_replace('\\', '/', $realPath);

//         // Kiểm tra xem file có nằm trong thư mục ngôn ngữ tiếng Anh không
//         if (str_contains($normalizedPath, 'Resources/lang/en') && $file->getExtension() == 'php') {
            
//             // Tạo đường dẫn mới: thay thế '/en/' hoặc '\\en\' thành '/vi/'
//             // Dùng str_replace thông minh để bắt cả 2 trường hợp
//             $newPath = str_replace(
//                 ['Resources/lang/en', 'Resources\\lang\\en'], 
//                 ['Resources/lang/vi', 'Resources\\lang\\vi'], 
//                 $realPath
//             );
            
//             $newDir = dirname($newPath);

//             if (!File::exists($newDir)) {
//                 File::makeDirectory($newDir, 0755, true);
//             }

//             $langArray = include $realPath;
//             if (!is_array($langArray)) continue;

//             // Hàm dịch đệ quy
//             $translateArray = function ($array) use (&$translateArray, $tr) {
//                 $newArray = [];
//                 foreach ($array as $key => $value) {
//                     if (is_array($value)) {
//                         $newArray[$key] = $translateArray($value);
//                     } else {
//                         if (is_string($value) && !empty($value)) {
//                             try {
//                                 $newArray[$key] = $tr->translate($value);
//                             } catch (\Exception $e) {
//                                 $newArray[$key] = $value;
//                             }
//                         } else {
//                             $newArray[$key] = $value;
//                         }
//                     }
//                 }
//                 return $newArray;
//             };

//             $translatedData = $translateArray($langArray);

//             $content = "<?php\n\nreturn " . var_export($translatedData, true) . ";\n";
//             $content = str_replace(["array (", "),", ");"], ["[", "],", "];"], $content);

//             File::put($newPath, $content);
            
//             // In ra tên file để biết tiến độ
//             echo "✅ OK: " . basename($newPath) . "<br>";
            
//             // Đẩy output ra trình duyệt ngay lập tức
//             if (ob_get_level() > 0) { ob_flush(); flush(); }
            
//             $count++;
//             // Giảm thời gian nghỉ xuống 0.1s để chạy nhanh hơn
//             usleep(100000); 
//         }
//     }
//     echo "</div>";
    
//     if ($count == 0) {
//         return "<h2>Vẫn chưa tìm thấy file?</h2> Hãy kiểm tra xem trong thư mục <code>packages/Webkul</code> có folder nào tên là <code>Resources/lang/en</code> không.";
//     }

//     return "<h2>HOÀN TẤT! Đã dịch xong $count file.</h2>";
// });



// Route::get('/setup-multilang', function () {
//     // 1. Xác định đường dẫn file
//     $basePath = base_path('packages/Webkul/Admin/src/Resources/lang');
    
//     // Nếu chạy trong vendor thì đổi đường dẫn
//     if (!File::exists($basePath)) {
//         $basePath = base_path('vendor/webkul/admin/src/Resources/lang');
//     }

//     $pathEN = $basePath . '/en/app.php';
//     $pathVI = $basePath . '/vi/app.php';
//     $dirVI = $basePath . '/vi';

//     // 2. Tạo thư mục vi nếu chưa có
//     if (!File::exists($dirVI)) {
//         File::makeDirectory($dirVI, 0755, true);
//     }

//     // 3. COPY nội dung hiện tại (đang là Tiếng Việt) sang file vi/app.php
//     if (File::exists($pathEN)) {
//         $currentContent = File::get($pathEN);
//         File::put($pathVI, $currentContent);
//         echo "✅ Đã chuyển nội dung Tiếng Việt sang: $pathVI <br>";
//     }

//     // 4. TẠO LẠI file Tiếng Anh chuẩn (Nội dung gốc)
//     $englishContent = "<?php
//     return [
//         'layouts' => [
//             'app-version' => 'Version : :version',
//             'dashboard' => 'Dashboard',
//             'leads' => 'Leads',
//             'quotes' => 'Quotes',
//             'quote' => 'Quote',
//             'mail' => [
//                 'title' => 'Mail',
//                 'compose' => 'Compose',
//                 'inbox' => 'Inbox',
//                 'draft' => 'Draft',
//                 'outbox' => 'Outbox',
//                 'sent' => 'Sent',
//                 'trash' => 'Trash',
//                 'setting' => 'Setting',
//             ],
//             'activities' => 'Activities',
//             'contacts' => 'Contacts',
//             'persons' => 'Persons',
//             'organizations' => 'Organizations',
//             'products' => 'Products',
//             'settings' => 'Settings',
//             'roles' => 'Roles',
//             'users' => 'Users',
//             'attributes' => 'Attributes',
//             'sources' => 'Sources',
//             'types' => 'Types',
//             'email-templates' => 'Email Templates',
//             'workflows' => 'Workflows',
//             'webhooks' => 'Webhooks',
//             'my-account' => 'My Account',
//             'sign-out' => 'Sign Out',
//             'back' => 'Back',
//             'name' => 'Name',
//             'configuration' => 'Configuration',
//             'groups' => 'Groups',
//             'pipelines' => 'Pipelines',
//             'tags' => 'Tags',
//             'web-forms' => 'Web Forms',
//             'warehouses' => 'Warehouses',
//         ],
//         'dashboard' => [
//             'title' => 'Dashboard',
//             'cards' => 'Cards',
//             'column' => 'Column',
//             'attribute' => 'Attribute',
//             'rows' => 'Rows',
//             'top_leads' => 'Top Leads',
//             'pipelines' => 'Pipelines',
//             'top_customers' => 'Top Customers',
//             'emails' => 'Emails',
//             'custom_card' => 'Custom Card',
//             'leads_over_time' => 'Leads Over Time',
//             'no_record_found' => 'No record found',
//         ],
//         'common' => [
//             'no-result-found' => 'No result found',
//             'save' => 'Save',
//             'cancel' => 'Cancel',
//             'yes' => 'Yes',
//             'no' => 'No',
//             'delete' => 'Delete',
//             'edit' => 'Edit',
//             'add' => 'Add',
//             'confirm-delete-message' => 'Are you sure you want to delete this?',
//             'name' => 'Name',
//             'email' => 'Email',
//             'code' => 'Code',
//             'status' => 'Status',
//             'created_at' => 'Created At',
//             'updated_at' => 'Updated At',
//             'id' => 'ID',
//             'action' => 'Action',
//         ],
//         // ... (Bạn có thể thêm các key khác nếu thiếu)
//     ];";

//     File::put($pathEN, $englishContent);
//     echo "✅ Đã khôi phục Tiếng Anh gốc tại: $pathEN <br>";

//     // 5. Đảm bảo Database có ngôn ngữ 'vi'
//     try {
//         $exists = DB::table('locales')->where('code', 'vi')->exists();
//         if (!$exists) {
//             DB::table('locales')->insert([
//                 'code' => 'vi',
//                 'name' => 'Vietnamese',
//                 'direction' => 'ltr',
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//             echo "✅ Đã thêm ngôn ngữ Tiếng Việt vào Database.<br>";
//         } else {
//             echo "ℹ️ Ngôn ngữ Tiếng Việt đã có trong Database.<br>";
//         }
//     } catch (\Exception $e) {
//         echo "⚠️ Không thể ghi vào Database (có thể do cấu hình khác), bỏ qua bước này.<br>";
//     }

//     echo "<h1>HOÀN TẤT! Bây giờ bạn đã có 2 ngôn ngữ riêng biệt.</h1>";
//     echo "<p>Vui lòng chạy lệnh: <code>php artisan optimize:clear</code></p>";
// });

// Dán đoạn này vào cuối file routes/web.php

use App\Services\WhatsAppService;
use App\Http\Controllers\WhatsAppTestController;
use App\Http\Controllers\WhatsAppController;


Route::get('/test-whatsapp', function (WhatsAppService $whatsapp) {
    // --- CẤU HÌNH TEST ---
    // Thay số này bằng số điện thoại THẬT của bạn (đã xác minh trên Meta)
    // Lưu ý: Mã quốc gia 84, bỏ số 0 đầu. VD: 84912345678
    $phoneReceiver = '84336632069'; 
    
    // Gửi thử template 'hello_world'
    // Đây là template mẫu luôn có sẵn khi tạo tài khoản Meta
    $result = $whatsapp->sendTemplate($phoneReceiver, 'hello_world', 'en_US');

    return response()->json([
        'message' => 'Đã gửi lệnh test!',
        'ket_qua_tra_ve_tu_meta' => $result
    ]);
});

Route::get('/test-whatsapp', [WhatsAppTestController::class, 'testSend']);

// Route TEST: Mô phỏng tin nhắn WhatsApp đến (chỉ dùng để debug)
Route::get('/test-incoming-message', function () {
    $phone = request('phone', '84336632069'); // Số điện thoại khách
    $message = request('message', 'Tin nhắn test từ khách hàng');
    
    // Tạo payload giả lập giống Facebook gửi
    $fakePayload = [
        'object' => 'whatsapp_business_account',
        'entry' => [[
            'id' => '123',
            'changes' => [[
                'value' => [
                    'messaging_product' => 'whatsapp',
                    'metadata' => ['phone_number_id' => '123'],
                    'messages' => [[
                        'from' => $phone,
                        'id' => 'wamid_test_' . time(),
                        'timestamp' => time(),
                        'type' => 'text',
                        'text' => ['body' => $message]
                    ]]
                ],
                'field' => 'messages'
            ]]
        ]]
    ];
    
    // Gọi controller trực tiếp
    $controller = app(\App\Http\Controllers\WhatsAppController::class);
    $request = new \Illuminate\Http\Request();
    $request->replace($fakePayload);
    
    $result = $controller->handleIncomingMessage($request);
    
    return response()->json([
        'message' => 'Test completed! Check logs for details.',
        'phone' => $phone,
        'test_message' => $message,
        'result' => $result->getContent()
    ]);
});

// Route cho webhook (GET để xác minh, POST để nhận tin)
// Loại bỏ CSRF middleware để Facebook có thể gửi request
Route::match(['get', 'post'], '/webhook/whatsapp', [WhatsAppController::class, 'verifyWebhookOrHandle'])->withoutMiddleware(['web']);

// Route để hiển thị giao diện chat
Route::get('admin/leads/{id}/chat', [App\Http\Controllers\WhatsAppController::class, 'chat'])->name('admin.leads.chat.index');

// Route để gửi tin nhắn từ giao diện chat
Route::post('admin/leads/{id}/chat/send', [App\Http\Controllers\WhatsAppController::class, 'sendFromChat'])->name('admin.leads.chat.send');




Route::middleware(['web'])->prefix('admin')->group(function () {
    // Route xử lý gửi tin nhắn AJAX từ khung chat
    Route::post('leads/{id}/whatsapp-reply', [WhatsAppController::class, 'reply'])
        ->name('admin.leads.whatsapp.reply');
    
    // Route lấy tin nhắn mới (cho auto-refresh)
    Route::get('leads/{id}/whatsapp-new-messages', [WhatsAppController::class, 'getNewMessages'])
        ->name('admin.leads.whatsapp.messages');
});



