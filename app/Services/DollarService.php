<?php

namespace App\Services;

use App\Models\Api\Dollar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class DollarService
{
    public static function request()
    {
        // send a request to the service my indicator
        $requestHttp = Http::withOptions(['verify' => false])->get('https://mindicador.cl/api/dolar');

        // obtains the array where the dollar values are with respect to the CLP currency
        $dollars = $requestHttp->object();

        foreach($dollars->serie as $dollar)
        {
            // get the date of the dollar
            $date = Carbon::parse($dollar->fecha);

            // finds if a record exists for the dollar date
            $dollarExists = Dollar::whereDate('date', $date->format('Y-m-d'))->exists();

            // if a record does not exist, I add it to the dollars table
            if(!$dollarExists)
            {
                Dollar::create([
                    'date' => $date->format('Y-m-d H:i:s'),
                    'value' => $dollar->valor,
                ]);
            }
        }
    }
}
