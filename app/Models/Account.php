<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'accounts'; // tabel yang dipakai
    protected $primaryKey = 'ID';  // sesuaikan dengan primary key di DB

    public $timestamps = false;

    protected $fillable = [
        'ID',
        'Username',
        'Password',
    ];

    protected $hidden = [
        'Password', // supaya password ga ikut di-return di response JSON
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
