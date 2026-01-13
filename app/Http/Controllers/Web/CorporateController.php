<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CorporateController extends Controller
{
    public function profil()
    {
        return view('corporate.profilKorporat');
    }

    public function sejarah()
    {
        return view('corporate.sejarahMasjid');
    }

    public function carta()
    {
        return view('corporate.cartaOrganisasi');
    }

    public function direktori()
    {
        return view('corporate.direktoriKakitangan');
    }

    public function perutusan()
    {
        return view('corporate.perutusanImamBesar');
    }

    public function logo()
    {
        return view('corporate.logo');
    }
}
