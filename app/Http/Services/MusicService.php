<?php

namespace App\Http\Services;

use App\Http\Repositories\MusicRepository;

class MusicService
{
    protected MusicRepository $musicRepository;

    public function __construct(MusicRepository $musicRepository)
    {
        $this->musicRepository = $musicRepository;
    }

    public function listMusic()
    {
        // Kalau nanti mau ada logika tambahan (misalnya filter, sort, dsb)
        return $this->musicRepository->listMusic();
    }
}
