<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\OrderResource;
use App\Http\Resources\Driver\WalletResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderDriverController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('status', 'pending')->whereNull('driver_id')->get();
        return response()->json([
            'status' => true,
            "message" => '',
            'data' => OrderResource::collection($orders)
        ], 200);
    }

    public function getOrders(Request $request)
    {
        $orders = Order::when($request->status, function ($q) use ($request) {
            return $q->where('status', $request->status);
        })->where('driver_id',auth()->id())->get();

        return response()->json([
            'status' => true,
            "message" => '',
            'data' => OrderResource::collection($orders)
        ], 200);
    }

    public function assignOrder($order_id)
    {
        $order = Order::find($order_id);
        if ($order->driver_id != null)
            return response()->json([
                'status' => false,
                "message" => 'تم تعيين الطلب لسائق اخر',
                'data' => ""
            ], 200);

        $order->update([
            'driver_id' => auth()->id(),
            'status' => 'in_progress'
        ]);
        return response()->json([
            'status' => true,
            "message" => 'تم قبول الطلب بنجاح',
            'data' => ""
        ], 200);
    }

    public function completeOrder($order_id)
    {
        $order = Order::find($order_id);

        $array = ['cash','vodafone'];
        // Get a random key from the array
        $randomKey = array_rand($array);
        // Get the value corresponding to the random key
        $randomValue = $array[$randomKey];

        $order->update([
            'status' => 'completed',
            'payment_method' => $randomValue,
        ]);
        return response()->json([
            'status' => true,
            "message" => 'تم تسليم الطلب بنجاح',
            'data' => ""
        ], 200);
    }


    public function getWalletBalance()
    {
        $orders = Order::where([
            'driver_id' => auth()->id(),
            'status' => 'completed'
        ])->get();

        return response()->json([
            'status' => true,
            "message" => '',
            'data' => WalletResource::collection($orders)
        ], 200);
    }
}
