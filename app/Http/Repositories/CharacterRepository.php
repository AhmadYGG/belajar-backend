<?php

namespace App\Http\Repositories;

use App\Models\Character;

class CharacterRepository
{
    /**
     * Ambil karakter berdasarkan ID.
     */
    public function findById(int $id): ?Character
    {
        return Character::find($id);
    }

    /**
     * Ambil semua karakter (opsional, bisa dipakai buat debugging / listing)
     */
    public function all()
    {
        return Character::all();
    }

    /**
     * Cari karakter berdasarkan nama.
     */
    public function findByName(string $name): ?Character
    {
        return Character::where('Name', $name)->first();
    }

    /**
     * Update data karakter (misalnya kalau mau ubah status binding atau lainnya)
     */
    public function update(int $id, array $data): bool
    {
        return Character::where('ID', $id)->update($data) > 0;
    }
}
