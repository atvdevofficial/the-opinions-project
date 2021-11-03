<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        // Password Reset Request
        if (request()->isMethod('POST')) {
            $validatedData = $request->validated();

            $token = preg_replace("/[^A-Za-z0-9 ]/", '', bcrypt(now() . $validatedData['email']));
            $passwordReset = DB::table('password_resets')->insert([
                'email' => $validatedData['email'],
                'token' => $token
            ]);

            Mail::send('emails.resetPassword', ['token' => $token], function ($message) use ($validatedData) {
                $message->to($validatedData['email'])->subject('Reset Password Request - The Opinions Project');
            });

            return response()->json(['message' => 'An email was sent to your address.']);
        }

        // Actual Password Reset
        if (request()->isMethod('PUT')) {
            $validatedData = $request->validated();
            $passwordReset = DB::table('password_resets')->whereToken($validatedData['token'])->first();

            if ($passwordReset) {
                $user = User::whereEmail($passwordReset->email)->first();
                $user->update(['password' => $validatedData['password']]);

                // Delete token
                DB::table('password_resets')->whereToken($validatedData['token'])->delete();
                return response()->json(['message' => 'Password Reset Successful']);
            }

            return response()->json(['message' => 'Invalid validation token'], 422);
        }
    }
}
