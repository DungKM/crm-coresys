<?php

namespace Webkul\EmailExtended\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Mail, Log, Config};
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\EmailExtended\Models\EmailSettings;

class EmailSettingsController extends Controller
{
    /**
     * Hiển thị trang settings
     */
    public function index()
    {
        $settings = EmailSettings::where('user_id', auth()->guard('user')->id())->first();
        
        return view('email_extended::settings.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Lưu settings
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'from_email' => 'required|email',
            'from_name' => 'required|string|max:255',
            'sendgrid_api_key' => 'required|string',
            'gmail_address' => 'nullable|email',
            'gmail_app_password' => 'nullable|string',
            'signature' => 'nullable|string',
            
            // Webhook validation
            'webhook_enabled' => 'nullable|boolean',
            'webhook_signing_key' => 'nullable|string|max:500',
            'webhook_events' => 'nullable|array',
            'webhook_events.*' => 'string|in:processed,delivered,open,click,bounce,dropped,spamreport,unsubscribe',
        ]);

        try {
            $userId = auth()->guard('user')->id();
            
            $data = [
                'from_email' => $request->from_email,
                'from_name' => $request->from_name,
                'sendgrid_api_key' => $request->sendgrid_api_key,
                'gmail_address' => $request->gmail_address,
                'gmail_app_password' => $request->gmail_app_password,
                'signature' => $request->signature,
                'is_active' => false, // Chưa active cho đến khi verify
                
                // Webhook data
                'webhook_enabled' => $request->boolean('webhook_enabled'),
                'webhook_signing_key' => $request->webhook_signing_key,
                'webhook_events' => $request->webhook_events ?? [],
            ];

            $settings = EmailSettings::updateOrCreate(
                ['user_id' => $userId],
                $data
            );

            Log::info('Email Settings Saved', [
                'user_id' => $userId,
                'from_email' => $request->from_email,
                'webhook_enabled' => $request->boolean('webhook_enabled'),
            ]);

            session()->flash('success', 'Settings saved successfully! Please test connections to activate.');
            
            return redirect()->route('admin.mail.settings.index');

        } catch (\Exception $e) {
            Log::error('Email Settings Save Error: ' . $e->getMessage(), [
                'user_id' => auth()->guard('user')->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            session()->flash('error', 'Failed to save settings: ' . $e->getMessage());
            
            return redirect()->back()->withInput();
        }
    }

    /**
     * Test SendGrid connection
     */
    public function testSendgrid(Request $request)
    {
        $this->validate($request, [
            'sendgrid_api_key' => 'required|string',
            'from_email' => 'required|email',
            'from_name' => 'required|string',
        ]);

        try {
            // Configure mailer với SendGrid settings
            Config::set('mail.mailers.smtp.host', 'smtp.sendgrid.net');
            Config::set('mail.mailers.smtp.port', 587);
            Config::set('mail.mailers.smtp.username', 'apikey');
            Config::set('mail.mailers.smtp.password', $request->sendgrid_api_key);
            Config::set('mail.mailers.smtp.encryption', 'tls');
            Config::set('mail.from.address', $request->from_email);
            Config::set('mail.from.name', $request->from_name);

            // Send test email
            Mail::raw('This is a test email from your CRM system. If you receive this, your SendGrid configuration is working correctly!', function ($message) use ($request) {
                $message->to($request->from_email)
                        ->subject('SendGrid Test - Connection Successful');
            });

            // Update verification status
            $userId = auth()->guard('user')->id();
            $settings = EmailSettings::where('user_id', $userId)->first();
            
            if ($settings) {
                $settings->update([
                    'sendgrid_verified' => true,
                    'sendgrid_verified_at' => now(),
                ]);
                
                // Activate nếu không cần Gmail hoặc Gmail đã verified
                if (empty($settings->gmail_address) || $settings->gmail_verified) {
                    $settings->update(['is_active' => true]);
                }
            }

            Log::info('SendGrid Test Successful', [
                'user_id' => $userId,
                'from_email' => $request->from_email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SendGrid connection successful! Test email sent to ' . $request->from_email,
            ]);

        } catch (\Exception $e) {
            Log::error('SendGrid Test Failed: ' . $e->getMessage(), [
                'user_id' => auth()->guard('user')->id(),
                'from_email' => $request->from_email ?? null,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'help' => 'Please check your SendGrid API key and make sure it has "Mail Send" permission.',
            ], 400);
        }
    }

    /**
     * Test Gmail IMAP connection
     */
    public function testGmail(Request $request)
    {
        $this->validate($request, [
            'gmail_address' => 'required|email',
            'gmail_app_password' => 'required|string',
        ]);

        try {
            // Test IMAP connection
            $mailbox = @imap_open(
                '{imap.gmail.com:993/imap/ssl}INBOX',
                $request->gmail_address,
                $request->gmail_app_password
            );

            if (!$mailbox) {
                throw new \Exception(imap_last_error() ?: 'Could not connect to Gmail IMAP');
            }

            // Get mailbox info
            $check = imap_check($mailbox);
            
            // Close connection
            imap_close($mailbox);

            // Update verification status
            $userId = auth()->guard('user')->id();
            $settings = EmailSettings::where('user_id', $userId)->first();
            
            if ($settings) {
                $settings->update([
                    'gmail_verified' => true,
                    'gmail_verified_at' => now(),
                ]);
                
                // Activate nếu SendGrid đã verified
                if ($settings->sendgrid_verified) {
                    $settings->update(['is_active' => true]);
                }
            }

            Log::info('Gmail IMAP Test Successful', [
                'user_id' => $userId,
                'gmail_address' => $request->gmail_address,
                'mailbox_messages' => $check->Nmsgs ?? 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gmail IMAP connection successful!',
            ]);

        } catch (\Exception $e) {
            Log::error('Gmail IMAP Test Failed: ' . $e->getMessage(), [
                'user_id' => auth()->guard('user')->id(),
                'gmail_address' => $request->gmail_address ?? null,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'help' => 'Please check your Gmail address and App Password. Make sure you are using an App Password, not your regular Gmail password.',
            ], 400);
        }
    }

    /**
     * Test Webhook configuration
     * 
     * Method này kiểm tra:
     * 1. User đã save settings chưa
     * 2. SendGrid đã verify chưa (bắt buộc)
     * 3. Events có hợp lệ không
     * 4. Cập nhật webhook_verified_at nếu OK
     */
    public function testWebhook(Request $request)
    {
        $this->validate($request, [
            'webhook_signing_key' => 'nullable|string|max:500',
            'webhook_events' => 'required|array|min:1',
            'webhook_events.*' => 'string|in:processed,delivered,open,click,bounce,dropped,spamreport,unsubscribe',
        ]);

        try {
            $userId = auth()->guard('user')->id();
            $settings = EmailSettings::where('user_id', $userId)->first();
            
            // Check 1: Settings tồn tại chưa
            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email settings not found. Please save your settings first.',
                    'help' => 'Click "Save Settings" button before testing webhook.',
                ], 400);
            }

            // Check 2: SendGrid đã verify chưa (bắt buộc vì webhook chỉ hoạt động khi gửi được email)
            if (!$settings->sendgrid_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify SendGrid connection first before testing webhook.',
                    'help' => 'Test SendGrid connection above and make sure it passes before configuring webhook.',
                ], 400);
            }

            // Check 3: Có chọn ít nhất 1 event không
            if (empty($request->webhook_events)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one event to track.',
                    'help' => 'Check the events you want to receive notifications for.',
                ], 400);
            }

            // All checks passed - Update verification timestamp
            $settings->update([
                'webhook_verified_at' => now(),
            ]);

            $webhookUrl = route('webhooks.sendgrid.email');
            $selectedEvents = $request->webhook_events;

            // Log để debug
            Log::info('Webhook Test Successful', [
                'user_id' => $userId,
                'webhook_url' => $webhookUrl,
                'events' => $selectedEvents,
                'has_signing_key' => !empty($request->webhook_signing_key),
            ]);

            // Build detailed instructions
            $instructions = "Webhook configuration verified!\n\n";
            $instructions .= "Next steps to complete setup:\n\n";
            $instructions .= "1. Go to SendGrid Dashboard:\n";
            $instructions .= "   https://app.sendgrid.com/settings/mail_settings\n\n";
            $instructions .= "2. Click on 'Event Webhook' (or 'Event Notification')\n\n";
            $instructions .= "3. Enter this HTTP POST URL:\n";
            $instructions .= "   {$webhookUrl}\n\n";
            $instructions .= "4. Select these events (must match):\n";
            foreach ($selectedEvents as $event) {
                $instructions .= "   ✓ " . ucfirst($event) . "\n";
            }
            $instructions .= "\n5. Enable 'Event Webhook Status' toggle\n\n";
            $instructions .= "6. Click 'Save' button\n\n";
            $instructions .= "7. Send a test email to verify webhook is receiving events\n\n";
            
            if (!empty($request->webhook_signing_key)) {
                $instructions .= "Signature verification is enabled for security.\n";
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook configured successfully!',
                'instructions' => $instructions,
                'webhook_url' => $webhookUrl,
                'selected_events' => $selectedEvents,
                'has_signing_key' => !empty($request->webhook_signing_key),
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook Test Failed: ' . $e->getMessage(), [
                'user_id' => auth()->guard('user')->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Configuration failed: ' . $e->getMessage(),
                'help' => 'Please check the logs or contact support if the issue persists.',
            ], 500);
        }
    }

    /**
     * Deactivate settings (sẽ dùng .env mặc định)
     */
    public function deactivate()
    {
        try {
            $userId = auth()->guard('user')->id();
            
            $settings = EmailSettings::where('user_id', $userId)->first();
            
            if ($settings) {
                $settings->update(['is_active' => false]);
                
                Log::info('Email Settings Deactivated', [
                    'user_id' => $userId,
                ]);
            }

            session()->flash('success', 'Email settings deactivated. System will use default configuration.');
            
            return redirect()->route('admin.mail.settings.index');

        } catch (\Exception $e) {
            Log::error('Deactivate Settings Error: ' . $e->getMessage());
            
            session()->flash('error', 'Failed to deactivate settings.');
            return redirect()->back();
        }
    }

    /**
     * Xóa settings
     */
    public function destroy()
    {
        try {
            $userId = auth()->guard('user')->id();
            
            $deleted = EmailSettings::where('user_id', $userId)->delete();

            if ($deleted) {
                Log::info('Email Settings Deleted', [
                    'user_id' => $userId,
                ]);
            }

            session()->flash('success', 'Email settings deleted successfully.');
            
            return redirect()->route('admin.mail.settings.index');

        } catch (\Exception $e) {
            Log::error('Delete Settings Error: ' . $e->getMessage());
            
            session()->flash('error', 'Failed to delete settings.');
            return redirect()->back();
        }
    }

    /**
     * Lấy status hiện tại (JSON API)
     */
    public function status()
    {
        try {
            $settings = EmailSettings::getCurrentUserSettings();
            
            if (!$settings) {
                return response()->json([
                    'configured' => false,
                    'message' => 'No email settings configured',
                ]);
            }

            return response()->json([
                'configured' => true,
                'is_active' => $settings->is_active,
                
                // SendGrid info
                'sendgrid_verified' => $settings->sendgrid_verified,
                'sendgrid_verified_at' => $settings->sendgrid_verified_at?->toIso8601String(),
                
                // Gmail info
                'gmail_verified' => $settings->gmail_verified,
                'gmail_verified_at' => $settings->gmail_verified_at?->toIso8601String(),
                
                // Email info
                'from_email' => $settings->from_email,
                'from_name' => $settings->from_name,
                'emails_sent' => $settings->emails_sent_count,
                'last_sent_at' => $settings->last_email_sent_at?->diffForHumans(),
                
                // Webhook info
                'webhook_enabled' => $settings->webhook_enabled,
                'webhook_configured' => $settings->isWebhookConfigured(),
                'webhook_status' => $settings->webhook_status,
                'webhook_events' => $settings->webhook_events ?? [],
                'webhook_events_count' => count($settings->webhook_events ?? []),
                'webhook_last_verified' => $settings->webhook_verified_at?->diffForHumans(),
                'webhook_needs_reverification' => $settings->needsWebhookReverification(),
                
                // Summary
                'fully_configured' => $settings->hasSendgridConfigured() && ($settings->webhook_enabled ? $settings->isWebhookConfigured() : true),
            ]);

        } catch (\Exception $e) {
            Log::error('Get Settings Status Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to get status',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test toàn bộ configuration (SendGrid + Gmail + Webhook)
     * Helper method để test tất cả trong 1 lần
     */
    public function testAll(Request $request)
    {
        try {
            $results = [
                'sendgrid' => false,
                'gmail' => false,
                'webhook' => false,
                'overall' => false,
            ];

            $userId = auth()->guard('user')->id();
            $settings = EmailSettings::where('user_id', $userId)->first();

            if (!$settings) {
                return response()->json([
                    'success' => false,
                    'message' => 'No settings found. Please save your settings first.',
                    'results' => $results,
                ], 400);
            }

            // Test SendGrid
            if ($settings->sendgrid_api_key && $settings->from_email) {
                try {
                    $sendgridTest = $this->testSendgrid(new Request([
                        'sendgrid_api_key' => $settings->sendgrid_api_key,
                        'from_email' => $settings->from_email,
                        'from_name' => $settings->from_name,
                    ]));
                    
                    $results['sendgrid'] = $sendgridTest->getData()->success ?? false;
                } catch (\Exception $e) {
                    Log::error('SendGrid test failed in testAll: ' . $e->getMessage());
                }
            }

            // Test Gmail (if configured)
            if ($settings->gmail_address && $settings->gmail_app_password) {
                try {
                    $gmailTest = $this->testGmail(new Request([
                        'gmail_address' => $settings->gmail_address,
                        'gmail_app_password' => $settings->gmail_app_password,
                    ]));
                    
                    $results['gmail'] = $gmailTest->getData()->success ?? false;
                } catch (\Exception $e) {
                    Log::error('Gmail test failed in testAll: ' . $e->getMessage());
                }
            } else {
                $results['gmail'] = null; // Not configured
            }

            // Test Webhook (if enabled)
            if ($settings->webhook_enabled && !empty($settings->webhook_events)) {
                try {
                    $webhookTest = $this->testWebhook(new Request([
                        'webhook_signing_key' => $settings->webhook_signing_key,
                        'webhook_events' => $settings->webhook_events,
                    ]));
                    
                    $results['webhook'] = $webhookTest->getData()->success ?? false;
                } catch (\Exception $e) {
                    Log::error('Webhook test failed in testAll: ' . $e->getMessage());
                }
            } else {
                $results['webhook'] = null; // Not enabled
            }

            // Overall success
            $results['overall'] = $results['sendgrid'] && 
                                 ($results['gmail'] !== false) && 
                                 ($results['webhook'] !== false);

            return response()->json([
                'success' => $results['overall'],
                'message' => $results['overall'] ? 'All tests passed!' : 'Some tests failed. Check results below.',
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            Log::error('Test All Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}