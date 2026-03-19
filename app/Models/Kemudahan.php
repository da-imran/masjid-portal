<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kemudahan extends Model
{
    use HasFactory;

    protected $table = 'kemudahan';

    protected $fillable = [
        'title_ms',
        'title_en',
        'description_ms',
        'description_en',
        'image_name',
        'order_column',
        'is_active',
        'is_deleted',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Get the user who created this facility.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this facility.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the title based on locale.
     */
    public function getTitleAttribute(): string
    {
        return $this->title_ms;
    }

    /**
     * Get the description based on locale.
     */
    public function getDescriptionAttribute(): string
    {
        return $this->description_ms;
    }

    /**
     * Scope to filter active facilities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by order_column.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_column');
    }
}
