<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\DriverCar;
use App\Models\User;
use Illuminate\Http\Request;

class AuthDriverController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'identity_number' => 'required|integer|digits:14|unique:users,identity_number',
        ]);
        // Create and Save the driver to the database
        $user = User::create($data + [
                'user_type' => 'driver',
            ]);
        // Return a response indicating success

        return response()->json([
            'status' => true,
            "message" => ' تم تسجيل السائق بنجاح برجاء اكمال التسجيل',
            'data' => [
                'user' => UserResource::make($user)
            ]
        ], 200);
    }

    public function completeRegistration(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'plate_number' => 'required|string',
            'car_type' => 'required|string',
            'driving_license' => 'nullable|image',
            'car_license' => 'nullable|image',
            'accessories' => 'required|array',
            'accessories.*' => 'required',
        ]);

        // Create and Save the driver car to the database
        $driverCar = DriverCar::create([
            'driver_id' => $data['driver_id'],
            'plate_number' => $data['plate_number'],
            'car_type' => $data['car_type'],
        ]);

        // Upload and store the driving_license if provided
        if ($request->hasFile('driving_license')) {
            $driving_license = $request->file('driving_license');
            $driving_license_name = \Str::random(5) . '_' . time() . '.' . $driving_license->getClientOriginalExtension();
            $driving_license->move(public_path('uploads'), $driving_license_name);
            $driverCar->driving_license = $driving_license_name;
            $driverCar->save();
        }


        // Upload and store the car_license if provided
        if ($request->hasFile('car_license')) {
            $car_license = $request->file('car_license');
            $car_license_name = \Str::random(5) . '_' . time() . '.' . $car_license->getClientOriginalExtension();
            $car_license->move(public_path('uploads'), $car_license_name);
            $driverCar->car_license = $car_license_name;
            $driverCar->save();
        }
        foreach ($data['accessories'] as $key => $accessory) {
            $driverCar->accessories()->create([
                'accessory' => $accessory
            ]);
        }

        return response()->json([
            'status' => true,
            "message" => ' تم تسجيل السائق بنجاح',
            'data' => ""
        ], 200);
    }

    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'phone' => 'required|string|max:15',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (\Auth::attempt([
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
            'user_type' => 'driver',
        ])) {
            // Generate a new Sanctum token for the authenticated user
            $user = auth()->user();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'status' => true,
                "message" => 'تم الدخول بنجاح',
                'data' => [
                    'token' => $token,
                    'user' => UserResource::make($user)
                ]
            ], 200);
        }

        // Authentication failed
        return response()->json([
            'status' => true,
            "message" => 'تأكد من البيانات المدخلة',
            'data' => ""
        ], 401);
    }

    public function resetPassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'phone' => 'required|string|max:15',
        ]);

        $user = User::firstWhere('phone', $request->phone);
        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => 'لم يتم العثور على هذا الرقم',
                'data' => ""
            ], 404);
        }
        $new_password = rand(100000, 999999);
        $user->update([
            'password' => bcrypt($new_password)
        ]);

        return response()->json([
            'status' => true,
            "message" => 'تم ارسال الرقم السري الجديد في رسالة نصية',
            'data' => ['new_password' => $new_password]
        ], 200);

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            "message" => 'تم تسجيل الخروج بنجاح',
            'data' => ""
        ], 200);
    }
}
