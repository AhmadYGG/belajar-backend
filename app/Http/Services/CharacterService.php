<?php

namespace App\Http\Services;

use App\Http\Repositories\AccountRepository;
use App\Http\Repositories\CharacterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;

class CharacterService
{
    protected $accountRepo;
    protected $characterRepo;

    public function __construct(AccountRepository $accountRepo, CharacterRepository $characterRepo)
    {
        $this->accountRepo = $accountRepo;
        $this->characterRepo = $characterRepo;
    }

    protected function sendDiscordBindingNotification($account, $character)
    {
        $webhookUrl = 'https://discord.com/api/webhooks/1431120572133281792/n_MRApiTGC-ZIVJmovV2cjHwNXINcWlT8PrhL3xdusgAIvPNZBmY9dlYPjKpdoSNEIGX';

        $time = now('Asia/Jakarta')->format('Y-m-d H:i:s');

        $embed = [
            "title" => "🔗 Binding Karakter Berhasil",
            "color" => 0x2ecc71, // hijau lembut
            "fields" => [
                [
                    "name" => "Username",
                    "value" => "`{$account->Username}`",
                    "inline" => true
                ],
                [
                    "name" => "Account ID",
                    "value" => "`{$account->ID}`",
                    "inline" => true
                ],
                [
                    "name" => "Character",
                    "value" => "`{$character->Character}`",
                    "inline" => true
                ],
                [
                    "name" => "Character ID",
                    "value" => "`{$character->ID}`",
                    "inline" => true
                ],
                [
                    "name" => "Waktu",
                    "value" => "`{$time}`",
                    "inline" => false
                ],
            ],
            "footer" => [
                "text" => "Sistem Binding | " . config('app.name'),
                "icon_url" => "https://cdn-icons-png.flaticon.com/512/5968/5968756.png"
            ],
            // "timestamp" => now('Asia/Jakarta')->toIso8601String(),
        ];

        try {
            Http::post($webhookUrl, [
                'embeds' => [$embed],
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi Discord: ' . $e->getMessage());
        }
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
        if ($account && $account->BindCharacterID != -1) {
            throw new Exception('Akun ini sudah memiliki karakter yang dibind.');
        }

        // Lakukan binding
        DB::beginTransaction();
        try {
            $this->accountRepo->updateBindCharacter($accountId, $characterId);
            DB::commit();

            // ambil ulang data untuk notifikasi (lebih aman)
            $updatedAccount = $this->accountRepo->findById($accountId);
            $character = $this->characterRepo->findById($characterId);

            // kirim notifikasi (jika gagal, hanya di-log)
            $this->sendDiscordBindingNotification($updatedAccount, $character);

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal melakukan binding karakter: ' . $e->getMessage());
        }
    }
}
