<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\User\OrderResource;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::when($request->status, function ($q) use ($request) {
            return $q->where('status', $request->status);
        })->get();

        return response()->json([
            'status' => true,
            "message" => '',
            'data' => OrderResource::collection($orders)
        ], 200);
    }

    public function store(OrderRequest $request)
    {
        // Start the database transaction
        \DB::beginTransaction();

        try {
            // Validate the request data
            $data = $request->validated();
            $package_data = $request->only(['title', 'weight', 'car', 'phone', 'date', 'note']);
            $order_data = $request->except('title', 'weight', 'car', 'phone', 'date', 'note');
            // store package data
            $package = Package::create($package_data + ['user_id' => auth()->id()]);

            // store order data
            $order = Order::create($order_data + [
                    'package_id' => $package->id,
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                ]);
            // store order data
            // Commit the database transaction
            \DB::commit();
            return response()->json([
                'status' => true,
                "message" => 'تم ارسال الطلب بنجاح',
                'data' => OrderResource::make($order)
            ], 200);
        } catch (\Exception $e) {
            // An error occurred, rollback the database transaction
            \DB::rollBack();
            // Optionally log the error or handle it in another way
            return response()->json([
                'status' => true,
                "message" => 'حدث خطأ ما',
                'data' => ''
            ], 422);
        }
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json([
            'status' => true,
            "message" => '',
            'data' => OrderResource::make($order)
        ], 200);
    }


    public function delete($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status != 'pending'){
            return response()->json([
                'status' => false,
                "message" => 'لا يمكن حذف طلب الان',
                'data' => ''
            ], 200);
        }
        return response()->json([
            'status' => true,
            "message" => 'تم حذف الطلب بنجاح',
            'data' => ""
        ], 200);
    }
}
