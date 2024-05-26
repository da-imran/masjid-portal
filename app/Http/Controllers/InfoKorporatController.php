<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoKorporatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $request = Request();
        $visitorCount = app(IndexController::class)->recordVisitor($request);
        switch($id){
            case "perutusan-imam-besar": $viewPartial = "perutusanImamBesar";
            break;

            case "sejarah-masjid": $viewPartial = "sejarahMasjid";
            break;

            case "profil-korporat": $viewPartial = "profilKorporat";
            break;

            case "borang": $viewPartial = "borang";
            break;

            case "logo": $viewPartial = "logo";
            break;

            case "carta-organisasi": $viewPartial = "cartaOrganisasi";
            break;

            case "direktori-kakitangan": $viewPartial = "direktoriKakitangan";
            break;
        }

        return view('corporate.'.$viewPartial,compact("visitorCount"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
