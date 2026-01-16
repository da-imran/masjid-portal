<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Takwim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'takwim';

    protected $fillable = [
        'title_ms',
        'title_en',
        'description_ms',
        'description_en',
        'event_date',
        'event_time',
        'location_ms',
        'location_en',
        'image_name',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i:s',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this event.
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
     * Get the location based on locale.
     */
    public function getLocationAttribute(): string
    {
        return $this->location_ms;
    }

    /**
     * Scope to filter active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->startOfDay())
            ->orderBy('event_date');
    }

    /**
     * Scope to get past events.
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->startOfDay())
            ->orderBy('event_date', 'desc');
    }
}
