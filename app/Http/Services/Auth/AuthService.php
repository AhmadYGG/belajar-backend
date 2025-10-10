<?php

namespace App\Http\Services\Auth;

use Firebase\JWT\JWT;
use App\Http\Repositories\AccountRepository;

class AuthService
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function loginUser(string $username, string $password): array
    {
        $account = $this->accountRepository->findByUsername($username);

        if (!$account) {
            return [
                'status' => 'error',
                'message' => 'UCP atau password salah',
                'code' => 401,
            ];
        }

        $salt = "78sdjs86d2h";
        $hashedInput = strtoupper(hash('sha256', $password . $salt));

        if ($hashedInput !== $account->Password) {
            return [
                'status' => 'error',
                'message' => 'UCP atau password salah',
                'code' => 401,
            ];
        }

        $payload = [
            'iss' => 'samp-ucp',
            'sub' => $account->ID,
            'ucp' => $account->Username,
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return [
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'id' => $account->ID,
                'username' => $account->Username,
            ],
            'token' => $token,
            'code' => 200,
        ];
    }
}
