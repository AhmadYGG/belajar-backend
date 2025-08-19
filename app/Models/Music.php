<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;
    protected $table = 'saved_music'; 
    protected $primaryKey = 'id';   // penting! case-sensitive

    protected $fillable = [
        'name',
        'url'
    ];
}
