<?php

namespace App\Http\Resources\Driver;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => Carbon::parse($this->package?->date)->format('Y-m-d'),
            'time' => Carbon::parse($this->package?->date)->format('h:i a'),
            'price' => $this->price,
            'payment_method' => __('lang.' . $this->payment_method),
        ];
    }
}
