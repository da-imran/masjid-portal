<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function jadual()
    {
        return view('download.jadualKuliah');
    }

    public function nota()
    {
        return view('download.notaKuliah');
    }

    public function borang()
    {
        return view('download.borang');
    }
}
