<?php

namespace App\Observers\Api;

use App\Enums\PaymentStatus;
use App\Events\PaymentCreated;
use App\Jobs\ProcessPayment;
use App\Models\Api\Payment;
use Illuminate\Support\Str;

class PaymentObserver
{
    /**
     * Handle the Payment "creating" event.
     *
     * @param  \App\Models\Api\Payment  $payment
     * @return void
     */
    public function creating(Payment $payment)
    {
        // add the uuid
        $payment->uuid = Str::uuid();

        // add pending status as default
        $payment->status = PaymentStatus::pending;
    }

    /**
     * Handle the Payment "created" event.
     *
     * @param  \App\Models\Api\Payment  $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        // add the payment to the "payment" queue
        ProcessPayment::dispatch($payment)->onQueue('payment');

        // upload an event for payment
        event(new PaymentCreated($payment));
    }
}
