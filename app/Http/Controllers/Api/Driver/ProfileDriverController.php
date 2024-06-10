<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\UserResource;
use Illuminate\Http\Request;

class ProfileDriverController extends Controller
{
    public function show()
    {
        return response()->json([
            'status' => true,
            "message" => '',
            'data' => UserResource::make(auth()->user())
        ], 200);
    }

    public function update(Request $request)
    {
        auth()->user()->update($request->all());
        return response()->json([
            'status' => true,
            "message" => 'تم التحديث بنجاح',
            'data' => UserResource::make(auth()->user())
        ], 200);
    }

    public function updatePicture(Request $request)
    {
        // Validate the incoming request (e.g., check for file type, size, etc.)
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        $profilePicture = $request->file('image');
        $fileName = time() . '_' . \Str::random(15) . '.' . $profilePicture->getClientOriginalExtension();
        $profilePicture->move(public_path('images'), $fileName);

        // Update user's profile picture field in the database
        auth()->user()->update(['image' => $fileName]);

        return response()->json([
            'status' => true,
            "message" => 'تم التحديث بنجاح',
            'data' => UserResource::make(auth()->user())
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        // Validate the incoming request
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        // Check if the provided old password matches the current password
        if (!\Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => true,
                "message" => 'كلمة المرور الحالية غير صحيحة',
                'data' => ""
            ], 401);
        }

        // Update user's password in the database
        $user->update([
            'password' => \Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => true,
            "message" => 'تم التحديث بنجاح',
            'data' => UserResource::make(auth()->user())
        ], 200);
    }

    public function deleteAccount()
    {
        auth()->user()->delete();
        return response()->json([
            'status' => true,
            "message" => 'تم حذف الحساب بنجاح',
            'data' => ""
        ], 200);
    }
}
