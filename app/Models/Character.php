<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $table = 'characters';
    protected $primaryKey = 'ID';   // penting! case-sensitive
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Username',
        'Character',
        'Gender',
        'Birthdate',
        'Money',
        'Faction',
        'VIPTime'
    ];

    public function bankAccount()
    {
        // relasi:  characters.ID <-> bank_accounts.OwnerID
        return $this->hasMany(BankAccount::class, 'OwnerID', 'ID');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'carOwner', 'ID');
    }

    public function houses()
    {
        return $this->hasMany(House::class, 'houseOwner', 'ID');
    }

    public function factions()
    {
        return $this->hasOne(Faction::class, 'factionID', 'Faction');
    }
}
