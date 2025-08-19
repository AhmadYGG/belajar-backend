<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    protected $table = 'bank_accounts';
    protected $primaryKey = 'id';
    public $timestamps = false; // kalau tabel ga pake updated_at, created_at default Laravel
    
    protected $fillable = [
        'OwnerID',
        'AccNumber',
        'AccName',
        'Balance'
    ];
    
    public function character()
    {
        return $this->belongsTo(Character::class, 'OwnerID', 'ID');
    }
}
