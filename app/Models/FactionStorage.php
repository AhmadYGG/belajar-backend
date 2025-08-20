<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactionStorage extends Model
{
    use HasFactory;
    protected $table = 'factionstorage';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'itemID',
        'ID',
        'itemName',
        'itemQuantity'
    ];

    public function faction()
    {
        return $this->belongsTo(Faction::class, 'factionID', 'factionID');
    }
}
