<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class APIController extends Controller
{
    public function ApiSolat()
    {
        $response = Http::get('https://api.waktusolat.app/v2/solat/PNG01');
        $fileName = 'api_data/waktusolat_' . date('m') . '.json';

        $dataTime = $response->json();
        if($response->failed()){
          \Log::error('Failed to retrieve prayer times: ' . $response->statusText());
        }    
        
        foreach ($dataTime["prayers"] as &$day) {
          foreach ($day as $prayer => $timestamp) {
            if (in_array($prayer, ["syuruk", "fajr", "dhuhr", "asr", "maghrib", "isha"])) {
              $day[$prayer] = Carbon::createFromTimestamp($timestamp)->format('h:i A');
            }
          }
        }
        $currentDay = Carbon::now()->format('d') - 1;
        if(!file_exists($fileName)){
          $jsonData = json_encode($dataTime["prayers"][$currentDay]);
          file_put_contents( $fileName, $jsonData);
        }

        $waktuSolat = $dataTime["prayers"][$currentDay];
        return $waktuSolat;
    }
}
