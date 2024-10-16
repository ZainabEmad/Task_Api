<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserProfileController extends Controller
{
    //
    public function user_profile() {
        $user = auth()->user();
        if ($user) {
            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
    // ################################################

    public function update_user_profile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $user = User::findOrFail($id);
            if (Auth::id() !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->save();

            return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
        } catch (\Exception $e) {
            Log::error('Failed to update user profile: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }
}