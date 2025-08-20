<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use Illuminate\Http\Request;

class FactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/factions",
     *     summary="Get All Factions",
     *     tags={"Factions"},
     *     @OA\Response(
     *         response=200,
     *         description="List of Factions",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="factionID", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Police Department"),
     *                     @OA\Property(property="description", type="string", example="Law enforcement faction")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $factions = Faction::all();

        return response()->json([
            'status' => 'success',
            'data' => $factions
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/factions/{factionID}/items",
     *     summary="Get Items from a Specific Faction",
     *     tags={"Factions"},
     *     @OA\Parameter(
     *         name="factionID",
     *         in="path",
     *         required=true,
     *         description="Faction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of Faction Items",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="ID", type="integer", example=10),
     *                     @OA\Property(property="itemName", type="string", example="M4A1 Rifle"),
     *                     @OA\Property(property="quantity", type="integer", example=50)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Faction not found"
     *     )
     * )
     */
    public function getItems($factionID)
    {
        $faction = Faction::with('storages')->find($factionID);

        if (!$faction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Faction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $faction->storages
        ]);
    }
}
