<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'identity_number' => $this->identity_number ?? "30210201207353",
            'image' => $this->image ? asset('images/' . $this->image) : asset('images/user.png'),
            'order_count' => $this->driver_orders->count(),
            'rates' => rand(1.0,5.0),
        ];
    }
}
