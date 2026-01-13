<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $pengumumanList = Pengumuman::active()
            ->valid()
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->latest('created_at')
            ->get();

        return view('information.pengumumanContent', compact('pengumumanList'));
    }

    public function show(Request $request, $id)
    {
        $pengumuman = Pengumuman::active()
            ->valid()
            ->findOrFail($id);

        return view('information.pengumumanContent', compact('pengumuman'));
    }
}
