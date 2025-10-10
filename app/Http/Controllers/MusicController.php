<?php

namespace App\Http\Controllers;

use App\Http\Services\MusicService;
use Illuminate\Http\JsonResponse;

class MusicController extends Controller
{
    protected MusicService $musicService;

    public function __construct(MusicService $musicService)
    {
        $this->musicService = $musicService;
    }

    // mengambil semua data musik yang sudah ditambahkan
    public function listMusic(): JsonResponse
    {
        try {
            $musics = $this->musicService->listMusic();

            return response()->json([
                'status' => 'success',
                'data' => $musics
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data musik: ' . $e->getMessage(),
            ], 500);
        }
    }
}
