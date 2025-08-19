<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $table = 'cars';
    protected $primaryKey = 'carID';
    public $timestamps = false;

    protected $fillable = [
        'carOwner',
        'carModel',
        'carPosX',
        'carPosY',
        'carPosZ',
        'carColor1',
        'carColor2',
        'carPlate'
        // tambahin kolom lain kalau perlu
    ];

    public function character()
    {
        return $this->belongsTo(Character::class, 'carOwner', 'ID');
    }
}
