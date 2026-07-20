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

    public function current_counter()
    {
        $day = now()->format('Y-m-d');

        return $this->hasOne(tb_counter_user::class, 'user_id')
            ->where('state', true)
            ->whereHas('daily', function ($query) use ($day) {
                $query->where('day', $day);
            });
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
