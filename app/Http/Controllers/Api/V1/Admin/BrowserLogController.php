<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrowserLogController extends Controller
{
    /**
     * Store browser logs from the React application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'logs' => 'required|array',
            'logs.*.level' => 'required|string|in:debug,info,warn,error',
            'logs.*.message' => 'required|string',
            'logs.*.timestamp' => 'required|integer',
            'logs.*.url' => 'nullable|string',
            'logs.*.userId' => 'nullable|integer',
            'logs.*.stack' => 'nullable|string',
            'logs.*.data' => 'nullable|array',
        ]);

        $logPath = storage_path('logs/browser.log');

        foreach ($validated['logs'] as $logEntry) {
            $timestamp = date('Y-m-d H:i:s', $logEntry['timestamp'] / 1000);
            $level = strtoupper($logEntry['level']);
            $message = $logEntry['message'];
            $url = $logEntry['url'] ?? 'N/A';
            $userId = $logEntry['userId'] ?? auth()->id() ?? 'guest';
            $stack = $logEntry['stack'] ?? '';
            $data = !empty($logEntry['data']) ? json_encode($logEntry['data']) : '';

            $logLine = "[{$timestamp}] [{$level}] [User:{$userId}] [{$url}] {$message}";

            if ($data) {
                $logLine .= " | Data: {$data}";
            }

            if ($stack) {
                $logLine .= "\nStack Trace:\n{$stack}";
            }

            // Append to browser log file
            file_put_contents($logPath, $logLine . PHP_EOL, FILE_APPEND | LOCK_EX);

            // Also log to Laravel's log channel for critical errors
            if ($level === 'ERROR') {
                Log::error('Browser Error', [
                    'message' => $message,
                    'url' => $url,
                    'user_id' => $userId,
                    'stack' => $stack,
                    'data' => $logEntry['data'] ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => 'Logs stored successfully',
            'count' => count($validated['logs']),
        ], 200);
    }

    /**
     * Clear old browser logs (keep only last 7 days).
     *
     * @return JsonResponse
     */
    public function clear(): JsonResponse
    {
        $logPath = storage_path('logs/browser.log');

        if (file_exists($logPath)) {
            $lines = file($logPath);
            $cutoffTime = now()->subDays(7)->timestamp;
            $filteredLines = [];

            foreach ($lines as $line) {
                if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                    $logTime = strtotime($matches[1]);
                    if ($logTime >= $cutoffTime) {
                        $filteredLines[] = $line;
                    }
                }
            }

            file_put_contents($logPath, implode('', $filteredLines), LOCK_EX);

            return response()->json([
                'message' => 'Old logs cleared',
                'remaining_lines' => count($filteredLines),
            ], 200);
        }

        return response()->json([
            'message' => 'No log file found',
        ], 404);
    }
}
