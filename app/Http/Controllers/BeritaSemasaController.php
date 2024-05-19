<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\BeritaSemasaModel;

class BeritaSemasaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beritaList = BeritaSemasaModel::all();
        $count = 1;
        return view('information.beritaSemasa',compact("beritaList","count"));
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
        $beritaList = BeritaSemasaModel::where('beritaID',$id)->get();
        $beritaCount = BeritaSemasaModel::where('beritaID',$id)->first()->berita_visitCount;
        $beritaCount++;

        DB::table('mm_berita_semasa')->where('beritaID',$id)->update(['berita_visitCount' => $beritaCount]);
        return view('information.beritaSemasaContent',compact("beritaList",));
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
