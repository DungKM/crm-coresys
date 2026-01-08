<?php

namespace Webkul\EmailExtended\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\EmailExtended\Models\EmailScheduledProxy;

class EmailScheduledRepository extends Repository
{
    public function model(): string
    {
        return EmailScheduledProxy::modelClass();
    }

    // lên lịch sử email 
    public function schedule(int $emailId, $scheduledAt, array $options = [])
    {
        return $this->create([
            'email_id' => $emailId,
            'scheduled_at' => $scheduledAt,
            'status' => 'pending',
            'attempts' => 0,
            'max_attempts' => $options['max_attempts'] ?? 3,
            'metadata' => $options['metadata'] ?? [],
        ]);
    }

    // Lấy toàn bộ email đến hạn 
    public function getDueEmails()
    {
        return $this->model
            ->with('email')
            ->where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'asc')
            ->get();
    }

    //  Lấy tất cả các email đã lên lịch đang chờ xử lý
    public function getPending(array $filters = [])
    {
        $query = $this->model
            ->with('email')
            ->where('status', 'pending')
            ->orderBy('scheduled_at', 'asc');
        if (!empty($filters['user_id'])) {
            $query->whereHas('email', function ($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            });
        }
        return $query;
    }

    // Lấy lịch gửi email theo ID email
    public function findByEmailId(int $emailId)
    {
        return $this->model
            ->where('email_id', $emailId)
            ->first();
    }

    // Đánh dấu là đang xử lý
    public function markAsProcessing(int $id): bool
    {
        return $this->update([
            'status' => 'processing',
            'last_attempt_at' => now(),
        ], $id);
    }

    // Đánh dấu là đã gửi
    public function markAsSent(int $id): bool
    {
        return $this->update([
            'status' => 'sent',
            'last_attempt_at' => now(),
        ], $id);
    }

    // Đánh dấu là thất bại
    public function markAsFailed(int $id, string $error): bool
    {
        $scheduled = $this->find($id);
        if (!$scheduled) {
            return false;
        }
        $newAttempts = $scheduled->attempts + 1;
        $status = $newAttempts >= $scheduled->max_attempts ? 'failed' : 'pending';
        return $this->update([
            'status' => $status,
            'attempts' => $newAttempts,
            'error_message' => $error,
            'last_attempt_at' => now(),
        ], $id);
    }

    // Hủy gửi email theo lịch trình
    public function cancel(int $id): bool
    {
        return $this->update([
            'status' => 'cancelled',
        ], $id);
    }

    // Hủy hàng loạt email đã lên lịch
    public function bulkCancel(array $ids): bool
    {
        return $this->model
            ->whereIn('id', $ids)
            ->update(['status' => 'cancelled']);
    }

    /**
     * Reset for retry
     */
    public function resetForRetry(int $id): bool
    {
        return $this->update([
            'status' => 'pending',
            'attempts' => 0,
            'error_message' => null,
        ], $id);
    }

    // Lấy các email bị lỗi có thể gửi lại
    public function getRetryable()
    {
        return $this->model
            ->with('email')
            ->where('status', 'failed')
            ->whereColumn('attempts', '<', 'max_attempts')
            ->orderBy('last_attempt_at', 'asc')
            ->get();
    }

    // Lấy số liệu thống kê
    public function getStatistics(array $filters = []): array
    {
        $query = $this->model;
        if (!empty($filters['user_id'])) {
            $query = $query->whereHas('email', function ($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            });
        }
        return [
            'total' => $query->count(),
            'pending' => $query->clone()->where('status', 'pending')->count(),
            'processing' => $query->clone()->where('status', 'processing')->count(),
            'sent' => $query->clone()->where('status', 'sent')->count(),
            'failed' => $query->clone()->where('status', 'failed')->count(),
            'cancelled' => $query->clone()->where('status', 'cancelled')->count(),
            'due_now' => $query->clone()
                ->where('status', 'pending')
                ->where('scheduled_at', '<=', now())
                ->count(),
        ];
    }

    // Nhận email theo lịch trình dựa trên khoảng thời gian
    public function getByDateRange($startDate, $endDate, array $filters = [])
    {
        $query = $this->model
            ->with('email')
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->orderBy('scheduled_at', 'asc');
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['user_id'])) {
            $query->whereHas('email', function ($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            });
        }
        return $query;
    }

    /**
     * Reschedule email
     */
    public function reschedule(int $id, $newScheduledAt): bool
    {
        $scheduled = $this->find($id);

        if (!$scheduled || $scheduled->status === 'sent') {
            return false;
        }
        return $this->update([
            'scheduled_at' => $newScheduledAt,
            'status' => 'pending',
            'attempts' => 0,
            'error_message' => null,
        ], $id);
    }

    // Dọn dẹp các bản ghi đã lên lịch cũ
    public function cleanup(int $daysOld = 30): int
    {
        return $this->model
            ->where('status', 'sent')
            ->where('updated_at', '<=', now()->subDays($daysOld))
            ->delete();
    }

    // Nhận các email đã lên lịch sắp tới
    public function getUpcoming(int $limit = 10, array $filters = [])
    {
        $query = $this->model
            ->with('email')
            ->where('status', 'pending')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit($limit);

        if (!empty($filters['user_id'])) {
            $query->whereHas('email', function ($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            });
        }

        return $query->get();
    }

    // Nhận email đã lên lịch quá hạn
    public function getOverdue(array $filters = [])
    {
        $query = $this->model
            ->with('email')
            ->where('status', 'pending')
            ->where('scheduled_at', '<', now()->subHours(1))
            ->orderBy('scheduled_at', 'asc');
        if (!empty($filters['user_id'])) {
            $query->whereHas('email', function ($q) use ($filters) {
                $q->where('user_id', $filters['user_id']);
            });
        }
        return $query->get();
    }

    //  Nhận email theo lịch trình cho hôm nay
    public function getToday(array $filters = [])
    {
        return $this->getByDateRange(
            now()->startOfDay(),
            now()->endOfDay(),
            $filters
        )->get();
    }

    // Nhận lịch gửi email trong tuần này
    public function getThisWeek(array $filters = [])
    {
        return $this->getByDateRange(
            now()->startOfWeek(),
            now()->endOfWeek(),
            $filters
        )->get();
    }
}