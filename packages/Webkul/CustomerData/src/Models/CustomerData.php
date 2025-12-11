<?php

namespace Webkul\CustomerData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Webkul\Lead\Models\Lead;

class CustomerData extends Model
{
    use SoftDeletes;

    protected $table = 'customer_data';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'title',
        'customer_type',
        'status',
        'verify_token',
        'verify_token_expires_at',
        'verified_at',
        'converted_to_lead_id',
        'spam_reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'verify_token_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'converted_to_lead_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    // Accessors & Mutators
    public function getIsVerifiedAttribute()
    {
        return $this->status === 'verified';
    }

    public function getIsConvertedAttribute()
    {
        return $this->status === 'converted';
    }

    public function getIsSpamAttribute()
    {
        return $this->status === 'spam';
    }

    public function getVerifyUrlAttribute()
    {
        return route('admin.customer-data.verify', $this->verify_token);
    }

    // Kiểm tra đã convert chưa
    public function isConverted(): bool
    {
        // Kiểm tra cả status và converted_to_lead_id
        return $this->status === 'converted' && !empty($this->converted_to_lead_id);
    }

    //Kiểm tra có thể convert không
    public function canConvert(): bool
    {
        // Chỉ convert được khi: verified và chưa có lead_id
        return $this->status === 'verified' && empty($this->converted_to_lead_id);
    }

    // Tạo token xác thực 
    public function generateVerifyToken()
    {
        $this->verify_token = Str::random(64);
        $this->verify_token_expires_at = now()->addDays(7); // Token expires in 7 days
        $this->save();
        
        return $this->verify_token;
    }

    // Update status customer 
    public function markAsVerified()
    {
        $this->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    // Đánh dấu khách hàng là spam 
    public function markAsSpam($reason = null)
    {
        $this->update([
            'status' => 'spam',
            'spam_reason' => $reason,
        ]);
    }

    // Chuyển leads => update status + liên kết leads mới 
    public function convertToLead($leadId)
    {
        $this->update([
            'status' => 'converted',
            'converted_to_lead_id' => $leadId,
        ]);
        $this->refresh();
    }

    // Kiểm tra token còn hiệu lực 
    public function isTokenValid()
    {
        return $this->verify_token_expires_at && 
               $this->verify_token_expires_at->isFuture();
    }

    // Validation Rules
    public static function validationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer_data,email',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'title' => 'nullable|string',
            'customer_type' => 'required|in:retail,business',
        ];
    }
}