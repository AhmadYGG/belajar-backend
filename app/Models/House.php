<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
    protected $table = 'houses';
    protected $primaryKey = 'houseID';
    public $timestamps = false;

    protected $fillable = [
        'houseAddress',
        'houseType',
        'housePrice',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class, 'houseOwner', 'ID');
    }
}
