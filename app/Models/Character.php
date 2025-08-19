<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $table = 'characters'; // nama tabel
    protected $primaryKey = 'id';   // ubah sesuai PK

    protected $fillable = [
        'Username',
        'Character',
        'Gender',
        'Birthdate',
        'Money',
        'Faction'
    ];
}