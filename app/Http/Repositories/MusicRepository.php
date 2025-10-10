<?php

namespace App\Http\Repositories;

use App\Models\Music;

class MusicRepository
{
    public function listMusic()
    {
        return Music::all();
    }
}
