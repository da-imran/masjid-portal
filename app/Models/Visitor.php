<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visitors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip_address',
        'session_id',
        'visit_count',
        'last_visit_at',
        'user_agent',
        'referrer',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visit_count' => 'integer',
        'last_visit_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Find or create a visitor by IP address.
     */
    public static function trackVisit(string $ipAddress, ?string $sessionId = null): self
    {
        $visitor = self::firstOrCreate(
            ['ip_address' => $ipAddress],
            [
                'session_id' => $sessionId,
                'visit_count' => 1,
                'last_visit_at' => now(),
            ]
        );

        if ($visitor->wasRecentlyCreated) {
            return $visitor;
        }

        // Update existing visitor
        $visitor->increment('visit_count');
        $visitor->update([
            'last_visit_at' => now(),
            'session_id' => $sessionId ?? $visitor->session_id,
        ]);

        return $visitor->fresh();
    }

    /**
     * Get visitor statistics.
     */
    public static function getStatistics(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            'today' => self::where('created_at', '>=', $today)->count(),
            'this_month' => self::where('created_at', '>=', $thisMonth)->count(),
            'total' => self::count(),
        ];
    }
}
