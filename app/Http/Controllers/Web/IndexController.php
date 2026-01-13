<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeritaSemasa;
use App\Models\Pengumuman;
use App\Models\Visitor;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        // Track visitor
        Visitor::trackVisit(
            $request->ip(),
            $request->session()->getId()
        );

        // Get active and published news
        $beritaList = BeritaSemasa::active()
            ->published()
            ->latest('published_at')
            ->get();

        // Get active and valid (non-expired) announcements
        $pengumumanList = Pengumuman::active()
            ->valid()
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->latest('created_at')
            ->get();

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

        return view('index.index', compact('beritaList', 'pengumumanList', 'visitorCount'));
    }
}
