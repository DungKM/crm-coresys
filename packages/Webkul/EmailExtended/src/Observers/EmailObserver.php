<?php

namespace Webkul\EmailExtended\Observers;

use Webkul\EmailExtended\Models\Email;
use Webkul\EmailExtended\Repositories\EmailThreadRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EmailObserver
{
    public function __construct(
        protected EmailThreadRepository $emailThreadRepository
    ) {}

    /**
     * Handle the Email "updated" event.
     * Trigger khi email status thay đổi từ 'scheduled' → 'sent'
     */
    public function updated(Email $email)
    {
        // Kiểm tra nếu status vừa chuyển từ 'scheduled' sang 'sent'
        if ($email->isDirty('status') && 
            $email->getOriginal('status') === 'scheduled' && 
            $email->status === 'sent') {
            
            Log::info('=== EMAIL STATUS CHANGED: scheduled → sent ===', [
                'email_id' => $email->id,
                'thread_id' => $email->thread_id,
                'subject' => $email->subject,
            ]);
            
            if (!in_array('sent', $email->folders ?? [])) {
                DB::table('emails')
                    ->where('id', $email->id)
                    ->update(['folders' => json_encode(['sent'])]);
                
                Log::info('Email folder updated to sent', [
                    'email_id' => $email->id,
                ]);
            }

            if ($email->thread_id) {
                $this->updateThreadAfterSending($email);
            }
        }
    }

    /**
     * Handle the Email "created" event.
     */
    public function created(Email $email)
    {
        Log::info('Email created via Observer', [
            'email_id' => $email->id,
            'status' => $email->status,
            'folders' => $email->folders,
            'thread_id' => $email->thread_id,
        ]);
    }

    /**
     * Handle the Email "deleted" event.
     */
    public function deleted(Email $email)
    {
        // Nếu xóa email trong thread, giảm email_count
        if ($email->thread_id) {
            $thread = $this->emailThreadRepository->find($email->thread_id);
            if ($thread && $thread->email_count > 0) {
                $thread->decrement('email_count');
                
                Log::info('Thread email_count decremented', [
                    'thread_id' => $thread->id,
                    'new_count' => $thread->email_count,
                ]);

                // Nếu thread không còn email nào, xóa thread luôn
                if ($thread->email_count <= 0) {
                    $thread->delete();
                    Log::info('Thread deleted (no emails left)', ['thread_id' => $thread->id]);
                }
            }
        }
    }

    /**
     * Cập nhật thread sau khi email được gửi
     * CHỈ update metadata, KHÔNG thay đổi folder của thread
     */
    protected function updateThreadAfterSending(Email $email)
    {
        try {
            $thread = $this->emailThreadRepository->find($email->thread_id);
            
            if (!$thread) {
                Log::warning('Thread not found for email', [
                    'email_id' => $email->id,
                    'thread_id' => $email->thread_id,
                ]);
                return;
            }
            $thread->update([
                'last_email_at' => now(),
                'is_read' => false, // Đánh dấu chưa đọc vì có email mới
            ]);

            Log::info('Thread updated after sending scheduled email', [
                'thread_id' => $thread->id,
                'email_id' => $email->id,
                'thread_folder' => $thread->folder, 
                'last_email_at' => $thread->last_email_at,
                'email_count' => $thread->email_count,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update thread after sending', [
                'email_id' => $email->id,
                'thread_id' => $email->thread_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}