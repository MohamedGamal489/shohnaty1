<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'weight' => 'required|string',
            'car' => 'required|string',
            'phone' => 'required|string',
            'date' => 'required|date',
            'note' => 'nullable|string',

            'load_street' => 'required|string',
            'load_city' => 'required|string',
            'load_region' => 'required|string',
            'load_state' => 'required|string',
            'load_building_number' => 'required|string',
            'load_lat' => 'required|string',
            'load_lng' => 'required|string',
            'delivery_street' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_region' => 'required|string',
            'delivery_state' => 'required|string',
            'delivery_building_number' => 'required|string',
            'delivery_lat' => 'required|string',
            'delivery_lng' => 'required|string',
            'price' => 'required|string',
        ];
    }
}
