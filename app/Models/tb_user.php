<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\tb_role;
use App\Models\tb_company_user;

class tb_user extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public function role()
    {
        return $this->belongsTo(tb_role::class, 'role_id')
        ->select(['id', 'name']);
    }

    protected $table = 'tb_users'; 

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'state',
        'role_id',
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
