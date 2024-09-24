<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class APIController extends Controller
{
    public function ApiSolat()
    {
        $response = Http::get('https://api.waktusolat.app/v2/solat/PNG01');
        if($response->failed()){
          Log::error('Failed to retrieve prayer times: ' . $response->status());
          return response()->json(['error' => 'Failed to retrieve prayer times.'], 500);
        }
        $fileName = 'api_data/waktusolat_' . date('m') . '.json';
        
        $dataTime = $response->json();
        file_put_contents( $fileName, json_encode($dataTime["prayers"]));

        
        
        foreach ($dataTime["prayers"] as &$day) {
          foreach ($day as $prayer => $timestamp) {
            if (in_array($prayer, ["syuruk", "fajr", "dhuhr", "asr", "maghrib", "isha"])) {
              $day[$prayer] = Carbon::createFromTimestamp($timestamp)->format('h:i A');
            }
          }
        }

        /* Order of prayer time data
            0 - asar
            1 - fajr / subuh
            2 - syuruk
            3 - maghrib
            4 - zuhur
            5 - hijri (date)
            6 - isha
            7 - day
        */
        $currentDay = Carbon::now()->format('d') - 1;
        $data = json_decode(file_get_contents($fileName),true);
        $waktuSolat = [];
        if(is_null($dataTime)){
            $data[$currentDay]['asr'] = Carbon::createFromTimestamp($data[$currentDay]['asr'])->format('h:i A');
            $data[$currentDay]['fajr'] = Carbon::createFromTimestamp($data[$currentDay]['fajr'])->format('h:i A');
            $data[$currentDay]['syuruk'] = Carbon::createFromTimestamp($data[$currentDay]['syuruk'])->format('h:i A');
            $data[$currentDay]['maghrib'] = Carbon::createFromTimestamp($data[$currentDay]['maghrib'])->format('h:i A');
            $data[$currentDay]['dhuhr'] = Carbon::createFromTimestamp($data[$currentDay]['dhuhr'])->format('h:i A');
            $data[$currentDay]['isha'] = Carbon::createFromTimestamp($data[$currentDay]['isha'])->format('h:i A');
            $waktuSolat = $data[$currentDay];
          }
        else{
          $waktuSolat = $dataTime["prayers"][$currentDay];
        }
        //return $waktuSolat;
        return response()->json(['data' => $waktuSolat], 200);
    }
}
