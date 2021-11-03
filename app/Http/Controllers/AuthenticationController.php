<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\ChangePasswordRequest;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\LogoutRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if ($user) {
            if (Hash::check($validatedData['password'], $user->password)) {
                $token = $user->createToken('AccessToken');
                return response()->json([
                    'token' => $token->plainTextToken, 'role' => $user->role,
                    'ids' => [
                        'user' => $user->id,
                        'critique' => $user->critique ? $user->critique->id : null
                    ]
                ]);
            }
        }

        return response()->json(['message' => 'Invalid Credentials'], 401);
    }

    public function logout(LogoutRequest $request)
    {
        $authenticatedUser = Auth::user();
        $authenticatedUser->tokens()->delete();

        return response()->json(['message' => 'User logged out'], 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $validatedData = $request->validated();
        $authenticatedUser = Auth::user();

        if (Hash::check($validatedData['old_password'], $authenticatedUser->password)) {
            $authenticatedUser->update(['password' => $validatedData['new_password']]);

            return response()->json(['message' => 'Password Changed Successfuly'], 200);
        }

        return response()->json(['message' => 'Old Password Incorrect'], 401);
    }
}
