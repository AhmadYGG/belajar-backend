<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    use HasFactory;
    protected $table = 'factions'; 
    protected $primaryKey = 'factionID';   // penting! case-sensitive

    protected $fillable = [
        'factionName',
        'factionType'
    ];

    public function storages()
    {
        return $this->hasMany(FactionStorage::class, 'ID', 'factionID');
    }
}
