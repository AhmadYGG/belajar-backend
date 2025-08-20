<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FactionStorage;

class FactionStorageController extends Controller
{
    public function getFactionStorageItem($factionid)
    {
        $character = FactionStorage::where('factionID', $factionid)
        ->with(['factionItem'])
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