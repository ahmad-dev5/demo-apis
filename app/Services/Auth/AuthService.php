<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\ErrorLog;
use App\Mail\SendOtpMail;
use App\Models\ActivityLog;

use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\SignupRequest;

class AuthService
{
    public function signup(array $data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'age' => $data['age'],
                'gender' => $data['gender'],
                'country_id' => $data['country_id'],
                'password' => Hash::make($data['password']),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'activity' => 'User registered successfully',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            ErrorLog::create([
                'action' => 'Signup',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Signup failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function login(array $data)
{
    try {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $tempToken = JWTAuth::fromUser($user);

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new SendOtpMail($otp));

        ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'OTP sent to email',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email',
            'temp_token' => $tempToken,
        ]);
    } catch (\Exception $e) {
        ErrorLog::create([
            'action' => 'Login',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Login failed',
            'error' => $e->getMessage()
        ], 400);
    }
}

public function verifyOtp(array $data)
{
    try {
        // ✅ Get token from Authorization header
        $token = request()->bearerToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Authorization token missing',
            ];
        }

        // ✅ Get user from token
        $user = JWTAuth::setToken($token)->toUser();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid token',
            ];
        }

        // ✅ Verify OTP
        if ($user->otp_code != $data['otp']) {
            return [
                'success' => false,
                'message' => 'Invalid OTP',
            ];
        }

        if ($user->otp_expires_at < Carbon::now()) {
            return [
                'success' => false,
                'message' => 'OTP has expired',
            ];
        }

        // ✅ Generate new final token
        $newToken = JWTAuth::fromUser($user);

        // ✅ Clear OTP
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // ✅ Activity log
        ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'User Logged in Successfully and otp verified',
        ]);

        return [
            'success' => true,
            'message' => 'User Logged in Successfully and otp verified',
            'token' => $newToken,
        ];

    } catch (\Exception $e) {
        ErrorLog::create([
            'action' => 'OTP Verification',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return [
            'success' => false,
            'message' => 'OTP verification failed',
            'error' => $e->getMessage(),
        ];
    }
}


}
