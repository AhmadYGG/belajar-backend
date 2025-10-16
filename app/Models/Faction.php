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
        'factionType',
        'factionRank1',
        'factionRank2',
        'factionRank3',
        'factionRank4',
        'factionRank5',
        'factionRank6',
        'factionRank7',
        'factionRank8',
        'factionRank9',
        'factionRank10',
        'factionRank11',
        'factionRank12',
        'factionRank13',
        'factionRank14',
        'factionRank15',
    ];

    public function storages()
    {
        return $this->hasMany(FactionStorage::class, 'ID', 'factionID');
    }

    public function characters()
    {
        return $this->hasMany(Character::class, 'Faction', 'factionID');
    }
}
