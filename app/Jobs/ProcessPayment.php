<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Api\Payment;
use App\Models\Api\Dollar;
use App\Services\DollarService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payment = Payment::find($this->payment->id)->makeVisible('created_at');
        $date = $payment->created_at->format('Y-m-d');
        $dollarExists = Dollar::whereDate('date', $date)->exists();
        if(!$dollarExists)
        {
            // If the dollar value for the date does not exist in the dollars table,
            // I consult the Dollar Service
            DollarService::request();
        }

        $dollar = Dollar::whereDate('date', $date);
        if($dollar->exists())
        {
            $dollarFound = $dollar->first();

            // If you find the price of the dollar, update the payment
            $payment->update([
                'status' => PaymentStatus::paid,
                'clp_usd' => $dollarFound->value,
            ]);
        }
        else
        {
            // If you don't find the dollar price, raise an exception
            $message = 'The price of the dollar was not found for the day ' . $date . ' at https://mindicador.cl/api/dolar';
            $exception = new Exception($message);
            $this->fail($exception);
        }
    }
}
