<?php

namespace Webkul\EmailExtended\Helpers;

use Webkul\EmailExtended\Models\EmailThreadProxy;
use Webkul\Email\Models\EmailProxy;

class EmailThreadHelper
{

    // Tìm hoặc tạo mới chuỗi email 
    public function findOrCreateThread($email)
    {
        // Kiểm tra các luồng hội thoại của email 
        if ($email->thread_id) {
            return EmailThreadProxy::modelClass()::find($email->thread_id);
        }
        // Tìm các luồng hiện có 
        if ($email->in_reply_to) {
            $parentEmail = EmailProxy::modelClass()::where('message_id', $email->in_reply_to)->first();
            if ($parentEmail && $parentEmail->thread_id) {
                $email->update(['thread_id' => $parentEmail->thread_id]);
                $parentEmail->thread->incrementEmailCount();
                return $parentEmail->thread;
            }
        }
        // Tìm kiếm thread theo tiêu đề chính 
        if (config('email_extended.threads.group_by_subject', true)) {
            $similarThread = $this->findThreadBySubject($email->subject, $email->user_id);
            
            if ($similarThread) {
                $email->update(['thread_id' => $similarThread->id]);
                $similarThread->incrementEmailCount();
                return $similarThread;
            }
        }
        // tạo mới thread 
        return $this->createThread($email);
    }

    // Tạo chuỗi hôi thoại mới từ emai 
    public function createThread($email)
    {
        return EmailThreadProxy::modelClass()::create([
            'subject' => $this->normalizeSubject($email->subject),
            'message_id' => $email->message_id ?? $this->generateMessageId(),
            'lead_id' => $email->lead_id,
            'person_id' => $email->person_id,
            'user_id' => $email->user_id ?? auth()->guard('user')->id(),
            'last_email_at' => now(),
            'email_count' => 1,
            'unread_count' => $email->direction === 'inbound' ? 1 : 0,
            'is_read' => $email->direction !== 'inbound',
            'folder' => $this->determineFolderFromEmail($email),
            'participants' => $this->extractParticipants($email),
        ]);
    }

    // Tìm thread theo thread tương tự 
    public function findThreadBySubject(string $subject, int $userId)
    {
        $normalized = $this->normalizeSubject($subject);
        return EmailThreadProxy::modelClass()::where('user_id', $userId)
            ->where('subject', $normalized)
            ->where('created_at', '>=', now()->subDays(30)) // chỉ có hiệu lực trong 30 ngày 
            ->first();
    }

    // Chuẩn hóa tiêu đề email 
    public function normalizeSubject(string $subject): string
    {
        $subject = preg_replace('/^(Re|Fwd|FW|RE):\s*/i', '', $subject);
        $subject = preg_replace('/\s+/', ' ', $subject);
        return trim($subject);
    }

    // Trích xuát danh sách người tham gia từ email 
    public function extractParticipants($email): array
    {
        $participants = [];
        // from 
        if ($email->from) {
            $from = is_array($email->from) ? $email->from : json_decode($email->from, true);
            if (isset($from['email'])) {
                $participants[] = strtolower($from['email']);
            } elseif (is_string($from)) {
                $participants[] = strtolower($from);
            }
        }
        // To
        if ($email->to) {
            $to = is_array($email->to) ? $email->to : json_decode($email->to, true);
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    if (is_array($recipient) && isset($recipient['email'])) {
                        $participants[] = strtolower($recipient['email']);
                    } elseif (is_string($recipient)) {
                        $participants[] = strtolower($recipient);
                    }
                }
            }
        }
        // Reply-To
        if ($email->reply_to) {
            $replyTo = is_array($email->reply_to) ? $email->reply_to : json_decode($email->reply_to, true);
            if (isset($replyTo['email'])) {
                $participants[] = strtolower($replyTo['email']);
            }
        }
        // CC
        if ($email->cc) {
            $cc = is_array($email->cc) ? $email->cc : json_decode($email->cc, true);
            if (is_array($cc)) {
                foreach ($cc as $recipient) {
                    if (is_array($recipient) && isset($recipient['email'])) {
                        $participants[] = strtolower($recipient['email']);
                    }
                }
            }
        }
        return array_values(array_unique(array_filter($participants)));
    }

    // Xâc định thư mục dựa trên thuộc tính của email 
    public function determineFolderFromEmail($email): string
    {
        if ($email->status === 'draft') {
            return 'draft';
        }
        if ($email->direction === 'inbound') {
            return 'inbox';
        }
        if ($email->direction === 'outbound') {
            return 'sent';
        }
        return 'inbox';
    }

    // tạo id tin nhắn duy nhất 
    public function generateMessageId(): string
    {
        return sprintf(
            '<%s.%s@%s>',
            uniqid(),
            time(),
            parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost'
        );
    }

    // Kểm tra email có thuộc chuỗi hội nào hay không 
    public function belongsToThread($email, $thread): bool
    {
        // Check direct thread_id
        if ($email->thread_id === $thread->id) {
            return true;
        }
        // Check by in_reply_to
        if ($email->in_reply_to === $thread->message_id) {
            return true;
        }
        // Check by subject similarity
        if ($this->normalizeSubject($email->subject) === $thread->subject) {
            return true;
        }
        return false;
    }
    
    // Lấy thông tin tóm tắt chủ đề 
    public function getThreadSummary($thread): array
    {
        return [
            'id' => $thread->id,
            'subject' => $thread->subject,
            'email_count' => $thread->email_count,
            'unread_count' => $thread->unread_count,
            'participants' => $thread->participants,
            'last_email_at' => $thread->last_email_at,
            'is_read' => $thread->is_read,
            'is_starred' => $thread->is_starred,
            'snippet' => $thread->getSnippet(),
            'folder' => $thread->folder,
            'tags' => $thread->tags,
        ];
    }

    // Hợp nhất các luồng 
    public function mergeThreads($sourceThreadId, $targetThreadId): bool
    {
        $sourceThread = EmailThreadProxy::modelClass()::find($sourceThreadId);
        $targetThread = EmailThreadProxy::modelClass()::find($targetThreadId);
        if (!$sourceThread || !$targetThread) {
            return false;
        }
        // Chuyển tất cả email từ nguồn sang đích 
        EmailProxy::modelClass()::where('thread_id', $sourceThreadId)
            ->update(['thread_id' => $targetThreadId]);
        // Cập nhật số lượng thống kê của luồng mục tiêu 
        $targetThread->update([
            'email_count' => $targetThread->email_count + $sourceThread->email_count,
            'unread_count' => $targetThread->unread_count + $sourceThread->unread_count,
            'last_email_at' => max($targetThread->last_email_at, $sourceThread->last_email_at),
            'participants' => array_unique(array_merge(
                $targetThread->participants ?? [],
                $sourceThread->participants ?? []
            )),
        ]);
        // Xóa luồng nguồn 
        $sourceThread->delete();
        return true;
    }

    // Danh sách cá luồng riêng biệt 
    public function splitThread($emailId): bool
    {
        $email = EmailProxy::modelClass()::find($emailId);

        if (!$email || !$email->thread_id) {
            return false;
        }
        $oldThread = $email->thread;
        // Tạo mới một luồng chat cho email chỉ định 
        $newThread = $this->createThread($email);
        // Cạp nhật email sang luồng mới 
        $email->update(['thread_id' => $newThread->id]);
        // Cập nahatj lại số lượng thống kê của chủ đề cũ 
        $oldThread->update([
            'email_count' => $oldThread->emails()->count(),
            'unread_count' => $oldThread->emails()->where('is_read', false)->count(),
        ]);
        return true;
    }

    public function getFolderIcon(string $folder): string
    {
        return match($folder) {
            'inbox' => 'icon-inbox',
            'sent' => 'icon-send',
            'draft' => 'icon-draft',
            'scheduled' => 'icon-clock', 
            'archive' => 'icon-archive',
            'trash' => 'icon-trash',
            'spam' => 'icon-warning', 
            default => 'icon-mail',
        };
    }

    public function getFolderLabel(string $folder): string
    {
        return match($folder) {
            'inbox' => 'Inbox',
            'sent' => 'Sent',
            'draft' => 'Drafts',
            'scheduled' => 'Scheduled', 
            'archive' => 'Archive',
            'trash' => 'Trash',
            'spam' => 'Spam',
            default => ucfirst($folder),
        };
    }

    /**
     * Format time ago
     */
    public function timeAgo($datetime): string
    {
        if (!$datetime) {
            return '-';
        }
        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }
        return $datetime->diffForHumans();
    }

    /**
     * Get email preview text
     */
    public function getEmailPreview($email, int $length = 100): string
    {
        $content = strip_tags($email->reply ?? $email->rendered_content ?? '');
        $content = preg_replace('/\s+/', ' ', $content);
        return \Illuminate\Support\Str::limit(trim($content), $length);
    }

    /**
     * Format email addresses for display
     */
    public function formatEmailAddresses($addresses, int $limit = 2): string
    {
        // Nếu null hoặc empty, return '-'
        if (!$addresses || empty($addresses)) {
            return '-';
        }
        
        // Nếu là string, decode
        if (is_string($addresses)) {
            $decoded = json_decode($addresses, true);
            
            // Nếu decode thành công và là array
            if (is_array($decoded)) {
                $addresses = $decoded;
            } else {
                // Nếu decode failed, return original string nếu hợp lệ
                return !empty($addresses) ? $addresses : '-';
            }
        }
        
        // Nếu không phải array, convert to string
        if (!is_array($addresses)) {
            $str = (string) $addresses;
            return !empty($str) ? $str : '-';
        }
        
        // Extract emails
        $emails = [];
        foreach ($addresses as $address) {
            if (is_array($address)) {
                $email = $address['name'] ?? $address['email'] ?? '';
                if (!empty($email)) {
                    $emails[] = $email;
                }
            } elseif (!empty($address)) {
                $emails[] = (string) $address;
            }
        }
        
        // Filter empty values
        $emails = array_filter($emails);
        
        // Nếu không có email nào, return '-'
        if (empty($emails)) {
            return '-';
        }
        
        // Format output
        if (count($emails) <= $limit) {
            return implode(', ', $emails);
        }
        
        return implode(', ', array_slice($emails, 0, $limit)) . ' +' . (count($emails) - $limit) . ' more';
    }
}