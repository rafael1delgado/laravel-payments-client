<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'payment_date' => $this->payment_date->format('Y-m-d'),
            'expires_at' => $this->expires_at->format('Y-m-d'),
            'status' => $this->status,
            'clp_usd' => $this->clp_usd,
            'client_id' => $this->client_id,
        ];
    }
}
