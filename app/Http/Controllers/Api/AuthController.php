<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Services\Auth\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function loginUser(LoginRequest $request)
    {
        try {
            $user = null;
            $token = $this->authService->loginUser($request->validated(), $user);

            if (!$token) {
                return response()->json([
                    'message' => 'Login gagal. Username atau kata sandi tidak sesuai'
                ], 500);
            }

            return response()->json([
                'message' => 'Selamat datang, Anda berhasil login.',
                'token' => $token ?? null,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
