<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   summary="Login UCP",
     *   description="Validasi username (UCP) dan password terhadap DB SA-MP (kolom Password_Bcrypt).",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"ucp","password"},
     *       @OA\Property(property="ucp", type="string", example="admin"),
     *       @OA\Property(property="password", type="string", example="admin123")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Login berhasil",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="Login berhasil"),
     *       @OA\Property(property="data", type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="username", type="string", example="admin")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Kredensial salah",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="UCP atau password salah")
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validasi gagal"
     *   )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'ucp'      => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('ucp');
        $password = $request->input('password');

        // Ambil akun dari DB
        $account = Account::where('Username', $username)->first();

        if (!$account) {
            return response()->json([
                'status'  => 'error',
                'message' => 'UCP atau password salah'
            ], 401);
        }
        
        // Hash password input user dengan salt tetap
        $salt = "78sdjs86d2h";
        $hashedInput = strtoupper(hash('sha256', $password . $salt));

        // Cek dengan yang ada di DB
        if ($hashedInput !== $account->Password) { // ganti sesuai nama kolom di DB
            return response()->json([
                'status'  => 'error',
                'message' => 'UCP atau password salah'
            ], 401);
        }
        
        // Buat payload JWT
        $payload = [
            'iss' => "samp-ucp",            // Issuer
            'sub' => $account->ID,          // Subject (user id)
            'ucp' => $account->Username,    // Bisa tambahin info lain
            'iat' => time(),                // Issued at
            'exp' => time() + 60*60         // Expired dalam 1 jam
        ];
        
        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        
        // Login berhasil
        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'data'    => [
                'id'       => $account->ID ?? null,
                'username' => $account->Username,
            ],
            'token' => $jwt
        ], 200);
    }
}
