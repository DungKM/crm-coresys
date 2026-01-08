<?php

namespace Webkul\EmailExtended\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Event, Mail, DB, Log};
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\EmailExtended\Repositories\{EmailThreadRepository, EmailScheduledRepository};
use Webkul\EmailTemplate\Repositories\EmailTemplateRepository;
use Sengrid; 
use SendGrid\Mail\Mail as SendGridMail;

class EmailComposerController extends Controller
{
    public function __construct(
        protected EmailRepository $emailRepository,
        protected EmailThreadRepository $emailThreadRepository,
        protected EmailScheduledRepository $emailScheduledRepository,
        protected EmailTemplateRepository $emailTemplateRepository
    ) {}

    // Hiển thị fomr soạn email mới 
    public function create(Request $request)
    {
        $templates = $this->emailTemplateRepository
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
            
        return view('email_extended::compose', [
            'templates' => $templates,
            'to' => $request->get('to'),
            'leadId' => $request->get('lead_id'),
            'personId' => $request->get('person_id'),
        ]);
    }

    // Lưu email mới (gửi ngay/ lên lịch / lưu nháp)
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $this->validate($request, [
            'to' => 'required|string',
            'subject' => 'required|string|max:500',
            'reply' => 'required|string',
            'action' => 'required|in:send,draft,schedule',
            'scheduled_at' => 'required_if:action,schedule|date_format:Y-m-d\TH:i|after:now',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'template_id' => 'nullable|exists:email_templates,id',
            'lead_id' => 'nullable|exists:leads,id',
            'person_id' => 'nullable|exists:persons,id',
        ]);

        // CHuẩn bị dữ iệu email 
        $data = $this->prepareEmailData($request);
        $actionConfig = $this->getActionConfig($request->action, $request->scheduled_at);
        $data = array_merge($data, $actionConfig);

        // dispatch event trước khi tạo 
        Event::dispatch('email.create.before', $data);
        // Tạo email trong db 
        $email = $this->emailRepository->create($data);
        if ($email->direction === 'outbound' && !$email->tracking_token) {
            DB::table('emails')->where('id', $email->id)->update([
                'tracking_token' => \Illuminate\Support\Str::random(32)
            ]);
            $email = $this->emailRepository->find($email->id); 
        }

        // Nếu là schedule, cập nhật status thành 'scheduled'
        if ($request->action === 'schedule') {
            $this->forceUpdateEmailStatus($email->id, 'scheduled', $request->scheduled_at);
            $email = $this->emailRepository->find($email->id);
        }

        // Xử lý file đính kèm 
        if ($request->hasFile('attachments')) {
            $this->handleAttachments($email, $request->file('attachments'));
        }

        try {
            // Thực hiện action (send/draft/schedule)
            $redirectUrl = $this->executeEmailAction($email, $request->action);
            Event::dispatch('email.create.after', $email);
            
            session()->flash('success', $this->getSuccessMessage($request->action));
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            session()->flash('error', trans('email_extended::app.composer.action-failed'));
            return redirect()->route('admin.mail.index');
        }
    }

    // Trả lời email 
    public function reply(Request $request, int $id)
    {
        // Tìm email gốc 
        $originalEmail = $this->emailRepository->findOrFail($id);
        
        // Kiểm tra quyền truy cập 
        if ($originalEmail->user_id && $originalEmail->user_id !== auth()->guard('user')->id()) {
            return $this->respondUnauthorized($request);
        }

        // Trích xuát địa chỉ người nhận 
        $toAddress = $this->extractRecipientEmail($originalEmail);
        
        if (!$toAddress) {
            return $this->respondInvalidRecipient($request);
        }

        // Nếu là GET request, redirect đến form compose với thông tin pre-filled
        if ($request->isMethod('get')) {
            return redirect()->route('admin.mail.compose', [
                'reply_to' => $id,
                'to' => $toAddress,
                'subject' => 'Re: ' . preg_replace('/^Re: /i', '', $originalEmail->subject),
            ]);
        }

        // Validate dữ liệu reply 
        $this->validate($request, [
            'reply' => 'required|string|min:1',
            'action' => 'required|in:send,draft,schedule',
            'scheduled_at' => 'required_if:action,schedule|date_format:Y-m-d\TH:i|after:now',
        ]);

        try {
            // Kiểm tra email hợp lệ 
            if (!filter_var($toAddress, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Invalid email: ' . $toAddress);
            }

            // Chuẩn bị dữ liệu reply 
            $data = $this->prepareReplyData($originalEmail, $toAddress, $request);
            $email = $this->emailRepository->create($data);
            if ($email->direction === 'outbound' && !$email->tracking_token) {
                DB::table('emails')->where('id', $email->id)->update([
                    'tracking_token' => \Illuminate\Support\Str::random(32)
                ]);
                $email = $this->emailRepository->find($email->id); 
            }

            // Cập nhật status nếu là schedule
            if ($request->action === 'schedule') {
                $this->forceUpdateEmailStatus($email->id, 'scheduled', $request->scheduled_at);
            }

            // Đảm bảo có user_id
            if (empty($email->user_id)) {
                DB::table('emails')->where('id', $email->id)->update(['user_id' => auth()->guard('user')->id()]);
            }

            // Reload email và thực hiện action
            $email = $this->emailRepository->find($email->id);
            $message = $this->executeReplyAction($email, $request->action);

            // Trả về JSON nếu là AJAX request
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            session()->flash('success', $message);
            return redirect($originalEmail->thread_id 
                ? route('admin.mail.show', $originalEmail->thread_id)
                : route('admin.mail.index'));

        } catch (\Exception $e) {
            return $this->respondError($request, $e->getMessage());
        }
    }

    // Chuyển tiếp email 
    public function forward(Request $request, int $id)
    {
        $originalEmail = $this->emailRepository->findOrFail($id);

        // Nếu là GET request, redirect đến form compose
        if ($request->isMethod('get')) {
            return redirect()->route('admin.mail.compose', [
                'forward_from' => $id,
                'subject' => 'Fwd: ' . $originalEmail->subject,
            ]);
        }

        // Validate dữ liệu forward
        $this->validate($request, [
            'to' => 'required|string',
            'reply' => 'nullable|string',
            'action' => 'required|in:send,draft,schedule',
            'scheduled_at' => 'required_if:action,schedule|date_format:Y-m-d\TH:i|after:now',
        ]);

        try {
            // Chuẩn bị dữ liệu forward (bao gồm nội dung email gốc)
            $data = $this->prepareForwardData($originalEmail, $request);
            $email = $this->emailRepository->create($data);
            if ($email->direction === 'outbound' && !$email->tracking_token) {
                DB::table('emails')->where('id', $email->id)->update([
                    'tracking_token' => \Illuminate\Support\Str::random(32)
                ]);
                $email = $this->emailRepository->find($email->id); 
            }

            // Cập nhật status nếu là schedule
            if ($request->action === 'schedule') {
                $this->forceUpdateEmailStatus($email->id, 'scheduled', $request->scheduled_at);
                $email = $this->emailRepository->find($email->id);
            }

            $message = $this->executeForwardAction($email, $request->action);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $message, 'email_id' => $email->id]);
            }

            session()->flash('success', $message);
            return redirect()->route('admin.mail.index');

        } catch (\Exception $e) {
            return $this->respondError($request, 'Failed to forward: ' . $e->getMessage());
        }
    }

    // Lưu email dưới dạng nháp (draft)
    public function saveDraft(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|string|max:500',
            'reply' => 'required|string',
        ]);

        // Chuẩn bị dữ liệu draft
        $data = $this->prepareEmailData($request);
        $data['status'] = 'draft';
        $data['folders'] = ['draft'];
        $data['direction'] = 'outbound';

        $email = $request->draft_id 
            ? $this->emailRepository->update($data, $request->draft_id)
            : $this->emailRepository->create($data);

        return response()->json([
            'message' => trans('email_extended::app.composer.draft-saved'),
            'draft_id' => $email->id,
            'success' => true,
        ]);
    }

    // Hiển thị form chỉnh sửa email nháp
    public function editDraft(int $id)
    {
        $email = $this->emailRepository->findOrFail($id);
        
        // Kiểm tra email phải là draft
        if ($email->status !== 'draft') {
            abort(404, 'Email is not a draft');
        }
        
        // Kiểm tra quyền sở hữu
        if ($email->user_id !== auth()->guard('user')->id()) {
            abort(403, 'Unauthorized');
        }

        $templates = $this->emailTemplateRepository->where('is_active', 1)->orderBy('name')->get();
        return view('email_extended::compose', compact('email', 'templates'));
    }

    // Cập nhật email draft (sau khi chỉnh sửa)
    public function update(Request $request, int $id)
    {
        $email = $this->emailRepository->findOrFail($id);

        // Kiểm tra quyền sở hữu
        if ($email->user_id !== auth()->guard('user')->id()) {
            abort(403, 'Unauthorized');
        }

        // Validate dữ liệu
        $this->validate($request, [
            'to' => 'required|string',
            'subject' => 'required|string|max:500',
            'reply' => 'required|string',
            'action' => 'required|in:send,draft,schedule',
            'scheduled_at' => 'required_if:action,schedule|date_format:Y-m-d\TH:i|after:now',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
        ]);

        // Chuẩn bị dữ liệu update
        $data = $this->prepareEmailData($request);
        $actionConfig = $this->getActionConfig($request->action, $request->scheduled_at);
        $data = array_merge($data, $actionConfig);

        // Dispatch event và update
        Event::dispatch('email.update.before', [$email, $data]);
        $email = $this->emailRepository->update($data, $id);

        // Cập nhật status nếu là schedule
        if ($request->action === 'schedule') {
            $this->forceUpdateEmailStatus($email->id, 'scheduled', $request->scheduled_at);
            $email = $this->emailRepository->find($email->id);
        }

        // Xử lý attachments
        if ($request->hasFile('attachments')) {
            $this->handleAttachments($email, $request->file('attachments'));
        }

        try {
            // Thực hiện action
            $redirectUrl = $this->executeEmailAction($email, $request->action);
            Event::dispatch('email.create.after', $email);

            $message = $this->getSuccessMessage($request->action);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message, 'email_id' => $email->id, 'redirect' => $redirectUrl]);
            }

            session()->flash('success', $message);
            return redirect($redirectUrl);

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => trans('email_extended::app.composer.action-failed')], 500);
            }

            session()->flash('error', trans('email_extended::app.composer.action-failed'));
            return redirect()->route('admin.mail.index');
        }
    }

    // Lấy nội dung template email theo ID
    public function fromTemplate(int $templateId)
    {
        $template = $this->emailTemplateRepository->findOrFail($templateId);
        $content = $template->content;
        
        // Nếu là Pro mode, extract nội dung email thực tế (bỏ UI builder)
        if ($template->editor_mode === 'pro' && !empty($content)) {
            $content = $this->extractEmailContent($content);
        }
        
        // Tăng số lần sử dụng
        $template->increment('usage_count');

        return response()->json([
            'subject' => $template->subject,
            'content' => $content, 
            'template_id' => $template->id,
            'variables' => $template->variables ?? [],
        ]);
    }

    // Preview template với dữ liệu động
    public function previewTemplate(Request $request)
    {
        $this->validate($request, [
            'template_id' => 'required|exists:email_templates,id',
            'data' => 'required|array',
        ]);

        $template = $this->emailTemplateRepository->find($request->template_id);

        return response()->json([
            'subject' => $this->renderTemplate($template->subject, $request->data),
            'content' => $this->renderTemplate($template->content, $request->data),
        ]);
    }

    // Đính kèm file vào email
    public function attach(Request $request)
    {
        return app(\Webkul\Admin\Http\Controllers\Mail\EmailController::class)->attach($request);
    }

    // Tải xuống file đính kèm
    public function download(?int $id = null)
    {
        return app(\Webkul\Admin\Http\Controllers\Mail\EmailController::class)->download($id);
    }

    // Hủy email đã lên lịch, chuyển về draft
    public function cancelSchedule(int $id)
    {
        try {
            $email = $this->emailRepository->findOrFail($id);

            // Kiểm tra quyền sở hữu
            if ($email->user_id !== auth()->guard('user')->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Kiểm tra status phải là scheduled
            if ($email->status !== 'scheduled') {
                return response()->json(['success' => false, 'message' => 'Email is not scheduled'], 400);
            }

            // Chuyển về draft
            DB::table('emails')->where('id', $id)->update([
                'status' => 'draft',
                'folders' => json_encode(['draft']),
                'scheduled_at' => null,
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Scheduled email cancelled and moved to drafts']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to cancel: ' . $e->getMessage()], 500);
        }
    }

    // Đổi thời gian lên lịch của email
    public function rescheduleEmail(Request $request, int $id)
    {
        try {
            $this->validate($request, ['scheduled_at' => 'required|date_format:Y-m-d H:i|after:now']);

            $email = $this->emailRepository->findOrFail($id);

            // Kiểm tra quyền sở hữu
            if ($email->user_id !== auth()->guard('user')->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Kiểm tra status phải là scheduled
            if ($email->status !== 'scheduled') {
                return response()->json(['success' => false, 'message' => 'Email is not scheduled'], 400);
            }

            // Update thời gian mới
            $newScheduledAt = \Carbon\Carbon::parse($request->scheduled_at);
            DB::table('emails')->where('id', $id)->update(['scheduled_at' => $newScheduledAt, 'updated_at' => now()]);

            return response()->json(['success' => true, 'message' => 'Email rescheduled to ' . $newScheduledAt->format('d M Y, H:i')]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Invalid date format. Use: YYYY-MM-DD HH:MM'], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reschedule: ' . $e->getMessage()], 500);
        }
    }

    // PROTECTED METHODS 

    // Chuẩn bị dữ liệu email từ request
    protected function prepareEmailData(Request $request): array
    {
        $userId = auth()->guard('user')->id();
        if (!$userId) {
            throw new \Exception('User not authenticated. Cannot create email.');
        }

        $currentUser = auth()->guard('user')->user();
        $userEmail = $currentUser->email ?? config('mail.from.address');

        $content = $request->reply;

        // Render template nếu có
        if ($request->template_id) {
            $template = $this->emailTemplateRepository->find($request->template_id);
            if ($template) {
                $templateContent = $template->content;
                if ($template->editor_mode === 'pro' && !empty($templateContent)) {
                    $templateContent = $this->extractEmailContent($templateContent);
                }
                
                $content = $this->renderTemplate($templateContent, $request->template_data ?? []);
                $template->increment('usage_count');
            }
        }

        $data = [
            'from' => config('mail.from.address'),
            'reply_to' => [$userEmail],
            'to' => $this->parseEmailAddresses($request->to),
            'cc' => $request->cc ? $this->parseEmailAddresses($request->cc) : null,
            'bcc' => $request->bcc ? $this->parseEmailAddresses($request->bcc) : null,
            'subject' => $request->subject,
            'reply' => $content,
            'rendered_content' => $content,
            'direction' => 'outbound',
            'user_id' => $userId,
            'user_type' => 'admin',
            'template_id' => $request->template_id,
            'source' => 'web',
            'tracking_token' => \Illuminate\Support\Str::random(32), 
        ];

        // Thêm lead_id và person_id nếu có
        foreach (['lead_id', 'person_id'] as $field) {
            $value = $request->$field;
            if (!empty($value) && $value !== 'null') {
                $data[$field] = $value;
            }
        }

        return $data;
    }

    // Chuẩn bị dữ liệu reply email
    protected function prepareReplyData($originalEmail, string $toAddress, Request $request): array
    {
        $actionConfig = $this->getActionConfig($request->action, $request->scheduled_at);

        return array_merge([
            'from' => config('mail.from.address'),
            'to' => [['email' => $toAddress]],
            'reply_to' => [auth()->guard('user')->user()->email],
            'subject' => 'Re: ' . preg_replace('/^Re: /i', '', $originalEmail->subject),
            'reply' => $request->reply,
            'rendered_content' => $request->reply,
            'direction' => 'outbound',
            'user_id' => auth()->guard('user')->id(),
            'user_type' => 'admin',
            'thread_id' => $originalEmail->thread_id,
            'reply_to_email_id' => $originalEmail->id,
            'in_reply_to' => $originalEmail->message_id,
            'message_id' => \Webkul\EmailExtended\Models\Email::generateMessageId(),
            'lead_id' => $originalEmail->lead_id,
            'person_id' => $originalEmail->person_id,
            'source' => 'web',
        ], $actionConfig);
    }

    // Chuẩn bị dữ liệu forward email (bao gồm nội dung email gốc)
    protected function prepareForwardData($originalEmail, Request $request): array
    {
        // Tạo nội dung forward với header "Forwarded message"
        $forwardContent = ($request->reply ? $request->reply . "\n\n" : '') .
            "---------- Forwarded message ----------\n" .
            "From: " . (is_array($originalEmail->from) ? ($originalEmail->from['email'] ?? $originalEmail->from) : $originalEmail->from) . "\n" .
            "Date: " . $originalEmail->created_at->format('D, M d, Y \a\t h:i A') . "\n" .
            "Subject: " . $originalEmail->subject . "\n\n" .
            $originalEmail->reply;

        $currentUser = auth()->guard('user')->user();
        $actionConfig = $this->getActionConfig($request->action, $request->scheduled_at);

        return array_merge([
            'from' => config('mail.from.address'),
            'reply_to' => [$currentUser->email ?? config('mail.from.address')],
            'to' => $this->parseEmailAddresses($request->to),
            'subject' => 'Fwd: ' . $originalEmail->subject,
            'reply' => $forwardContent,
            'rendered_content' => $forwardContent,
            'direction' => 'outbound',
            'user_id' => auth()->guard('user')->id(),
            'user_type' => 'admin',
            'forward_from_email_id' => $originalEmail->id,
            'message_id' => \Webkul\EmailExtended\Models\Email::generateMessageId(),
            'source' => 'web',
        ], $actionConfig);
    }

    // Lấy config theo action (send/draft/schedule)
    protected function getActionConfig(string $action, $scheduledAt = null): array
    {
        return match($action) {
            'send' => ['status' => 'queued', 'folders' => ['outbox']],
            'schedule' => ['status' => 'scheduled', 'folders' => ['scheduled'], 'scheduled_at' => $scheduledAt],
            'draft' => ['status' => 'draft', 'folders' => ['draft']],
            default => [],
        };
    }

    // Thực hiện action email (send/draft/schedule)
    protected function executeEmailAction($email, string $action): string
    {
        switch ($action) {
            case 'send':
                // Gửi email ngay
                $this->sendEmail($email);
                $email->refresh();
                return $email->thread_id 
                    ? route('admin.mail.show', $email->thread_id)
                    : route('admin.mail.index');
                    
            case 'schedule':
                // Tạo thread cho email lên lịch
                $this->createThreadForEmail($email);
                $email->refresh();
                return $email->thread_id
                    ? route('admin.mail.show', $email->thread_id)
                    : route('admin.mail.index', ['folder' => 'scheduled']);
                    
            case 'draft':
                // Tạo thread cho draft
                $this->createThreadForEmail($email);
                return route('admin.mail.index', ['folder' => 'draft']);
                
            default:
                return route('admin.mail.index');
        }
    }

    // Thực hiện action reply (send/draft/schedule)
    protected function executeReplyAction($email, string $action): string
    {
        return match($action) {
            'send' => [$this->sendEmail($email), 'Reply sent successfully'][1],
            'schedule' => [$this->createThreadForEmail($email), 'Reply scheduled'][1],
            'draft' => [$this->createThreadForEmail($email), 'Draft saved'][1],
            default => 'Action completed',
        };
    }

    // Thực hiện action forward (send/draft/schedule)
    protected function executeForwardAction($email, string $action): string
    {
        return match($action) {
            'send' => [$this->sendEmail($email), 'Email forwarded successfully'][1],
            'schedule' => [$this->createThreadForEmail($email), 'Forward scheduled successfully'][1],
            'draft' => [$this->createThreadForEmail($email), 'Forward saved as draft'][1],
            default => 'Action completed',
        };
    }

    // Lấy message thành công theo action
    protected function getSuccessMessage(string $action): string
    {
        return match($action) {
            'send' => trans('email_extended::app.composer.sent-successfully'),
            'schedule' => trans('email_extended::app.composer.scheduled-successfully'),
            'draft' => trans('email_extended::app.composer.saved-as-draft'),
            default => 'Action completed',
        };
    }

    public function sendEmail($email): void
    {
        // Kiểm tra user có settings không
        $settings = \Webkul\EmailExtended\Models\EmailSettings::where('user_id', $email->user_id)
            ->where('is_active', true)
            ->first();

        // Nếu có settings và SendGrid đã verified, dùng SendGrid
        if ($settings && $settings->sendgrid_verified && $settings->sendgrid_api_key) {
            $this->sendViaSendGrid($email, $settings);
        } else {
            // Fallback: Dùng mail mặc định của Laravel
            $this->sendViaDefaultMail($email);
        }
    }
    
    /**
     * Gửi email qua SendGrid với tracking
     */
    protected function sendViaSendGrid($email, $settings): void
    {
        try {
            // Tạo SendGrid Mail object
            $mail = new \SendGrid\Mail\Mail();
            
            // From
            $mail->setFrom(
                $settings->from_email,
                $settings->from_name
            );
            
            // To
            $recipients = $this->extractEmailsFromField($email->to);
            if (empty($recipients)) {
                throw new \Exception('No valid recipient email addresses');
            }
            foreach ($recipients as $recipient) {
                $mail->addTo($recipient);
            }
            
            // CC
            if ($email->cc) {
                $ccList = $this->extractEmailsFromField($email->cc);
                foreach ($ccList as $cc) {
                    $mail->addCc($cc);
                }
            }
            
            // BCC
            if ($email->bcc) {
                $bccList = $this->extractEmailsFromField($email->bcc);
                foreach ($bccList as $bcc) {
                    $mail->addBcc($bcc);
                }
            }
            
            // Reply-To
            $replyTo = $this->determineReplyTo($email);
            if ($replyTo) {
                $mail->setReplyTo($replyTo);
            }
            
            // Subject
            $mail->setSubject($email->subject);
            
            // Content với tracking
            $content = $this->applyTracking($email->reply, $email);
            $mail->addContent("text/html", $content);
            $mail->addCustomArg("email_id", (string)$email->id);
            $mail->addCustomArg("user_id", (string)$email->user_id);
            
            // Thêm thread_id nếu có
            if ($email->thread_id) {
                $mail->addCustomArg("thread_id", (string)$email->thread_id);
            }
            
            // Message ID
            $messageId = \Webkul\EmailExtended\Models\Email::generateMessageId();
            $mail->addHeader('Message-ID', $messageId);
            
            // References và In-Reply-To cho threading
            if ($email->thread_id || $email->reply_to_email_id) {
                $parentMessageId = $this->findParentMessageId($email);
                if ($parentMessageId) {
                    $cleanParentId = trim($parentMessageId, '<>');
                    $mail->addHeader('In-Reply-To', '<' . $cleanParentId . '>');
                    
                    if ($email->thread_id) {
                        $threadMessageIds = DB::table('emails')
                            ->where('thread_id', $email->thread_id)
                            ->whereNotNull('message_id')
                            ->where('id', '!=', $email->id)
                            ->orderBy('id', 'asc')
                            ->pluck('message_id')
                            ->map(fn($id) => '<' . trim($id, '<>') . '>')
                            ->toArray();
                        
                        if (!empty($threadMessageIds)) {
                            $mail->addHeader('References', implode(' ', $threadMessageIds));
                        }
                    }
                }
            }
            
            // Attachments
            if ($email->attachments && method_exists($email, 'attachments')) {
                foreach ($email->attachments as $attachment) {
                    $filePath = storage_path('app/' . $attachment->path);
                    if (file_exists($filePath)) {
                        $fileContent = base64_encode(file_get_contents($filePath));
                        $mail->addAttachment(
                            $fileContent,
                            $attachment->content_type ?? 'application/octet-stream',
                            $attachment->name
                        );
                    }
                }
            }
            
            // Gửi qua SendGrid
            $sendgrid = new \SendGrid($settings->sendgrid_api_key);
            $response = $sendgrid->send($mail);
            // Update email
            DB::table('emails')->where('id', $email->id)->update([
                'status' => 'sent',
                'sent_at' => now(),
                'folders' => json_encode(['sent']),
                'message_id' => $messageId,
                'rendered_content' => $content,
                'updated_at' => now(),
            ]);
            
            // Update settings count
            $settings->increment('emails_sent_count');
            $settings->update(['last_email_sent_at' => now()]);
            
            // Tạo thread nếu chưa có
            if (!$email->thread_id) {
                $email->refresh();
                $this->createThreadForEmail($email);
            }
            
        } catch (\Exception $e) {
            // Update status failed
            DB::table('emails')->where('id', $email->id)->update([
                'status' => 'failed',
                'folders' => json_encode(['failed']),
                'updated_at' => now(),
            ]);
            
            throw $e;
        }
    }

    /**
    * Gửi email qua Laravel Mail mặc định (fallback)
    */
    protected function sendViaDefaultMail($email): void
    {
        $messageId = \Webkul\EmailExtended\Models\Email::generateMessageId();
        $content = $this->applyTracking($email->reply, $email);
        
        DB::table('emails')->where('id', $email->id)->update([
            'rendered_content' => $content,
            'message_id' => $messageId
        ]);

        try {
            Mail::send([], [], function ($message) use ($email, $content, $messageId) {
                $this->configureMailMessage($message, $email, $content, $messageId);
            });
            $email->status = 'sent';
            $email->sent_at = now();
            $email->folders = ['sent'];
            $email->save();

            if (!$email->thread_id) {
                $this->createThreadForEmail($email);
                $email->refresh();
                
                if (!$email->thread_id) {
                    sleep(1);
                    $this->createThreadForEmail($email);
                    $email->refresh();
                }
            }

        } catch (\Exception $e) {
            $email->status = 'failed';
            $email->folders = ['failed'];
            $email->save();
            throw $e;
        }
    }

    protected function configureMailMessage($message, $email, string $content, string $messageId): void
    {
        $verifiedEmail = config('mail.from.address');
        $message->from($verifiedEmail, config('mail.from.name', 'System'));

        $replyToEmail = config('imap.accounts.default.username');
        $replyToName = config('mail.from.name', 'Support Team');
        
        if (!$replyToEmail || !filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
            $replyToEmail = config('mail.from.address');
        }
        
        $message->replyTo($replyToEmail, $replyToName);

        $to = $this->extractEmailsFromField($email->to);
        if (empty($to)) {
            throw new \Exception('No valid recipient email addresses');
        }
        $message->to($to);

        if ($email->cc) {
            $cc = $this->extractEmailsFromField($email->cc);
            if (!empty($cc)) {
                $message->cc($cc);
            }
        }

        if ($email->bcc) {
            $bcc = $this->extractEmailsFromField($email->bcc);
            if (!empty($bcc)) {
                $message->bcc($bcc);
            }
        }

        $message->getHeaders()->addIdHeader('Message-ID', $messageId);
        $message->getHeaders()->addTextHeader('X-SMTPAPI', json_encode([
            'unique_args' => [
                'email_id' => (string)$email->id,
                'thread_id' => (string)($email->thread_id ?? ''),
            ]
        ]));

        if ($email->thread_id || $email->reply_to_email_id) {
            $parentMessageId = $this->findParentMessageId($email);

            if ($parentMessageId) {
                $cleanParentId = trim($parentMessageId, '<>');
                $message->getHeaders()->addIdHeader('In-Reply-To', $cleanParentId);
                
                if ($email->thread_id) {
                    $this->addThreadReferences($message, $email, $cleanParentId);
                } else {
                    $message->getHeaders()->addTextHeader('References', '<' . $cleanParentId . '>');
                }
            }
        }

        $message->subject($email->subject);
        $message->html($content);

        if ($email->attachments && method_exists($email, 'attachments')) {
            foreach ($email->attachments as $attachment) {
                $this->attachFile($message, $attachment);
            }
        }
    }

    protected function findParentMessageId($email): ?string
    {
        if ($email->reply_to_email_id) {
            $parentEmail = DB::table('emails')
                ->where('id', $email->reply_to_email_id)
                ->first(['message_id']);
            
            if ($parentEmail && $parentEmail->message_id) {
                return $parentEmail->message_id;
            }
        }
        
        if ($email->thread_id) {
            $parentEmail = DB::table('emails')
                ->where('thread_id', $email->thread_id)
                ->whereNotNull('message_id')
                ->where('id', '!=', $email->id)
                ->orderBy('id', 'desc')
                ->first(['message_id']);
            
            if ($parentEmail && $parentEmail->message_id) {
                return $parentEmail->message_id;
            }
        }

        return null;
    }

    protected function addThreadReferences($message, $email, string $cleanParentId): void
    {
        $threadMessageIds = DB::table('emails')
            ->where('thread_id', $email->thread_id)
            ->whereNotNull('message_id')
            ->where('id', '!=', $email->id)
            ->orderBy('id', 'asc')
            ->pluck('message_id')
            ->map(fn($id) => '<' . trim($id, '<>') . '>')
            ->toArray();
        
        if (!empty($threadMessageIds)) {
            $message->getHeaders()->addTextHeader('References', implode(' ', $threadMessageIds));
        } else {
            $message->getHeaders()->addTextHeader('References', '<' . $cleanParentId . '>');
        }
    }

    protected function attachFile($message, $attachment): void
    {
        $filePath = storage_path('app/' . $attachment->path);
        
        if (file_exists($filePath)) {
            $message->attach($filePath, [
                'as' => $attachment->name,
                'mime' => $attachment->content_type ?? 'application/octet-stream'
            ]);
        }
    }

    /**
     * Tạo hoặc cập nhật thread cho email
     */
    protected function createThreadForEmail($email): void
    {
        try {
            DB::beginTransaction();
            
            $email->refresh();

            if ($email->thread_id) {
                $thread = $this->emailThreadRepository->find($email->thread_id);
                if ($thread) {
                    $thread->update(['last_email_at' => now(), 'is_read' => false]);
                    $thread->increment('email_count');
                    DB::commit();
                    return;
                }
            }

            if ($email->reply_to_email_id) {
                $originalEmail = $this->emailRepository->find($email->reply_to_email_id);

                if ($originalEmail && $originalEmail->thread_id) {
                    $thread = $this->emailThreadRepository->find($originalEmail->thread_id);

                    if ($thread) {
                        DB::table('emails')->where('id', $email->id)->update(['thread_id' => $thread->id]);
                        $thread->update(['last_email_at' => now(), 'is_read' => false]);
                        $thread->increment('email_count');
                        DB::commit();
                        return;
                    }
                }

                if ($originalEmail) {
                    $thread = $this->emailThreadRepository->create([
                        'subject' => $originalEmail->subject,
                        'folder' => $this->determineThreadFolder($originalEmail),
                        'user_id' => $originalEmail->user_id ?? $email->user_id,
                        'last_email_at' => now(),
                        'email_count' => 2,
                        'is_read' => false,
                    ]);

                    DB::table('emails')->whereIn('id', [$originalEmail->id, $email->id])->update(['thread_id' => $thread->id]);
                    DB::commit();
                    return;
                }
            }

            if (!$email->user_id) {
                DB::rollBack();
                return;
            }

            $thread = $this->emailThreadRepository->create([
                'subject' => $email->subject,
                'folder' => $this->determineThreadFolder($email),
                'user_id' => $email->user_id,
                'last_email_at' => $email->created_at ?? now(),
                'email_count' => 1,
                'is_read' => $email->is_read ?? false,
            ]);

            DB::table('emails')->where('id', $email->id)->update(['thread_id' => $thread->id]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    protected function handleAttachments($email, $files): void
    {
        foreach ($files as $file) {
            if (!$file->isValid()) continue;

            try {
                $path = $file->store('email-attachments');

                $email->attachments()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'content_type' => $file->getMimeType(),
                ]);
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    protected function parseEmailAddresses($addresses): array
    {
        if (is_array($addresses)) return $addresses;
        if (empty(trim($addresses))) return [];

        $emails = array_map('trim', explode(',', $addresses));
        $validEmails = array_filter($emails, fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));

        return array_map(fn($email) => ['email' => $email], array_values($validEmails));
    }

    protected function extractEmailsFromField($data): array
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        $emails = [];
        if (is_array($data)) {
            foreach ($data as $item) {
                if (is_array($item) && isset($item['email'])) {
                    $emails[] = $item['email'];
                } elseif (is_string($item) && filter_var($item, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $item;
                }
            }
        }

        return $emails;
    }

    protected function extractRecipientEmail($originalEmail): ?string
    {
        return $originalEmail->direction === 'outbound'
            ? $this->extractEmailAddress($originalEmail->to)
            : $this->extractEmailAddress($originalEmail->from);
    }

    protected function extractEmailAddress($from): ?string
    {
        if (empty($from)) return null;

        if (is_string($from)) {
            $decoded = json_decode($from, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $from = $decoded;
            } else {
                return filter_var($from, FILTER_VALIDATE_EMAIL) ? $from : null;
            }
        }

        if (is_array($from)) {
            $checks = [
                fn() => $from['email'] ?? null,
                fn() => $from[0]['email'] ?? null,
                fn() => (is_string($from[0] ?? null) && filter_var($from[0], FILTER_VALIDATE_EMAIL)) ? $from[0] : null,
                fn() => (is_string($email = reset($from)) && filter_var($email, FILTER_VALIDATE_EMAIL)) ? $email : null,
                fn() => (is_array($email = reset($from)) && isset($email['email'])) ? $email['email'] : null,
            ];

            foreach ($checks as $check) {
                if ($result = $check()) return $result;
            }
        }

        return null;
    }

    protected function determineReplyTo($email): ?string
    {
        if (!empty($email->reply_to)) {
            return is_array($email->reply_to) ? ($email->reply_to[0] ?? null) : $email->reply_to;
        }

        if (!empty($email->user_id)) {
            try {
                $user = \Webkul\User\Models\User::find($email->user_id);
                if ($user && !empty($user->email)) {
                    return $user->email;
                }
            } catch (\Exception $e) {}
        }

        $fromData = $email->from;
        if (is_array($fromData)) {
            return $fromData['email'] ?? $fromData[0]['email'] ?? null;
        }

        return is_string($fromData) ? $fromData : null;
    }

    protected function applyTracking(string $content, $email): string
    {
        if (method_exists($email, 'injectTrackingPixel') && config('email_extended.tracking.track_opens', false)) {
            $content = $email->injectTrackingPixel($content);
        }

        if (method_exists($email, 'injectTrackingLinks') && config('email_extended.tracking.track_clicks', false)) {
            $content = $email->injectTrackingLinks($content);
        }

        return $content;
    }

    protected function renderTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    protected function determineThreadFolder($email): string
    {
        if (in_array($email->status, ['draft', 'scheduled', 'sent'])) {
            return $email->status === 'sent' ? 'sent' : $email->status;
        }

        if (!empty($email->folders)) {
            $folders = is_string($email->folders) ? json_decode($email->folders, true) : $email->folders;
            if (is_array($folders) && !empty($folders)) {
                return $folders[0];
            }
        }

        return $email->direction === 'inbound' ? 'inbox' : 'sent';
    }

    protected function forceUpdateEmailStatus($emailId, $status, $scheduledAt = null): bool
    {
        try {
            if ($scheduledAt) {
                if (is_string($scheduledAt) && strpos($scheduledAt, 'T') !== false) {
                    $scheduledAt = str_replace('T', ' ', $scheduledAt) . ':00';
                }
                DB::statement("UPDATE emails SET status = ?, scheduled_at = ?, updated_at = NOW() WHERE id = ?", [$status, $scheduledAt, $emailId]);
            } else {
                DB::statement("UPDATE emails SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $emailId]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function respondUnauthorized($request)
    {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        abort(403);
    }

    protected function respondInvalidRecipient($request)
    {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Cannot determine recipient'], 400);
        }
        session()->flash('error', 'Cannot determine recipient email address');
        return redirect()->back();
    }

    protected function respondError($request, string $message)
    {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $message], 500);
        }
        session()->flash('error', $message);
        return redirect()->back();
    }

    /**
     * Loại bỏ các phần UI builder, chỉ giữ lại email content
     */
    protected function extractEmailContent(string $html): string
    {
        if (empty($html)) {
            return $html;
        }

        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        if (preg_match('/<table[^>]*(?:role="presentation"|cellpadding="0")[^>]*>.*?<\/table>/is', $html, $matches)) {
            $content = $matches[0];
            if (stripos($content, '<!DOCTYPE') === false) {
                $content = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $content . '</body></html>';
            }
            
            return $content;
        }

        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $bodyContent = $matches[1];
            $bodyContent = preg_replace('/<div[^>]*class="[^"]*(?:gjs-|builder-|wrapper-|chrome-|toolbar|inspector)[^"]*"[^>]*>.*?<\/div>/isU', '', $bodyContent);
            $bodyContent = preg_replace('/<nav[^>]*>.*?<\/nav>/is', '', $bodyContent);
            
            $bodyContent = trim($bodyContent);
            
            if (!empty($bodyContent)) {
                return '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $bodyContent . '</body></html>';
            }
        }

        if (preg_match('/<div[^>]*id="[^"]*(?:main|content|email)[^"]*"[^>]*>(.*?)<\/div>/is', $html, $matches)) {
            $content = $matches[1];
            if (!empty(trim(strip_tags($content)))) {
                return '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $content . '</body></html>';
            }
        }

        if (preg_match_all('/<table[^>]*>(.*?)<\/table>/is', $html, $matches, PREG_SET_ORDER)) {
            $maxLength = 0;
            $bestTable = '';
            
            foreach ($matches as $match) {
                $fullTable = $match[0];
                
                // Skip các table có class builder
                if (preg_match('/class="[^"]*(?:gjs-|builder|chrome|toolbar)[^"]*"/', $fullTable)) {
                    continue;
                }
                
                $textContent = strip_tags($match[1]);
                $textLength = strlen(trim($textContent));
                
                if ($textLength > $maxLength && $textLength > 50) {
                    $maxLength = $textLength;
                    $bestTable = $fullTable;
                }
            }
            
            if (!empty($bestTable)) {
                return '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $bestTable . '</body></html>';
            }
        }

        if (stripos($html, '<!DOCTYPE') !== false || stripos($html, '<html') !== false) {
            $html = preg_replace('/<div[^>]*class="[^"]*(?:gjs-|builder-)[^"]*"[^>]*>.*?<\/div>/isU', '', $html);
            return $html;
        }

        return '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $html . '</body></html>';
    }
}