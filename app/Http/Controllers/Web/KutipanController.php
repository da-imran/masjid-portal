<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KutipanMasjid;

class KutipanController extends Controller
{
    public function index(Request $request)
    {
        // Order by month, then week, then day (Isnin = 1, Ahad = 7)
        $kutipanList = KutipanMasjid::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('week', 'asc')
            ->orderBy('day', 'asc')
            ->paginate(10); // 10 records per page

        $totalKutipan = KutipanMasjid::sum('year_total');

        return view('information.kutipanTabungMasjid', compact('kutipanList', 'totalKutipan'));
    }
}
