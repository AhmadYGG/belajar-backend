<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/characters/{username}",
     *     summary="Get character by UCP username",
     *     tags={"Character"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="username",
     *         in="path",
     *         required=true,
     *         description="UCP Username",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Character data",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="Username", type="string", example="Aziz"),
     *                 @OA\Property(property="CharName", type="string", example="Aziz_Martinez"),
     *                 @OA\Property(property="Level", type="integer", example=5),
     *                 @OA\Property(property="Money", type="integer", example=2500),
     *                 @OA\Property(property="Job", type="string", example="Trucker")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Character not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Character not found")
     *         )
     *     )
     * )
     */

    public function getByUsername($username)
    {
        $character = Character::where('Username', $username)
        ->with(['bankAccount', 'cars'])
        ->get(['ID', 'Character', 'Level', 'PlayingHours', 'Exp', 'Money', 'Faction', 'FactionRank']);

        if (!$character) {
            return response()->json([
                'status' => 'error',
                'message' => 'Character not found'
            ], 404);
        }

        $result = $character->map(function ($char) {
            return [
                'ID' => $char->ID,
                'Character' => $char->Character,
                'Level' => $char->Level,
                'PlayingHours' => $char->PlayingHours,
                'Exp' => $char->Exp,
                'Money' => $char->Money,
                'Faction' => $char->Faction,
                'FactionRank' => $char->FactionRank,
                'BankAccount' => $char->bankAccount->map(function ($bank) {
                    return [
                        'AccNumber' => $bank->AccNumber,
                        'AccName' => $bank->AccName,
                        'Balance' => $bank->Balance
                    ];
                }),
                'Cars' => $char->cars->map(function ($car) {
                    return [
                        'carModel' => $car->carModel,
                        'carPlate' => $car->carPlate,
                        'carPlate_Time1' => $car->carPlate_Time1
                    ];
                })
            ];
        });
    
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }
}