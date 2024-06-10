<?php

namespace App\Http\Resources\Driver;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'title' => $this->package?->title,
            'weight' => $this->package?->weight,
            'car' => $this->package?->car,
            'phone' => $this->package?->phone,
            'date' => Carbon::parse($this->package?->date)->format('Y-m-d'),
            'time' => Carbon::parse($this->package?->date)->format('h:i a'),
            'note' => $this->package?->note,
            'status' => __('lang.' . $this->status),
            'load_street' => $this->load_street,
            'load_city' => $this->load_city,
            'load_region' => $this->load_region,
            'load_state' => $this->load_state,
            'load_building_number' => $this->load_building_number,
            'load_lat' => $this->load_lat,
            'load_lng' => $this->load_lng,
            'delivery_street' => $this->delivery_street,
            'delivery_city' => $this->delivery_city,
            'delivery_region' => $this->delivery_region,
            'delivery_state' => $this->delivery_state,
            'delivery_building_number' => $this->delivery_building_number,
            'delivery_lat' => $this->delivery_lat,
            'delivery_lng' => $this->delivery_lng,
            'price' => $this->price,
            'user' => [
                'name' => $this->user->name,
                'phone' => $this->user->phone,
                'image' => $this->image ? asset('images/' . $this->image) : asset('images/user.png'),
            ]
        ];
    }
}
