<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use Illuminate\Http\Request;

class FactionController extends Controller
{
    public function index()
    {
        $factions = Faction::all();

        return response()->json([
            'status' => 'success',
            'data' => $factions
        ]);
    }

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
