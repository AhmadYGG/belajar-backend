<?php

namespace App\Http\Repositories;

use App\Models\Account;

class AccountRepository
{
    public function findByUsername(string $username): ?Account
    {
        return Account::where('Username', $username)->first();
    }
}
