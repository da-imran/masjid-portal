<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeritaSemasa;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        // Use the existing beritaSemasa view
        $beritaList = BeritaSemasa::active()
            ->published()
            ->latest('published_at')
            ->get();

        return view('information.beritaSemasa', compact('beritaList'));
    }

    public function show(Request $request, $id)
    {
        $berita = BeritaSemasa::active()
            ->published()
            ->findOrFail($id);

        // Increment view count
        $berita->increment('view_count');

        return view('information.beritaSemasaContent', compact('berita'));
    }
}
