<?php

namespace Webkul\EmailExtended\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\EmailExtended\Contracts\EmailThread as EmailThreadContract;
use Webkul\Email\Models\EmailProxy;
use Webkul\Lead\Models\LeadProxy;
use Webkul\Contact\Models\PersonProxy;
use Webkul\User\Models\UserProxy;

class EmailThread extends Model implements EmailThreadContract
{
    use SoftDeletes;
    protected $table = 'email_threads';
    protected $fillable = [
        'subject',
        'message_id',
        'lead_id',
        'person_id',
        'user_id',
        'last_email_at',
        'email_count',
        'unread_count',
        'is_read',
        'is_starred',
        'is_important',
        'folder',
        'tags',
        'participants',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_email_at' => 'datetime',
        'is_read'       => 'boolean',
        'is_starred'    => 'boolean',
        'is_important'  => 'boolean',
        'tags'          => 'array',
        'participants'  => 'array',
        'metadata'      => 'array',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'snippet',
    ];

    /**
     * Get all emails in this thread
     */
    public function emails()
    {
        return $this->hasMany(EmailProxy::modelClass(), 'thread_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get latest email in thread
     */
    public function latestEmail()
    {
        return $this->hasOne(EmailProxy::modelClass(), 'thread_id')
            ->latestOfMany();
    }

    /**
     * Get first email in thread
     */
    public function firstEmail()
    {
        return $this->hasOne(EmailProxy::modelClass(), 'thread_id')
            ->oldestOfMany();
    }

    /**
     * Get the lead that owns the thread
     */
    public function lead()
    {
        return $this->belongsTo(LeadProxy::modelClass());
    }

    /**
     * Get the person that owns the thread
     */
    public function person()
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

    /**
     * Get the user that owns the thread
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    /**
     * Scope threads in specific folder
     */
    public function scopeInFolder($query, string $folder)
    {
        return $query->where('folder', $folder);
    }

    /**
     * Scope unread threads
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope read threads
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope starred threads
     */
    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    /**
     * Scope important threads
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope threads for specific user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope threads with specific tag
     */
    public function scopeWithTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Scope recent threads
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('last_email_at', '>=', now()->subDays($days));
    }

    /**
     * Mark thread as read
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'is_read' => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Mark thread as unread
     */
    public function markAsUnread(): bool
    {
        return $this->update([
            'is_read' => false,
        ]);
    }

    /**
     * Toggle starred status
     */
    public function toggleStar(): bool
    {
        return $this->update([
            'is_starred' => !$this->is_starred,
        ]);
    }

    /**
     * Toggle important status
     */
    public function toggleImportant(): bool
    {
        return $this->update([
            'is_important' => !$this->is_important,
        ]);
    }

    /**
     * Move thread to folder
     */
    public function moveToFolder(string $folder): bool
    {
        if (!in_array($folder, ['inbox', 'sent', 'draft', 'archive', 'trash', 'spam'])) {
            return false;
        }

        return $this->update(['folder' => $folder]);
    }

    /**
     * Archive thread
     */
    public function archive(): bool
    {
        return $this->moveToFolder('archive');
    }

    /**
     * Move to trash
     */
    public function trash(): bool
    {
        return $this->moveToFolder('trash');
    }

    /**
     * Restore from trash
     */
    public function restore(): bool
    {
        return $this->moveToFolder('inbox');
    }

    /**
     * Add tag to thread
     */
    public function addTag(string $tag): bool
    {
        $tags = $this->tags ?? [];
        
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            return $this->update(['tags' => $tags]);
        }
        
        return false;
    }

    /**
     * Remove tag from thread
     */
    public function removeTag(string $tag): bool
    {
        $tags = $this->tags ?? [];
        
        if (($key = array_search($tag, $tags)) !== false) {
            unset($tags[$key]);
            return $this->update(['tags' => array_values($tags)]);
        }
        
        return false;
    }

    /**
     * Check if thread has tag
     */
    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }

    /**
     * Add participant email
     */
    public function addParticipant(string $email): void
    {
        $participants = $this->participants ?? [];
        
        $email = strtolower(trim($email));
        
        if (!in_array($email, $participants)) {
            $participants[] = $email;
            $this->update(['participants' => $participants]);
        }
    }

    /**
     * Get participants count
     */
    public function getParticipantsCount(): int
    {
        return count($this->participants ?? []);
    }

    /**
     * Get participants as comma-separated string
     */
    public function getParticipantsString(): string
    {
        return implode(', ', $this->participants ?? []);
    }

    /**
     * Increment email count
     */
    public function incrementEmailCount(): void
    {
        $this->increment('email_count');
        $this->update(['last_email_at' => now()]);
    }

    /**
     * Increment unread count
     */
    public function incrementUnreadCount(): void
    {
        $this->increment('unread_count');
        $this->update(['is_read' => false]);
    }

    /**
     * Check if has unread emails
     */
    public function hasUnreadEmails(): bool
    {
        return $this->unread_count > 0;
    }

    /**
     * Get thread snippet from latest email
     */
    public function getSnippet(int $length = 150): string
    {
        $latestEmail = $this->latestEmail;
        
        if (!$latestEmail) {
            return '';
        }
        
        $content = strip_tags($latestEmail->reply ?? '');
        return \Illuminate\Support\Str::limit($content, $length);
    }

    /**
     * Accessor for snippet attribute
     */
    public function getSnippetAttribute(): string
    {
        return $this->getSnippet();
    }

    /**
     * Create thread from email
     */
    public static function createFromEmail($email): self
    {
        return self::create([
            'subject' => $email->subject,
            'message_id' => $email->message_id ?? self::generateMessageId(),
            'lead_id' => $email->lead_id,
            'person_id' => $email->person_id,
            'user_id' => auth()->guard('user')->id(),
            'last_email_at' => now(),
            'email_count' => 1,
            'unread_count' => $email->direction === 'inbound' ? 1 : 0,
            'is_read' => $email->direction !== 'inbound',
            'folder' => $email->direction === 'inbound' ? 'inbox' : 'sent',
            'participants' => array_filter([
                is_array($email->from) ? ($email->from['email'] ?? null) : $email->from,
                is_array($email->reply_to) ? ($email->reply_to['email'] ?? null) : null,
            ]),
        ]);
    }

    /**
     * Generate unique message ID
     */
    public static function generateMessageId(): string
    {
        return sprintf(
            '<%s.%s@%s>',
            uniqid(),
            time(),
            parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost'
        );
    }

    /**
     * Find thread by message ID
     */
    public static function findByMessageId(string $messageId): ?self
    {
        return self::where('message_id', $messageId)->first();
    }
}