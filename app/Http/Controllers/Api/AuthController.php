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
            $result = $this->authService->loginUser(
                $request->input('ucp'),
                $request->input('password')
            );

            if (!$result || !$result['status']) {
                return response()->json([
                    'message' => $result['message'] ?? 'Login gagal. Username atau kata sandi tidak sesuai.',
                ], $result['code'] ?? 401);
            }

            return response()->json([
                'message' => $result['message'] ?? 'Login berhasil.',
                'data' => $result['data'] ?? null,
                'token' => $result['token'] ?? null,
            ], $result['code'] ?? 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
