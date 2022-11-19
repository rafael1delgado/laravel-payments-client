<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Api\Client;
use App\Models\Api\Payment;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    /**
     * Gets payments from a
     * .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);
            return response()->json($client->payments, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
                'line' => $th->getLine(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Save a payment.
     *
     * @param  \App\Http\Requests\Api\StorePaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        try {
            $dataValidated = $request->validated();
            $payment = Payment::create($dataValidated);
            $payment = new PaymentResource($payment);

            return response()->json($payment, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
                'line' => $th->getLine(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
