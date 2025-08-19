<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;

class MusicController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/music",
     *     summary="Get All Public Music",
     *     tags={"Music"},
     *     @OA\Response(
     *         response=200,
     *         description="List of Saved Music",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Song 1"),
     *                     @OA\Property(property="url", type="string", example="https://example.com/song1.mp3")
     *                 )
     *             )
     *         )
     *     )
     * )
     */


    public function getAllSavedMusic()
    {
        $musics = Music::all();

        return response()->json([
            'status' => 'success',
            'data' => $musics
        ]);
    }
}
