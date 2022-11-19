<?php

namespace App\Listeners;

use App\Events\PaymentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentCreated $event)
    {
        $payment = $event->payment;
        $client = $event->payment->client;
        $data['payment'] = $payment->toArray();
        $data['client'] = $payment->client->toArray();

        // send a notification of payment to the client by email
        Mail::send('emails.show', $data, function($message) use($client) {
            $message->to($client->email, $client->name . ' ' . $client->last_name)
                ->subject('Payment Notificacion');
            $message->from('app@laravel.com', 'Laravel App');
        });

    }
}
