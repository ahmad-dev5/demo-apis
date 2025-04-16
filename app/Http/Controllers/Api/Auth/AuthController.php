<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function signup(SignupRequest $request)
    {
        return $this->authService->signup($request->all());
    }

    public function login(LoginRequest $request)
{
    return $this->authService->login($request->validated());
}

public function verifyOtp(VerifyOtpRequest $request)
    {
        
        $result = $this->authService->verifyOtp($request->validated());
        return response()->json($result);
    }

}
