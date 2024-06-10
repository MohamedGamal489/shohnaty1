<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);
        // Create and Save the user to the database
        User::create($data);
        // Return a response indicating success

        return response()->json([
            'status' => true,
            "message" => 'تم التسجيل بنجاح',
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
        if (\Auth::attempt(['phone' => $request->input('phone'), 'password' => $request->input('password')])) {
            // Authentication passed

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

        $user = User::firstWhere('phone',$request->phone);
        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => 'لم يتم العثور على هذا الرقم',
                'data' => ""
            ], 404);
        }
        $new_password = rand(100000,999999);
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
