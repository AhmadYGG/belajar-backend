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
        'Faction'
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
}