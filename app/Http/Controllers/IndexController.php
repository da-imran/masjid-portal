<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

use App\Models\BeritaSemasaModel;
use App\Models\PengumumanModel;
use App\Models\VisitorModel;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countB = 1;
        $request = new Request();
        $beritaList = BeritaSemasaModel::all();
        $pengumumanList = PengumumanModel::all();
        $visitorCount = app(IndexController::class)->recordVisitor($request);
        
        return view('index.index',compact("beritaList","pengumumanList","countB","visitorCount"));
    }

    public function recordVisitor(Request $request){
        $userIp = $request->ip();
        $cookie = Cookie::make('visitorCount', $userIp,24*60);
        $visitor = VisitorModel::where('visitorIP', $userIp)->first();
        
        $today = Carbon::now();
        $dailyCounts = DB::table('mm_visitor')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereDate('created_at', '=', $today->format('Y-m-d'))
            ->groupBy('date')
            ->get();

        $monthlyCounts = DB::table('mm_visitor')
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->whereMonth('created_at', '=', $today->month)
            ->whereYear('created_at', '=', $today->year)
            ->groupBy('year', 'month')
            ->get();

        $dailyCounts =  $dailyCounts[0]->count;
        $monthlyCounts = $monthlyCounts[0]->count;
        $overallCount = DB::table('mm_visitor')->count();
        
        if(!$visitor){
            $visitor = new VisitorModel();
            $visitor->visitorIP = $userIp;
            $visitor->visitorCount = 1;
            $visitor->visitorDuration = $cookie->getExpiresTime();
            $visitor->save();
        }
        else {
            if(Carbon::now() <= Carbon::createFromTimestamp($visitor->visitorDuration)->format('d-m-y h:i'))
            {
                $visitor->visitorCount;
            }
            else {
                $visitor->visitorCount++;
                $visitor->save();
            }
        } 
        return response()->json(['dailyCount' => $dailyCounts, 'monthlyCount' =>  $monthlyCounts, 'overallCount' => $overallCount]);
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
