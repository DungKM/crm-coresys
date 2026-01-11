<?php 
namespace Webkul\EmailTemplateExtended\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\EmailTemplate\Models\EmailTemplate as CoreEmailTemplate;
use Webkul\User\Models\User;

/** EXTENDED EMAIL TEMPLATE MODEL
 * Thêm các tính năng:
 * - Variables system (personalization)
 * - Categories & Tags
 * - Usage tracking
 * - Preview & Sample data
 * - Clone support
 * - Multi-language
 * - Editor mode support (Classic/Pro)
 */
class EmailTemplate extends CoreEmailTemplate
{
    use SoftDeletes;

    /**
     * Giữ nguyên các field core: name, subject, content
     */
    protected $fillable = [
        // Core fields
        'name',
        'subject',
        'content',
        
        // NEW: Editor mode fields
        'editor_mode',         // classic | pro
        'builder_config',      // JSON: EmailBuilder.js config
        
        // Extended fields
        'variables',           // JSON: [{name, type, default, description}]
        'category',            // sales, marketing, support, notification, internal
        'tags',                // JSON: ['welcome', 'follow-up']
        'usage_count',         // Số lần sử dụng
        'last_used_at',        // Lần cuối sử dụng
        'is_active',           // Template có đang hoạt động không
        'preview_text',        // Plain text preview
        'sample_data',         // JSON: Sample data để preview
        'thumbnail',           // URL ảnh thumbnail
        'locale',              // vi, en
        'cloned_from_id',      // Template được clone từ đâu
        'metadata',            // JSON: Thông tin thêm
        'user_id',             // Owner của template
    ];

    protected $casts = [
        // JSON fields
        'variables' => 'array',
        'tags' => 'array',
        'sample_data' => 'array',
        'metadata' => 'array',
        'builder_config' => 'array',  // NEW: Cast builder_config as array
        
        // Boolean fields
        'is_active' => 'boolean',
        
        // Datetime fields
        'last_used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Default values
     */
    protected $attributes = [
        'category' => 'general',
        'locale' => 'vi',
        'is_active' => true,
        'usage_count' => 0,
        'editor_mode' => 'classic',  // NEW: Default editor mode
    ];
    
    /**
     * Template thuộc về User nào (creator)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Template được clone từ template nào
     */
    public function clonedFrom()
    {
        return $this->belongsTo(self::class, 'cloned_from_id');
    }

    /**
     * Template này đã được clone thành những template nào
     */
    public function clones()
    {
        return $this->hasMany(self::class, 'cloned_from_id');
    }

    /**
     * Chỉ lấy template đang active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter theo category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Filter theo editor mode
     */
    public function scopeEditorMode($query, $mode)
    {
        return $query->where('editor_mode', $mode);
    }

    /**
     * Lấy template phổ biến nhất
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('usage_count', 'desc')
                    ->limit($limit);
    }

    /**
     * Lấy template gần đây
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    /**
     * Filter theo tag
     */
    public function scopeWithTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Lấy template theo locale (ngôn ngữ hiện tại)
     */
    public function scopeLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Search template
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('subject', 'like', "%{$search}%")
              ->orWhere('preview_text', 'like', "%{$search}%");
        });
    }
    
    /**
     * Check template có active không
     */
    public function getIsActiveAttribute($value)
    {
        return (bool) $value;
    }

    /**
     * Lấy category label
     */
    public function getCategoryLabelAttribute()
    {
        return $this->getCategoryLabels()[$this->category] ?? $this->category;
    }

    /**
     * Check xem template có dùng Pro Builder không
     */
    public function isProMode(): bool
    {
        return $this->editor_mode === 'pro';
    }

    /**
     * Check xem template có dùng Classic Editor không
     */
    public function isClassicMode(): bool
    {
        return $this->editor_mode === 'classic';
    }

    /**
     * Get builder config (decoded)
     */
    public function getBuilderConfig(): ?array
    {
        return $this->builder_config;
    }
    
    /**
     * Tăng usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
        return $this;
    }

    /**
     * Lấy danh sách variables đã định nghĩa
     */
    public function getAvailableVariables(): array
    {
        return $this->variables ?? [];
    }

    /**
     * Extract variables từ content (tìm tất cả {{variable_name}})
     */
    public function extractVariablesFromContent(): array
    {
        preg_match_all('/\{\{([a-zA-Z0-9_\.]+)\}\}/', $this->content, $matches);
        return array_unique($matches[1] ?? []);
    }

    /**
     * Extract variables từ subject
     */
    public function extractVariablesFromSubject(): array
    {
        preg_match_all('/\{\{([a-zA-Z0-9_\.]+)\}\}/', $this->subject, $matches);
        return array_unique($matches[1] ?? []);
    }

    /**
     * Lấy tất cả variables được sử dụng (content + subject)
     */
    public function getAllUsedVariables(): array
    {
        $contentVars = $this->extractVariablesFromContent();
        $subjectVars = $this->extractVariablesFromSubject();
        return array_unique(array_merge($contentVars, $subjectVars));
    }

    /**
     * Kiểm tra template có variables chưa định nghĩa không
     */
    public function hasUndefinedVariables(): bool
    {
        $defined = collect($this->getAvailableVariables())->pluck('name')->toArray();
        $used = $this->getAllUsedVariables();
        $undefined = array_diff($used, $defined);
        return count($undefined) > 0;
    }

    /**
     * Lấy danh sách variables chưa định nghĩa
     */
    public function getUndefinedVariables(): array
    {
        $defined = collect($this->getAvailableVariables())->pluck('name')->toArray();
        $used = $this->getAllUsedVariables();
        return array_values(array_diff($used, $defined));
    }

    /**
     * Lấy danh sách variables đã định nghĩa nhưng không dùng
     */
    public function getUnusedVariables(): array
    {
        $defined = collect($this->getAvailableVariables())->pluck('name')->toArray();
        $used = $this->getAllUsedVariables();
        return array_values(array_diff($defined, $used));
    }

    /**
     * Toggle active status
     */
    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
        return $this;
    }

    /**
     * Đánh dấu template là inactive
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Đánh dấu template là active
     */
    public function activate()
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Thêm tag nếu chưa có
     */
    public function addTag(string $tag)
    {
        $tags = $this->tags ?? [];
        
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
        
        return $this;
    }

    /**
     * Xóa đúng 1 tag khỏi danh sách
     */
    public function removeTag(string $tag)
    {
        $tags = $this->tags ?? [];
        $tags = array_filter($tags, fn($t) => $t !== $tag);
        
        $this->update(['tags' => array_values($tags)]);
        return $this;
    }

    /**
     * Thay đổi toàn bộ list tag
     */
    public function syncTags(array $tags)
    {
        $this->update(['tags' => $tags]);
        return $this;
    }

    /**
     * Check template có tag không
     */
    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }
    
    /**
     * Danh sách categories có sẵn
     */
    public static function getCategories(): array
    {
        return [
            'sales'         => 'Sales (Bán hàng)',
            'marketing'     => 'Marketing (Tiếp thị)',
            'support'       => 'Support (Hỗ trợ)',
            'customer_care' => 'Customer Care (CSKH)',
            'workflow'      => 'Workflow Automation',
            'transactional' => 'Transactional (Hệ thống)',
            'notification'  => 'Notification (Thông báo)',
            'internal'      => 'Internal (Nội bộ)',
            'billing'       => 'Billing / Order (Hóa đơn & Đơn hàng)',
            'reporting'     => 'Reporting (Báo cáo)',
            'general'       => 'General (Chung)',
        ];
    }

    /**
     * Lấy category labels
     */
    public static function getCategoryLabels(): array
    {
        return self::getCategories();
    }

    /**
     * Danh sách variable types có sẵn
     */
    public static function getVariableTypes(): array
    {
        return [
            'text' => 'Text',
            'email' => 'Email',
            'number' => 'Number',
            'date' => 'Date',
            'datetime' => 'DateTime',
            'url' => 'URL',
            'phone' => 'Phone',
            'boolean' => 'Boolean',
        ];
    }

    /**
     * Tạo sample data mặc định dựa trên type
     */
    public static function getDefaultSampleValue(string $type)
    {
        return match($type) {
            'text' => 'CoreSys',
            'email' => 'CoreSys@example.com',
            'number' => 1000,
            'date' => now()->format('d/m/Y'),
            'datetime' => now()->format('d/m/Y H:i'),
            'url' => 'https://example.com',
            'phone' => '0123456789',
            'boolean' => true,
            default => 'Sample Value',
        };
    }

    protected static function boot()
    {
        parent::boot();

        // Auto-generate preview text từ content khi tạo mới
        static::creating(function ($template) {
            if (empty($template->preview_text) && !empty($template->content)) {
                $template->preview_text = strip_tags(
                    substr($template->content, 0, 200)
                ) . '...';
            }
        });

        // Auto-update preview text khi update content
        static::updating(function ($template) {
            if ($template->isDirty('content') && empty($template->preview_text)) {
                $template->preview_text = strip_tags(
                    substr($template->content, 0, 200)
                ) . '...';
            }
        });
    }
}