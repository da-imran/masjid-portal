<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaSemasa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'berita_semasa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_ms',
        'title_en',
        'image_name',
        'description_ms',
        'description_en',
        'content_ms',
        'content_en',
        'view_count',
        'is_active',
        'is_deleted',
        'is_featured',
        'published_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'view_count' => 'integer',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created the news.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the news.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to only include active news.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only include featured news.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to only include published news.
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * Scope to order by view count.
     */
    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    /**
     * Get the title based on locale.
     */
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ms' ? $this->title_ms : $this->title_en;
    }

    /**
     * Get the description based on locale.
     */
    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'ms' ? $this->description_ms : $this->description_en;
    }

    /**
     * Get the content based on locale.
     */
    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'ms' ? $this->content_ms : $this->content_en;
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(): int
    {
        $this->increment('view_count');
        return $this->fresh()->view_count;
    }
}
