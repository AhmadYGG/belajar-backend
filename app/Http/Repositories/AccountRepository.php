<?php

namespace App\Http\Repositories;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountRepository
{
    public function findByUsername(string $username): ?Account
    {
        return Account::where('Username', $username)->first();
    }

    public function findById(int $accountId)
    {
        return DB::table('accounts')->where('ID', $accountId)->first();
    }

    public function findByBindCharacterId(int $characterId)
    {
        return DB::table('accounts')->where('BindCharacterID', $characterId)->first();
    }

    public function updateBindCharacter(int $accountId, int $characterId): bool
    {
        return DB::table('accounts')
            ->where('ID', $accountId)
            ->update(['BindCharacterID' => $characterId]) > 0;
    }
}
