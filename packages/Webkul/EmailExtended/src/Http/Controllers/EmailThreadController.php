<?php

namespace Webkul\EmailExtended\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\EmailExtended\Repositories\EmailThreadRepository;
use Webkul\EmailExtended\Repositories\EmailTrackingRepository;
use Webkul\EmailExtended\Repositories\EmailScheduledRepository;
use Webkul\Email\Repositories\EmailRepository;

class EmailThreadController extends Controller
{
    public function __construct(
        protected EmailThreadRepository $emailThreadRepository,
        protected EmailRepository $emailRepository,
        protected EmailTrackingRepository $emailTrackingRepository,
        protected EmailScheduledRepository $emailScheduledRepository
    ) {}

    /**
     * Redirect index về folder inbox
     */
    public function index(Request $request)
    {
        return redirect()->route('admin.mail.folder', ['folder' => 'inbox']);
    }

    /**
     * Hiển thị danh sách email threads theo folder
     * 
     * @param string $folder - Tên folder (inbox, sent, draft, scheduled, archive, trash)
     */
    public function folder(Request $request, string $folder)
    {
        $userId = auth()->guard('user')->id();
        
        // Lấy thống kê số lượng email theo folder
        $stats = [
            'total' => DB::table('email_threads')->where('user_id', $userId)->count(),
            'inbox' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'inbox')->count(),
            'sent' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'sent')->count(),
            'draft' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'draft')->count(),
            'scheduled' => DB::table('emails')->where('user_id', $userId)->where('status', 'scheduled')->count(),
            'archive' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'archive')->count(),
            'trash' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'trash')->count(),
            'unread' => DB::table('email_threads')->where('user_id', $userId)->where('is_read', 0)->count(),
            'starred' => DB::table('email_threads')->where('user_id', $userId)->where('is_starred', 1)->count(),
        ];
        
        // Tab "Scheduled" hiển thị emails thay vì threads
        if ($folder === 'scheduled') {
            $scheduledEmails = DB::table('emails')
                ->select(
                    'emails.id as email_id',
                    'emails.subject',
                    'emails.to',
                    'emails.from',
                    'emails.scheduled_at',
                    'emails.created_at',
                    'emails.thread_id',
                    'email_threads.subject as thread_subject'
                )
                ->leftJoin('email_threads', 'emails.thread_id', '=', 'email_threads.id')
                ->where('emails.user_id', $userId)
                ->where('emails.status', 'scheduled')
                ->orderBy('emails.scheduled_at', 'asc')
                ->paginate(20);
            
            return view('email_extended::scheduled', compact('folder', 'stats', 'scheduledEmails'));
        }

        // Các folder khác hiển thị threads
        $threads = DB::table('email_threads')
            ->select(
                'email_threads.id',
                'email_threads.subject',
                'email_threads.last_email_at',
                'email_threads.created_at',
                'email_threads.updated_at',
                'email_threads.email_count',
                'email_threads.is_read',
                'email_threads.is_starred',
                'email_threads.folder',
                DB::raw('(SELECT `to` FROM emails WHERE emails.thread_id = email_threads.id ORDER BY emails.id DESC LIMIT 1) as email_to'),
                DB::raw('(SELECT `from` FROM emails WHERE emails.thread_id = email_threads.id ORDER BY emails.id DESC LIMIT 1) as email_from')
            )
            ->where('email_threads.user_id', $userId)
            ->where('email_threads.folder', $folder)
            ->orderBy('email_threads.last_email_at', 'desc')
            ->paginate(20);
        
        return view('email_extended::index', compact('folder', 'stats', 'threads'));
    }

    /**
     * Hiển thị chi tiết thread và tất cả emails trong thread
     * 
     * @param int $id - Thread ID
     */
    public function show(int $id)
    {
        // Lấy thread với relations
        $thread = \Webkul\EmailExtended\Models\EmailThread::where('id', $id)
            ->where('user_id', auth()->guard('user')->id())
            ->with(['lead', 'person'])
            ->firstOrFail();
        
        // Lấy tất cả emails trong thread
        $emails = \Webkul\EmailExtended\Models\Email::where('thread_id', $thread->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Nếu không có email, tìm orphan emails (emails bị mất thread_id)
        if ($emails->isEmpty()) {
            $orphanEmails = \Webkul\EmailExtended\Models\Email::where('subject', $thread->subject)
                ->whereNull('thread_id')
                ->orWhere('thread_id', 0)
                ->get();
            
            // Gán orphan emails vào thread
            if ($orphanEmails->isNotEmpty()) {
                foreach ($orphanEmails as $orphan) {
                    $orphan->update(['thread_id' => $thread->id]);
                }
                
                // Reload emails sau khi fix
                $emails = \Webkul\EmailExtended\Models\Email::where('thread_id', $thread->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }
        
        // Set relation để view có thể dùng $thread->emails
        $thread->setRelation('emails', $emails);
        
        // Đánh dấu thread đã đọc (trừ draft)
        if ($thread->folder !== 'draft' && method_exists($thread, 'markAsRead')) {
            $thread->markAsRead();
        }
        
        // Lấy tracking stats cho các outbound emails
        $trackingStats = [];
        foreach ($emails as $email) {
            if ($email->direction === 'outbound') {
                $trackingStats[$email->id] = $this->emailTrackingRepository->getStatsForEmail($email->id);
            }
        }
        
        return view('email_extended::show', compact('thread', 'trackingStats'));
    }

    /**
     * Đánh dấu thread đã đọc
     */
    public function markRead(int $id)
    {
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $this->emailThreadRepository->markAsRead($id);
        
        return response()->json([
            'message' => trans('email_extended::app.threads.marked-as-read'),
            'success' => true,
        ]);
    }

    /**
     * Đánh dấu thread chưa đọc
     */
    public function markUnread(int $id)
    {
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $this->emailThreadRepository->markAsUnread($id);
        
        return response()->json([
            'message' => trans('email_extended::app.threads.marked-as-unread'),
            'success' => true,
        ]);
    }

    /**
     * Toggle star thread (đánh dấu sao)
     */
    public function toggleStar(int $id)
    {
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $this->emailThreadRepository->toggleStar($id);
        $thread->refresh();
        
        return response()->json([
            'message' => trans('email_extended::app.threads.star-toggled'),
            'is_starred' => $thread->is_starred,
            'success' => true,
        ]);
    }

    /**
     * Di chuyển thread sang folder khác
     * Lưu original_folder khi chuyển vào archive/trash
     */
    public function move(Request $request, int $id)
    {
        $this->validate($request, [
            'folder' => 'required|in:inbox,sent,draft,scheduled,archive,trash,spam',
        ]);
        
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $targetFolder = $request->folder;
        $currentFolder = $thread->folder;
        
        // Lưu original_folder khi chuyển vào archive hoặc trash
        if (in_array($targetFolder, ['archive', 'trash']) && !in_array($currentFolder, ['archive', 'trash'])) {
            DB::table('email_threads')
                ->where('id', $id)
                ->update([
                    'folder' => $targetFolder,
                    'original_folder' => $currentFolder,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('email_threads')
                ->where('id', $id)
                ->update([
                    'folder' => $targetFolder,
                    'updated_at' => now()
                ]);
        }
        
        return response()->json([
            'message' => trans('email_extended::app.threads.moved-to-folder', ['folder' => $targetFolder]),
            'success' => true,
        ]);
    }

    /**
     * Xóa thread (chuyển vào trash hoặc xóa vĩnh viễn)
     */
    public function destroy(int $id)
    {
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        Event::dispatch('email_thread.delete.before', $id);
        
        // Nếu đã ở trash, xóa vĩnh viễn
        if ($thread->folder === 'trash') {
            $thread->forceDelete();
            $message = trans('email_extended::app.threads.deleted-permanently');
        } else {
            // Chuyển vào trash và lưu original_folder
            DB::table('email_threads')
                ->where('id', $id)
                ->update([
                    'folder' => 'trash',
                    'original_folder' => $thread->folder,
                    'updated_at' => now()
                ]);
            $message = trans('email_extended::app.threads.moved-to-trash');
        }
        
        Event::dispatch('email_thread.delete.after', $id);
        
        return response()->json([
            'message' => $message,
            'success' => true,
        ]);
    }
    
    /**
     * Xóa vĩnh viễn thread từ thùng rác
     */
    public function destroyPermanent(int $id)
    {
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        Event::dispatch('email_thread.delete.before', $id);
        $thread->forceDelete();
        Event::dispatch('email_thread.delete.after', $id);
        
        return response()->json([
            'message' => 'Email đã được xóa vĩnh viễn',
            'success' => true,
        ]);
    }
    
    /**
     * Khôi phục thread về folder gốc
     */
    public function restore(int $id)
    {
        $thread = DB::table('email_threads')
            ->where('id', $id)
            ->where('user_id', auth()->guard('user')->id())
            ->first(['id', 'folder', 'original_folder']);
        
        if (!$thread) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Xác định folder để khôi phục (ưu tiên original_folder)
        $restoreToFolder = $thread->original_folder ?: 'inbox';
        
        // Khôi phục về folder gốc và xóa original_folder
        DB::table('email_threads')
            ->where('id', $id)
            ->update([
                'folder' => $restoreToFolder,
                'original_folder' => null,
                'updated_at' => now()
            ]);
        
        return response()->json([
            'message' => "Đã khôi phục email về " . $restoreToFolder,
            'success' => true,
        ]);
    }

    /**
     * Thao tác hàng loạt trên nhiều threads
     * Actions: mark_read, mark_unread, star, unstar, move, delete
     */
    public function massUpdate(Request $request)
    {
        $this->validate($request, [
            'indices' => 'required|array',
            'indices.*' => 'integer|exists:email_threads,id',
            'action' => 'required|in:mark_read,mark_unread,star,unstar,move,delete',
            'folder' => 'required_if:action,move|in:inbox,sent,draft,scheduled,archive,trash,spam',
        ]);
        
        $threadIds = $request->input('indices', []);
        $action = $request->input('action');
        
        // Xác minh quyền sở hữu
        $userId = auth()->guard('user')->id();
        $validThreads = DB::table('email_threads')
            ->whereIn('id', $threadIds)
            ->where('user_id', $userId)
            ->pluck('id')
            ->toArray();
        
        if (count($validThreads) !== count($threadIds)) {
            return response()->json([
                'message' => 'Bạn không có quyền thao tác trên một số email đã chọn',
                'success' => false
            ], 403);
        }
        
        try {
            switch ($action) {
                case 'mark_read':
                    DB::table('email_threads')
                        ->whereIn('id', $validThreads)
                        ->update(['is_read' => 1, 'updated_at' => now()]);
                    $message = 'Đã đánh dấu ' . count($validThreads) . ' email là đã đọc';
                    break;
                    
                case 'mark_unread':
                    DB::table('email_threads')
                        ->whereIn('id', $validThreads)
                        ->update(['is_read' => 0, 'updated_at' => now()]);
                    $message = 'Đã đánh dấu ' . count($validThreads) . ' email là chưa đọc';
                    break;
                    
                case 'star':
                    DB::table('email_threads')
                        ->whereIn('id', $validThreads)
                        ->update(['is_starred' => 1, 'updated_at' => now()]);
                    $message = 'Đã đánh dấu sao cho ' . count($validThreads) . ' email';
                    break;
                    
                case 'unstar':
                    DB::table('email_threads')
                        ->whereIn('id', $validThreads)
                        ->update(['is_starred' => 0, 'updated_at' => now()]);
                    $message = 'Đã bỏ đánh dấu sao cho ' . count($validThreads) . ' email';
                    break;
                    
                case 'move':
                    $targetFolder = $request->input('folder');
                    
                    // Lấy danh sách threads cần di chuyển
                    $threads = DB::table('email_threads')
                        ->whereIn('id', $validThreads)
                        ->get(['id', 'folder']);
                    
                    foreach ($threads as $thread) {
                        $currentFolder = $thread->folder;
                        
                        // Lưu original_folder khi chuyển vào archive/trash từ folder khác
                        if (in_array($targetFolder, ['archive', 'trash']) && !in_array($currentFolder, ['archive', 'trash'])) {
                            DB::table('email_threads')
                                ->where('id', $thread->id)
                                ->update([
                                    'folder' => $targetFolder,
                                    'original_folder' => $currentFolder,
                                    'updated_at' => now()
                                ]);
                        } else {
                            DB::table('email_threads')
                                ->where('id', $thread->id)
                                ->update([
                                    'folder' => $targetFolder,
                                    'updated_at' => now()
                                ]);
                        }
                    }
                    
                    $message = 'Đã di chuyển ' . count($validThreads) . ' email vào ' . $targetFolder;
                    break;
                    
                case 'delete':
                    // Lấy threads cần xóa
                    $threads = DB::table('email_threads')
                        ->whereIn('id', $validThreads)
                        ->get(['id', 'folder']);
                    
                    foreach ($threads as $thread) {
                        // Nếu chưa ở trash thì chuyển vào trash và lưu original_folder
                        if ($thread->folder !== 'trash') {
                            DB::table('email_threads')
                                ->where('id', $thread->id)
                                ->update([
                                    'folder' => 'trash',
                                    'original_folder' => $thread->folder,
                                    'updated_at' => now()
                                ]);
                        }
                    }
                    
                    $message = 'Đã chuyển ' . count($validThreads) . ' email vào thùng rác';
                    break;
                    
                default:
                    return response()->json([
                        'message' => 'Hành động không hợp lệ',
                        'success' => false
                    ], 400);
            }
            
            return response()->json([
                'message' => $message,
                'success' => true,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Xóa hàng loạt threads
     */
    public function massDelete(Request $request)
    {
        $threadIds = $request->input('indices', []);
        
        $userId = auth()->guard('user')->id();
        $deletedCount = DB::table('email_threads')
            ->whereIn('id', $threadIds)
            ->where('user_id', $userId)
            ->delete();
        
        return response()->json([
            'message' => 'Đã xóa ' . $deletedCount . ' email',
            'success' => true,
        ]);
    }

    /**
     * Tìm kiếm threads theo từ khóa
     * Tìm trong subject, from, to, reply content
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'query' => 'required|string|min:2',
        ]);
        
        $userId = auth()->guard('user')->id();
        $query = $request->input('query');
        $folder = $request->input('folder', 'inbox');
        
        $threads = DB::table('email_threads')
            ->select(
                'email_threads.id',
                'email_threads.subject',
                'email_threads.last_email_at',
                'email_threads.created_at',
                'email_threads.updated_at',
                'email_threads.email_count',
                'email_threads.is_read',
                'email_threads.is_starred',
                'email_threads.folder',
                DB::raw('(SELECT `to` FROM emails WHERE emails.thread_id = email_threads.id ORDER BY emails.id DESC LIMIT 1) as email_to'),
                DB::raw('(SELECT `from` FROM emails WHERE emails.thread_id = email_threads.id ORDER BY emails.id DESC LIMIT 1) as email_from')
            )
            ->where('email_threads.user_id', $userId)
            ->where(function($q) use ($query) {
                $q->where('subject', 'like', "%{$query}%")
                  ->orWhereExists(function($subq) use ($query) {
                      $subq->select(DB::raw(1))
                           ->from('emails')
                           ->whereColumn('emails.thread_id', 'email_threads.id')
                           ->where(function($sq) use ($query) {
                               $sq->where('from', 'like', "%{$query}%")
                                  ->orWhere('to', 'like', "%{$query}%")
                                  ->orWhere('reply', 'like', "%{$query}%");
                           });
                  });
            })
            ->when($folder, function($q) use ($folder) {
                return $q->where('folder', $folder);
            })
            ->orderBy('last_email_at', 'desc')
            ->paginate(20);
        
        $stats = [
            'total' => DB::table('email_threads')->where('user_id', $userId)->count(),
            'inbox' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'inbox')->count(),
            'sent' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'sent')->count(),
            'draft' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'draft')->count(),
            'scheduled' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'scheduled')->count(),
            'archive' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'archive')->count(),
            'trash' => DB::table('email_threads')->where('user_id', $userId)->where('folder', 'trash')->count(),
            'unread' => DB::table('email_threads')->where('user_id', $userId)->where('is_read', 0)->count(),
            'starred' => DB::table('email_threads')->where('user_id', $userId)->where('is_starred', 1)->count(),
        ];
        
        return view('email_extended::index', compact('threads', 'stats', 'folder'));
    }

    /**
     * Hiển thị danh sách emails đã lên lịch
     */
    public function scheduled(Request $request)
    {
        $userId = auth()->guard('user')->id();
        $stats = $this->emailScheduledRepository->getStatistics(['user_id' => $userId]);
        
        return view('email_extended::scheduled', compact('stats'));
    }

    /**
     * Hủy email đã lên lịch
     */
    public function cancelScheduled(int $id)
    {
        $scheduled = $this->emailScheduledRepository->findOrFail($id);
        
        if ($scheduled->email->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $this->emailScheduledRepository->cancel($id);
        
        return response()->json([
            'message' => trans('email_extended::app.scheduled.cancelled'),
            'success' => true,
        ]);
    }

    /**
     * Đổi lịch gửi email
     */
    public function reschedule(Request $request, int $id)
    {
        $this->validate($request, [
            'scheduled_at' => 'required|date|after:now',
        ]);
        
        $scheduled = $this->emailScheduledRepository->findOrFail($id);
        
        if ($scheduled->email->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $this->emailScheduledRepository->reschedule($id, $request->scheduled_at);
        
        return response()->json([
            'message' => trans('email_extended::app.scheduled.rescheduled'),
            'success' => true,
        ]);
    }

    /**
     * Thêm tag vào thread
     */
    public function addTag(Request $request, int $id)
    {
        $this->validate($request, [
            'tag' => 'required|string|max:50',
        ]);
        
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $this->emailThreadRepository->addTag($id, $request->tag);
        
        return response()->json([
            'message' => trans('email_extended::app.threads.tag-added'),
            'success' => true,
        ]);
    }

    /**
     * Xóa tag khỏi thread
     */
    public function removeTag(Request $request, int $id)
    {
        $this->validate($request, [
            'tag' => 'required|string',
        ]);
        
        $thread = $this->emailThreadRepository->findOrFail($id);
        
        if ($thread->user_id !== auth()->guard('user')->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $this->emailThreadRepository->removeTag($id, $request->tag);
        
        return response()->json([
            'message' => trans('email_extended::app.threads.tag-removed'),
            'success' => true,
        ]);
    }

    /**
     * Hủy hàng loạt emails đã lên lịch
     */
    public function massCancelScheduled(Request $request)
    {
        $scheduledIds = $request->input('indices', []);
        
        foreach ($scheduledIds as $id) {
            $scheduled = $this->emailScheduledRepository->find($id);
            if ($scheduled && $scheduled->email->user_id === auth()->guard('user')->id()) {
                $this->emailScheduledRepository->cancel($id);
            }
        }
        
        return response()->json([
            'message' => trans('email_extended::app.scheduled.cancelled'),
            'success' => true,
        ]);
    }

    /**
     * Xóa hàng loạt emails đã lên lịch
     */
    public function massDeleteScheduled(Request $request)
    {
        $scheduledIds = $request->input('indices', []);
        
        foreach ($scheduledIds as $id) {
            $scheduled = $this->emailScheduledRepository->find($id);
            if ($scheduled && $scheduled->email->user_id === auth()->guard('user')->id()) {
                $scheduled->delete();
            }
        }
        
        return response()->json([
            'message' => trans('email_extended::app.scheduled.deleted'),
            'success' => true,
        ]);
    }
    
    /**
     * Khôi phục hàng loạt threads về folder gốc
     */
    public function massRestore(Request $request)
    {
        $this->validate($request, [
            'indices' => 'required|array',
            'indices.*' => 'integer|exists:email_threads,id',
        ]);
        
        $threadIds = $request->input('indices', []);
        $userId = auth()->guard('user')->id();
        
        // Xác minh quyền sở hữu
        $threads = DB::table('email_threads')
            ->whereIn('id', $threadIds)
            ->where('user_id', $userId)
            ->get(['id', 'original_folder']);
        
        if ($threads->count() !== count($threadIds)) {
            return response()->json([
                'message' => 'Bạn không có quyền thao tác',
                'success' => false
            ], 403);
        }
        
        // Khôi phục từng thread
        foreach ($threads as $thread) {
            $restoreFolder = $thread->original_folder ?: 'inbox';
            DB::table('email_threads')
                ->where('id', $thread->id)
                ->update([
                    'folder' => $restoreFolder,
                    'original_folder' => null,
                    'updated_at' => now()
                ]);
        }
        
        return response()->json([
            'message' => 'Đã khôi phục ' . count($threadIds) . ' email',
            'success' => true,
        ]);
    }
    
    /**
     * Xóa vĩnh viễn hàng loạt threads
     */
    public function massPermanentDelete(Request $request)
    {
        // Lấy danh sách ID email được chọn
        $threadIds = $request->input('indices', []);

        // Nếu không có email nào được chọn
        if (empty($threadIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có email nào được chọn để xóa',
            ], 400);
        }

        // Lấy ID user đang đăng nhập
        $userId = auth()->guard('user')->id();

        // Xóa vĩnh viễn các email thuộc về user
        $deletedCount = DB::table('email_threads')
            ->whereIn('id', $threadIds)
            ->where('user_id', $userId)
            ->delete();

        // Trả kết quả về client
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa vĩnh viễn ' . $deletedCount . ' email',
            'deleted_count' => $deletedCount,
        ]);
    }
}