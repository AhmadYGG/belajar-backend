<?php

namespace App\Http\Repositories;

use App\Models\Account;

class AuthRepository
{
    public function findByUsername(string $username): ?Account
    {
        return Account::where('Username', $username)->first();
    }

    public function SelectUserById($id)
    {
        return Account::find($id);
    }

    public function SelectUserLogin(string $field)
    {
        return Account::where('Username', $field)->first();
    }
}
