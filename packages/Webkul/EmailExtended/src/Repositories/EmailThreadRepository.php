<?php

namespace Webkul\EmailExtended\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\EmailExtended\Models\EmailThreadProxy;

class EmailThreadRepository extends Repository
{
    /**
     * Chỉ định model class cho repository
     * 
     * @return string
     */
    public function model(): string
    {
        return EmailThreadProxy::modelClass();
    }

    /**
     * Lấy tất cả các luồng email của người dùng trong một thư mục cụ thể
     * Hỗ trợ filter theo: is_read, is_starred, tag, search
     * 
     * @param int $userId - ID người dùng
     * @param string $folder - Thư mục (inbox, sent, draft, archive, trash)
     * @param array $filters - Các bộ lọc bổ sung
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getThreadsForUser(int $userId, string $folder = 'inbox', array $filters = [])
    {
        $query = $this->model
            ->where('user_id', $userId)
            ->where('folder', $folder)
            ->with(['latestEmail', 'lead', 'person'])
            ->orderBy('last_email_at', 'desc');
        
        // Filter theo trạng thái đã đọc
        if (!empty($filters['is_read'])) {
            $query->where('is_read', $filters['is_read'] === 'true');
        }
        
        // Filter theo starred
        if (!empty($filters['is_starred'])) {
            $query->where('is_starred', true);
        }
        
        // Filter theo tag
        if (!empty($filters['tag'])) {
            $query->withTag($filters['tag']);
        }
        
        // Filter theo từ khóa tìm kiếm
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('subject', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('emails', function ($eq) use ($filters) {
                      $eq->where('reply', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }
        
        return $query;
    }

    /**
     * Lấy số lượng tin nhắn chưa đọc của người dùng
     * 
     * @param int $userId - ID người dùng
     * @param string|null $folder - Thư mục cụ thể (null = tất cả)
     * @return int
     */
    public function getUnreadCount(int $userId, ?string $folder = null): int
    {
        $query = $this->model
            ->where('user_id', $userId)
            ->where('is_read', false);
        
        if ($folder) {
            $query->where('folder', $folder);
        }
        
        return $query->count();
    }

    /**
     * Tìm luồng email theo message ID
     * 
     * @param string $messageId - Message ID của email
     * @return \Webkul\EmailExtended\Models\EmailThread|null
     */
    public function findByMessageId(string $messageId)
    {
        return $this->model
            ->where('message_id', $messageId)
            ->first();
    }

    /**
     * Tạo thread mới từ email
     * Tự động xác định folder dựa trên direction (inbound/outbound)
     * 
     * @param \Webkul\EmailExtended\Models\Email $email
     * @return \Webkul\EmailExtended\Models\EmailThread
     */
    public function createFromEmail($email)
    {
        // Tạo thread mới
        $thread = $this->create([
            'subject' => $email->subject,
            'message_id' => $email->message_id ?? $this->generateMessageId(),
            'lead_id' => $email->lead_id,
            'person_id' => $email->person_id,
            'user_id' => $email->user_id ?? auth()->guard('user')->id(),
            'last_email_at' => now(),
            'email_count' => 1,
            'unread_count' => $email->direction === 'inbound' ? 1 : 0,
            'is_read' => $email->direction !== 'inbound',
            'folder' => $email->direction === 'inbound' ? 'inbox' : 'sent',
            'participants' => $this->extractParticipants($email),
        ]);
        
        // QUAN TRỌNG: Cập nhật thread_id vào email
        DB::table('emails')
            ->where('id', $email->id)
            ->update(['thread_id' => $thread->id]);
        
        return $thread;
    }

    /**
     * Cập nhật thống kê của thread (email_count, unread_count, last_email_at)
     * 
     * @param int $threadId
     * @return \Webkul\EmailExtended\Models\EmailThread|false
     */
    public function updateThreadStats(int $threadId)
    {
        $thread = $this->find($threadId);
        
        if (!$thread) {
            return false;
        }
        
        $emails = $thread->emails;
        
        $thread->update([
            'email_count' => $emails->count(),
            'unread_count' => $emails->where('is_read', false)->count(),
            'last_email_at' => $emails->first()?->created_at,
            'is_read' => $emails->where('is_read', false)->count() === 0,
        ]);
        
        return $thread;
    }

    /**
     * Đánh dấu thread và tất cả emails bên trong là đã đọc
     * 
     * @param int $threadId
     * @return bool
     */
    public function markAsRead(int $threadId): bool
    {
        $thread = $this->find($threadId);

        if (!$thread) {
            return false;
        }
        
        DB::beginTransaction();

        try {
            // Đánh dấu thread là đã đọc
            $thread->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);
            
            // Đánh dấu tất cả email trong thread là đã đọc
            $thread->emails()->update(['is_read' => true]);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Đánh dấu thread là chưa đọc
     * 
     * @param int $threadId
     * @return bool
     */
    public function markAsUnread(int $threadId): bool
    {
        $thread = $this->find($threadId);
        
        if (!$thread) {
            return false;
        }
        
        return $thread->update(['is_read' => false]);
    }

    /**
     * Toggle star cho thread (bật/tắt đánh dấu sao)
     * 
     * @param int $threadId
     * @return bool
     */
    public function toggleStar(int $threadId): bool
    {
        $thread = $this->find($threadId);
        
        if (!$thread) {
            return false;
        }
        
        return $thread->update([
            'is_starred' => !$thread->is_starred,
        ]);
    }

    /**
     * Di chuyển thread sang thư mục khác
     * 
     * @param int $threadId
     * @param string $folder - Tên thư mục đích
     * @return bool
     */
    public function moveToFolder(int $threadId, string $folder): bool
    {
        $thread = $this->find($threadId);
        
        if (!$thread) {
            return false;
        }
        
        return $thread->update(['folder' => $folder]);
    }

    /**
     * Di chuyển nhiều threads sang thư mục khác (bulk operation)
     * 
     * @param array $threadIds - Mảng ID threads
     * @param string $folder - Tên thư mục đích
     * @return bool
     */
    public function bulkMoveToFolder(array $threadIds, string $folder): bool
    {
        return $this->model
            ->whereIn('id', $threadIds)
            ->update(['folder' => $folder]);
    }

    /**
     * Đánh dấu nhiều threads là đã đọc (bulk operation)
     * 
     * @param array $threadIds - Mảng ID threads
     * @return bool
     */
    public function bulkMarkAsRead(array $threadIds): bool
    {
        return $this->model
            ->whereIn('id', $threadIds)
            ->update([
                'is_read' => true,
                'unread_count' => 0,
            ]);
    }

    /**
     * Đánh dấu nhiều threads là chưa đọc (bulk operation)
     * 
     * @param array $threadIds - Mảng ID threads
     * @return bool
     */
    public function bulkMarkAsUnread(array $threadIds): bool
    {
        return $this->model
            ->whereIn('id', $threadIds)
            ->update(['is_read' => false]);
    }

    /**
     * Thêm tag vào thread
     * 
     * @param int $threadId
     * @param string $tag - Tên tag
     * @return bool
     */
    public function addTag(int $threadId, string $tag): bool
    {
        $thread = $this->find($threadId);
        
        if (!$thread) {
            return false;
        }
        
        $tags = $thread->tags ?? [];
        
        // Chỉ thêm nếu tag chưa tồn tại
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            return $thread->update(['tags' => $tags]);
        }
        
        return false;
    }

    /**
     * Xóa tag khỏi thread
     * 
     * @param int $threadId
     * @param string $tag - Tên tag cần xóa
     * @return bool
     */
    public function removeTag(int $threadId, string $tag): bool
    {
        $thread = $this->find($threadId);
        
        if (!$thread) {
            return false;
        }
        
        $tags = $thread->tags ?? [];
        
        // Tìm và xóa tag
        if (($key = array_search($tag, $tags)) !== false) {
            unset($tags[$key]);
            return $thread->update(['tags' => array_values($tags)]);
        }
        
        return false;
    }

    /**
     * Lấy thống kê số lượng threads theo folder của người dùng
     * 
     * @param int $userId
     * @return array - Mảng chứa thống kê: total, inbox, sent, draft, archive, trash, unread, starred
     */
    public function getStatistics(int $userId): array
    {
        $threads = $this->model->where('user_id', $userId);
        
        return [
            'total' => $threads->count(),
            'inbox' => $threads->clone()->where('folder', 'inbox')->count(),
            'sent' => $threads->clone()->where('folder', 'sent')->count(),
            'draft' => $threads->clone()->where('folder', 'draft')->count(),
            'archive' => $threads->clone()->where('folder', 'archive')->count(),
            'trash' => $threads->clone()->where('folder', 'trash')->count(),
            'unread' => $threads->clone()->where('is_read', false)->count(),
            'starred' => $threads->clone()->where('is_starred', true)->count(),
        ];
    }

    /**
     * Tìm kiếm threads theo từ khóa
     * Tìm trong: subject, email content, person name
     * 
     * @param int $userId
     * @param string $query - Từ khóa tìm kiếm
     * @param array $options - Tùy chọn bổ sung (folder, ...)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search(int $userId, string $query, array $options = [])
    {
        $threads = $this->model
            ->where('user_id', $userId)
            ->where(function ($q) use ($query) {
                $q->where('subject', 'like', '%' . $query . '%')
                  ->orWhereHas('emails', function ($eq) use ($query) {
                      $eq->where('reply', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('person', function ($pq) use ($query) {
                      $pq->where('name', 'like', '%' . $query . '%')
                         ->orWhereJsonContains('emails', $query);
                  });
            });
        
        // Filter theo folder nếu có
        if (!empty($options['folder'])) {
            $threads->where('folder', $options['folder']);
        }
        
        return $threads->with(['latestEmail', 'lead', 'person'])
            ->orderBy('last_email_at', 'desc');
    }

    /**
     * Trích xuất danh sách email người tham gia từ email
     * Lấy từ: from, reply_to
     * 
     * @param \Webkul\EmailExtended\Models\Email $email
     * @return array - Mảng email addresses duy nhất
     */
    protected function extractParticipants($email): array
    {
        $participants = [];
        
        // Lấy từ FROM field
        if ($email->from) {
            $from = is_array($email->from) ? $email->from : json_decode($email->from, true);
            if (isset($from['email'])) {
                $participants[] = $from['email'];
            }
        }
        
        // Lấy từ REPLY_TO field
        if ($email->reply_to) {
            $replyTo = is_array($email->reply_to) ? $email->reply_to : json_decode($email->reply_to, true);
            if (isset($replyTo['email'])) {
                $participants[] = $replyTo['email'];
            }
        }
        
        // Loại bỏ duplicate và giá trị rỗng
        return array_unique(array_filter($participants));
    }

    /**
     * Tạo message ID duy nhất cho thread
     * Format: <uniqid.timestamp@domain>
     * 
     * @return string
     */
    protected function generateMessageId(): string
    {
        return sprintf(
            '<%s.%s@%s>',
            uniqid(),
            time(),
            parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost'
        );
    }
}