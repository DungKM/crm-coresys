<?php

use Illuminate\Support\Facades\Route;
use Webkul\EmailExtended\Http\Controllers\EmailThreadController;
use Webkul\EmailExtended\Http\Controllers\EmailComposerController;
use Webkul\EmailExtended\Http\Controllers\EmailTrackingController;
use Webkul\EmailExtended\Http\Controllers\EmailSettingsController;

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix'     => config('app.admin_path', 'admin'),
], function () {
    
    Route::prefix('mail')->group(function () {
        Route::get('/', [EmailThreadController::class, 'folder'])
            ->defaults('folder', 'inbox')
            ->name('admin.mail.index');
            
        // Route email settings 
        Route::prefix('settings')->group(function () {
            Route::get('/', [EmailSettingsController::class, 'index'])
                ->name('admin.mail.settings.index');
            
            Route::post('/', [EmailSettingsController::class, 'store'])
                ->name('admin.mail.settings.store');
            
            Route::post('test-sendgrid', [EmailSettingsController::class, 'testSendgrid'])
                ->name('admin.mail.settings.test-sendgrid');
            
            Route::post('test-gmail', [EmailSettingsController::class, 'testGmail'])
                ->name('admin.mail.settings.test-gmail');
            
            Route::post('deactivate', [EmailSettingsController::class, 'deactivate'])
                ->name('admin.mail.settings.deactivate');
            
            Route::delete('/', [EmailSettingsController::class, 'destroy'])
                ->name('admin.mail.settings.destroy');
            
            Route::get('status', [EmailSettingsController::class, 'status'])
                ->name('admin.mail.settings.status');
            
            Route::post('test-webhook', [EmailSettingsController::class, 'testWebhook'])
                ->name('admin.mail.settings.test-webhook');

            Route::post('test-all', [EmailSettingsController::class, 'testAll'])
                ->name('admin.mail.settings.test-all');
        });
            
        // Compose routes
        Route::get('compose', [EmailComposerController::class, 'create'])
            ->name('admin.mail.compose');
        
        Route::post('compose', [EmailComposerController::class, 'store'])
            ->name('admin.mail.store');
        
        Route::get('compose/from-template/{templateId}', [EmailComposerController::class, 'fromTemplate'])
            ->name('admin.mail.compose.from_template');

        // Route hiển thị HTML view 
        Route::get('folder/{folder}', [EmailThreadController::class, 'folder'])
            ->name('admin.mail.folder');
        
        // Route trả JSON cho DataGrid
        Route::get('folder/{folder}/data', [EmailThreadController::class, 'folderData'])
            ->name('admin.mail.folder.data');
            
        // Search route
        Route::get('search', [EmailThreadController::class, 'search'])
            ->name('admin.mail.search');
        
        // Scheduled routes
        Route::get('scheduled', [EmailThreadController::class, 'scheduled'])
            ->name('admin.mail.scheduled');
        
        Route::delete('scheduled/{id}/cancel', [EmailThreadController::class, 'cancelScheduled'])
            ->name('admin.mail.cancel_scheduled');
        
        Route::put('scheduled/{id}/reschedule', [EmailThreadController::class, 'reschedule'])
            ->name('admin.mail.reschedule');
        
        Route::post('scheduled/mass-cancel', [EmailThreadController::class, 'massCancelScheduled'])
            ->name('admin.mail.mass_cancel_scheduled');
        
        Route::post('scheduled/mass-delete', [EmailThreadController::class, 'massDeleteScheduled'])
            ->name('admin.mail.mass_delete_scheduled');
    
        Route::post('email/{id}/cancel-schedule', [EmailComposerController::class, 'cancelSchedule'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.email.cancel_schedule');
        
        Route::post('email/{id}/reschedule', [EmailComposerController::class, 'rescheduleEmail'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.email.reschedule');
    
        // Mass actions
        Route::put('mass_update', [EmailThreadController::class, 'massUpdate'])
            ->name('admin.mail.mass_update');
        
        Route::delete('mass_delete', [EmailThreadController::class, 'massDelete'])
            ->name('admin.mail.mass_delete');
        
        Route::put('mass_restore', [EmailThreadController::class, 'massRestore'])
            ->name('admin.mail.mass_restore');
        
        Route::delete('mass_permanent', [EmailThreadController::class, 'massPermanentDelete'])
            ->name('admin.mail.mass_permanent');
        
        // Draft routes
        Route::post('save-draft', [EmailComposerController::class, 'saveDraft'])
            ->name('admin.mail.save_draft');
        
        Route::get('draft/{id}', [EmailComposerController::class, 'editDraft'])
            ->name('admin.mail.edit_draft');
        
        // Template routes
        Route::post('preview-template', [EmailComposerController::class, 'previewTemplate'])
            ->name('admin.mail.preview_template');
        
        // Attachment routes
        Route::post('attach', [EmailComposerController::class, 'attach'])
            ->name('admin.mail.attach');
        
        Route::get('download/{id?}', [EmailComposerController::class, 'download'])
            ->name('admin.mail.download');
        
        // Tracking routes
        Route::get('tracking/dashboard', [EmailTrackingController::class, 'dashboard'])
            ->name('admin.mail.tracking.dashboard');
        
        Route::get('/show/{id}', [EmailThreadController::class, 'show'])
            ->name('admin.mail.show');
        
        // Email actions - Các route có {id} phải đặt SAU mass actions
        Route::put('{id}/mark-read', [EmailThreadController::class, 'markRead'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.mark_read');
        
        Route::put('{id}/mark-unread', [EmailThreadController::class, 'markUnread'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.mark_unread');
        
        Route::put('{id}/toggle-star', [EmailThreadController::class, 'toggleStar'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.toggle_star');
        
        Route::put('{id}/move', [EmailThreadController::class, 'move'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.move');
        
        Route::put('{id}/restore', [EmailThreadController::class, 'restore'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.restore');
        
        Route::delete('{id}', [EmailThreadController::class, 'destroy'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.delete');
        
        Route::delete('{id}/permanent', [EmailThreadController::class, 'destroyPermanent'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.delete_permanent');
        
        // Tags
        Route::post('{id}/tag', [EmailThreadController::class, 'addTag'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.add_tag');
        
        Route::delete('{id}/tag', [EmailThreadController::class, 'removeTag'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.remove_tag');
        
        // Reply & Forward - Hỗ trợ cả GET và POST
        Route::match(['get', 'post'], '{id}/reply', [EmailComposerController::class, 'reply'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.reply');

        Route::match(['get', 'post'], '{id}/forward', [EmailComposerController::class, 'forward'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.forward');
        
        // Tracking per email
        Route::get('{id}/tracking', [EmailTrackingController::class, 'stats'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.tracking');
        
        Route::put('{id}', [EmailComposerController::class, 'update'])
            ->where('id', '[0-9]+')
            ->name('admin.mail.update');
    });
});

// Public tracking routes 
Route::group(['middleware' => 'web'], function () {
    Route::get('track/open/{id}/{token}', [EmailTrackingController::class, 'trackOpen'])
        ->name('admin.emails.track.open');
    
    Route::get('track/click/{id}/{token}', [EmailTrackingController::class, 'trackClick'])
        ->name('admin.emails.track.click');
});

Route::group([
    'prefix' => 'webhooks/sendgrid',
    'middleware' => ['web'], 
], function () {
    
    // GET endpoint để test trong browser (CHỈ HIỂN THỊ TRONG LOCAL/TESTING)
    Route::get('email', function() {
        // Trong production, trả về 405 Method Not Allowed
        if (app()->environment('production')) {
            return response()->json([
                'error' => 'Method Not Allowed',
                'message' => 'This endpoint only accepts POST requests',
            ], 405);
        }
        
        // Trong local/staging, hiển thị thông tin debug
        return response()->json([
            'status' => 'ok',
            'environment' => app()->environment(),
            'message' => 'SendGrid Webhook Endpoint is accessible',
            'info' => 'This endpoint accepts POST requests from SendGrid',
            'webhook_url' => route('webhooks.sendgrid.email'),
            'methods' => ['POST'],
            'test_instructions' => [
                '1. Configure this URL in SendGrid Dashboard',
                '2. Go to: https://app.sendgrid.com/settings/mail_settings',
                '3. Enable Event Webhook',
                '4. Paste webhook URL and select events to track',
            ],
            'curl_test' => 'curl -X POST ' . route('webhooks.sendgrid.email') . ' -H "Content-Type: application/json" -d \'[{"event":"test"}]\'',
            'time' => now()->toDateTimeString(),
        ]);
    });
    
    // POST endpoint chính - nhận events từ SendGrid
    Route::post('email', [EmailTrackingController::class, 'webhook'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
        ->name('webhooks.sendgrid.email');
});

// Test endpoint riêng (có thể xóa sau)
Route::get('/webhook-test', function() {
    return response()->json([
        'success' => true,
        'message' => 'Webhook test endpoint working',
        'routes' => [
            'GET' => url('webhooks/sendgrid/email'),
            'POST' => route('webhooks.sendgrid.email'),
        ],
    ]);
})->middleware('web');