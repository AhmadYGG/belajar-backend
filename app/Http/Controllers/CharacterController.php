<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Account;
use App\Http\Requests\BindCharacterRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Services\CharacterService;
use Exception; // ✅ penting brok

class CharacterController extends Controller
{
    protected CharacterService $service;

    public function __construct(CharacterService $service)
    {
        $this->service = $service;
    }

    private function formatTimeRemaining(int $vipTime): string
    {
        // Jika nilainya timestamp UNIX (lebih besar dari waktu sekarang)
        if ($vipTime > time()) {
            $seconds = $vipTime - time();
        } else {
            // Kalau sudah hasil selisih, langsung pakai
            $seconds = $vipTime;
        }

        if ($seconds <= 0) {
            return 'Not Have';
        }

        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        if ($days > 0) {
            return sprintf('%d Hari %d Jam %d Menit', $days, $hours, $minutes);
        } elseif ($hours > 0) {
            return sprintf('%d Jam %d Menit', $hours, $minutes);
        } elseif ($minutes > 0) {
            return sprintf('%d Menit %d Detik', $minutes, $secs);
        } else {
            return sprintf('%d Detik', $secs);
        }
    }

    public function getByUsername()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate(); // ambil user dari token
            $username = JWTAuth::parseToken()->getPayload()->get('username');

            // Ambil bind character ID dari akun
            $account = Account::where('Username', $username)->first();
            $bindCharacterID = $account->BindCharacterID;

            // Jika user sudah bind character
            if ($bindCharacterID != -1 && $bindCharacterID != 0) {
                $character = Character::with(['bankAccount', 'cars', 'houses', 'factions'])
                    ->where('ID', $bindCharacterID)
                    ->first([
                        'ID', 'Character', 'Skin', 'Level', 'PlayingHours', 'Exp',
                        'Money', 'Faction', 'FactionRank', 'VIPTime'
                    ]);

                if (!$character) {
                    return response()->json([
                        'status' => 'error',
                        'data' => [
                            'bindCharacterID' => $account,
                        ],
                        'message' => 'Karakter yang sudah di-bind tidak ditemukan.',
                    ], 404);
                }

                // format data character tunggal
                $result = $this->formatCharacterData($character);

                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ], 200);
            }

            // Kalau belum bind character
            $characters = Character::where('Username', $username)
                ->with(['bankAccount', 'cars', 'houses', 'factions'])
                ->get([
                    'ID', 'Character', 'Skin', 'Level', 'PlayingHours', 'Exp',
                    'Money', 'Faction', 'FactionRank', 'VIPTime'
                ]);

            if ($characters->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Karakter tidak ditemukan'
                ], 200);
            }

            $result = $characters->map(function ($char) {
                return $this->formatCharacterData($char);
            });

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔹 Fungsi bantu untuk format data karakter biar gak ngulang2
     */
    private function formatCharacterData($char)
    {
        $factionName = $char->factions->factionName ?? 'Tidak Bergabung';
        $rankNumber = $char->FactionRank ?? 0;

        if ($char->factions) {
            $rankField = 'factionRank' . $rankNumber;
            $rankName = $char->factions->$rankField ?? 'Unknown Rank';
        } else {
            $rankName = 'Tidak Bergabung';
        }

        return [
            'ID' => $char->ID,
            'Character' => $char->Character,
            'Skin' => $char->Skin,
            'Level' => $char->Level,
            'PlayingHours' => $char->PlayingHours,
            'Exp' => $char->Exp,
            'Money' => $char->Money,
            'VIP' => $this->formatTimeRemaining($char->VIPTime),
            'Faction' => $factionName,
            'FactionRank' => $rankName,
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
                    'carPlate_Time1' => $car->carPlate_Time1,
                ];
            }),
            'Houses' => $char->houses->map(function ($house) {
                return [
                    'houseAddress' => $house->houseAddress,
                    'houseType' => match ($house->houseType) {
                        0 => 'Kecil',
                        1 => 'Sedang',
                        2 => 'Besar',
                        3 => 'Mansion Kecil',
                        4 => 'Mansion Besar',
                        default => 'Tidak Diketahui',
                    },
                    'housePrice' => $house->housePrice,
                ];
            }),
        ];
    }

    public function bindCharacter(BindCharacterRequest $request) // ✅ diperbaiki nama method
    {
        $accountId = JWTAuth::parseToken()->getPayload()->get('accountId');
        $characterId = $request->input('character_id');

        try {
            $this->service->bindCharacter($accountId, $characterId);

            return response()->json([
                'success' => true,
                'message' => 'Karakter berhasil dibind ke akun ini.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
