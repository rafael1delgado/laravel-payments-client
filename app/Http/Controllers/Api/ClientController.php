<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Api\Client;
use App\Models\Api\Dollar;
use App\Models\Api\Payment;
use App\Services\DollarService;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ClientController extends Controller
{
    /**
     * Gets the clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $payment = Payment::find(1)->makeVisible('created_at');

        $date = $payment->created_at->format('Y-m-d');
        $dollarExists = Dollar::whereDate('date', $date)->exists();
        return response()->json($dollarExists);

        if(!$dollarExists)
        {
            // If the dollar value for the date does not exist in the dollars table, I consult the Dollar Service
            DollarService::request();
        }

        $dollar = Dollar::whereDate('date', $date);
        if($dollar->exists())
        {
            $dollarFound = $dollar->first();

            $payment->update([
                'status' => PaymentStatus::paid,
                'clp_usd' => $dollarFound->value,
            ]);
        }

        try {
            $clients = Client::all();
            return response()->json($clients, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
                'line' => $th->getLine(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
