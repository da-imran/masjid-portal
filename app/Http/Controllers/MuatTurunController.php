<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MuatTurunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $request = new Request();
        $visitorCount = app(IndexController::class)->recordVisitor($request);

        switch($id){
            case "jadual-kuliah": $viewPartial = "jadualKuliah";
            break;

            case "nota-kuliah": $viewPartial = "notaKuliah";
            break;

            case "borang": $viewPartial = "borang";
            break;
        }
        
        return view('download.'.$viewPartial,compact("visitorCount"));
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
