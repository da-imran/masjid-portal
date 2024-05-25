<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KutipanMasjidModel;

class KutipanMasjidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kutipanList = KutipanMasjidModel::all();
        $count = 1;

        $dayName = [];
        $monthName = [];

        foreach($kutipanList as $lst){
            switch($lst->kutipanDay){
                case 1: 
                    $dayName[$lst->kutipanID] = 'Isnin';
                    break;
                case 2:
                    $dayName[$lst->kutipanID] = 'Selasa';
                    break;
                case 3:
                    $dayName[$lst->kutipanID] = 'Rabu';
                    break;
                case 4:
                    $dayName[$lst->kutipanID] = 'Khamis';
                    break;
                case 5:
                    $dayName[$lst->kutipanID] = 'Jumaat';
                    break;
                case 6:
                    $dayName[$lst->kutipanID] = 'Sabtu';
                    break;
                case 7:
                    $dayName[$lst->kutipanID] = 'Ahad';
                    break;
            }

            switch($lst->kutipanMonth){
                case 1: 
                    $monthName[$lst->kutipanID] = 'Januari';
                    break;
                case 2:
                    $monthName[$lst->kutipanID] = 'Februari';
                    break;
                case 3:
                    $monthName[$lst->kutipanID] = 'Mac';
                    break;
                case 4:
                    $monthName[$lst->kutipanID] = 'April';
                    break;
                case 5:
                    $monthName[$lst->kutipanID] = 'Mei';
                    break;
                case 6:
                    $monthName[$lst->kutipanID] = 'Jun';
                    break;
                case 7:
                    $monthName[$lst->kutipanID] = 'Julai';
                    break;
                case 8:
                    $monthName[$lst->kutipanID] = 'Ogos';
                    break;
                case 9:
                    $monthName[$lst->kutipanID] = 'September';
                    break;
                case 10:
                    $monthName[$lst->kutipanID] = 'Oktober';
                    break;
                case 11:
                    $monthName[$lst->kutipanID] = 'November';
                    break;
                case 12:
                    $monthName[$lst->kutipanID] = 'Disember';
                    break;
            }
        }

        return view('information/kutipanTabungMasjid',compact("kutipanList","dayName","monthName","count"));
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
