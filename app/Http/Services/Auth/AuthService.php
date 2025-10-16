<?php

namespace App\Http\Services\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Repositories\AuthRepository;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    // public function loginUser(string $username, string $password): array
    // {
    //     $account = $this->accountRepository->findByUsername($username);

    //     if (!$account) {
    //         return [
    //             'status' => 'error',
    //             'message' => 'UCP atau password salah',
    //             'code' => 401,
    //         ];
    //     }

    //     $salt = "78sdjs86d2h";
    //     $hashedInput = strtoupper(hash('sha256', $password . $salt));

    //     if ($hashedInput !== $account->Password) {
    //         return [
    //             'status' => 'error',
    //             'message' => 'UCP atau password salah',
    //             'code' => 401,
    //         ];
    //     }

    //     $payload = [
    //         'iss' => 'samp-ucp',
    //         'sub' => $account->ID,
    //         'ucp' => $account->Username,
    //         'iat' => time(),
    //         'exp' => time() + 3600,
    //     ];

    //     $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

    //     return [
    //         'status' => 'success',
    //         'message' => 'Login berhasil',
    //         'data' => [
    //             'id' => $account->ID,
    //             'username' => $account->Username,
    //         ],
    //         'token' => $token,
    //         'code' => 200,
    //     ];
    // }

    public function generateAccessToken($user)
    {
        $accessToken = JWTAuth::customClaims([
            'accountId' => $user->ID,
            'username' => $user->Username,
        ])->fromUser($user);

        return $accessToken;
    }

    public function loginUser($data, &$user = null): mixed
    {
        $user = $this->authRepository->SelectUserLogin($data['ucp']);
        if ($user === null) {
            return false;
        }

        $salt = "78sdjs86d2h";
        $hashedInput = strtoupper(hash('sha256', $data['password'] . $salt));

        if (!$user || $hashedInput !== $user->Password) {
            return false;
        }

        return $this->generateAccessToken($user);
    }
}
