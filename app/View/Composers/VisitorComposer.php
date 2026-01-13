<?php

namespace App\View\Composers;

use App\Models\Visitor;
use Illuminate\View\View;

class VisitorComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        // Get visitor statistics
        $visitorStats = Visitor::getStatistics();

        // Create visitor count object in the format expected by the view
        $visitorCount = (object) [
            'original' => [
                'dailyCount' => $visitorStats['today'],
                'monthlyCount' => $visitorStats['this_month'],
                'overallCount' => $visitorStats['total'],
            ]
        ];

        $view->with('visitorCount', $visitorCount);
    }
}
