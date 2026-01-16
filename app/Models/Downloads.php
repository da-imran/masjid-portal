<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Downloads extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'downloads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_ms',
        'title_en',
        'description_ms',
        'description_en',
        'category', // borang, nota_kuliah, jadual_kuliah
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'download_count',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'download_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created the download.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the download.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to only include active downloads.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Increment download count.
     */
    public function incrementDownloadCount(): bool
    {
        $this->increment('download_count');
        return true;
    }

    /**
     * Get the title based on locale.
     */
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ms' ? $this->title_ms : ($this->title_en ?? $this->title_ms);
    }

    /**
     * Get the description based on locale.
     */
    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'ms' ? $this->description_ms : ($this->description_en ?? $this->description_ms ?? '');
    }
}
