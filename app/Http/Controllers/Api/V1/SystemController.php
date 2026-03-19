<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SystemController extends Controller
{
    /**
     * Health check endpoint.
     *
     * @return JsonResponse
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'messages' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
        ]);
    }

    /**
     * Check if the application is in maintenance mode.
     *
     * @return JsonResponse
     */
    public function maintenanceStatus(): JsonResponse
    {
        $isDown = app()->isDownForMaintenance();

        return response()->json([
            'maintenance' => $isDown,
            'message' => $isDown
                ? 'Harap Maaf. Kami sedang melakukan kerja penyelengaraan. Sila cuba sebentar lagi.'
                : null,
        ]);
    }
}
