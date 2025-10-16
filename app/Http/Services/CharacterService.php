<?php

namespace App\Http\Services;

use App\Http\Repositories\AccountRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class CharacterService
{
    protected $accountRepo;

    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepo = $accountRepo;
    }

    public function bindCharacter(int $accountId, int $characterId)
    {
        // Cek apakah karakter sudah dibind ke akun lain
        $usedByOther = $this->accountRepo->findByBindCharacterId($characterId);
        if ($usedByOther) {
            throw new Exception('Karakter ini sudah dibind ke akun lain.');
        }

        // Cek apakah akun sudah punya binding aktif
        $account = $this->accountRepo->findById($accountId);
        if ($account && $account->BindCharacterID) {
            throw new Exception('Akun ini sudah memiliki karakter yang dibind.');
        }

        // Lakukan binding
        DB::beginTransaction();
        try {
            $this->accountRepo->updateBindCharacter($accountId, $characterId);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal melakukan binding karakter.');
        }
    }
}
