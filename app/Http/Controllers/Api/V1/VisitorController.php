<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitorStatsResource;
use App\Models\Visitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitorController extends BaseController
{
    /**
     * Track visitor.
     */
    public function track(Request $request): JsonResponse
    {
        $ipAddress = $request->ip() ?? '127.0.0.1';
        $sessionId = $request->input('session_id');

        $visitor = Visitor::trackVisit($ipAddress, $sessionId);

        return $this->success([
            'visit_count' => $visitor->visit_count,
            'last_visit_at' => $visitor->last_visit_at?->toIso8601String(),
        ], 'Visitor tracked successfully');
    }

    /**
     * Get visitor statistics.
     */
    public function stats(): VisitorStatsResource
    {
        $stats = Visitor::getStatistics();

        return new VisitorStatsResource($stats);
    }
}
