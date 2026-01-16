<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KutipanMasjid extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kutipan_masjid';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['day_name_ms', 'month_name_ms'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'day',
        'week',
        'month',
        'year',
        'day_total',
        'week_total',
        'month_total',
        'year_total',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day' => 'integer',
        'week' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'day_total' => 'decimal:2',
        'week_total' => 'decimal:2',
        'month_total' => 'decimal:2',
        'year_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created the record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to filter by month and year.
     */
    public function scopeByMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Scope to filter by year.
     */
    public function scopeByYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to get current month records.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->where('month', now()->month)
            ->where('year', now()->year);
    }

    /**
     * Get the day name in Malay.
     */
    public function getDayNameMsAttribute(): string
    {
        $days = [
            1 => 'Isnin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Khamis',
            5 => 'Jumaat',
            6 => 'Sabtu',
            7 => 'Ahad',
        ];

        return $days[$this->day] ?? '';
    }

    /**
     * Get the month name in Malay.
     */
    public function getMonthNameMsAttribute(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Mac',
            4 => 'April',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Julai',
            8 => 'Ogos',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Disember',
        ];

        return $months[$this->month] ?? '';
    }

    /**
     * Get summary totals for a specific period.
     */
    public static function getSummary(int $month, int $year): array
    {
        $records = self::byMonth($month, $year)->get();

        return [
            'day_total' => $records->sum('day_total'),
            'week_total' => $records->max('week_total'),
            'month_total' => $records->max('month_total'),
            'year_total' => $records->max('year_total'),
        ];
    }
}
