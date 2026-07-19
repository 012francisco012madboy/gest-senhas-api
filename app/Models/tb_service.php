<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_service extends Model
{
    use HasFactory;

    public function counterService()
    {
        return $this->hasMany(tb_counter_service::class, 'service_id')->where('state', true);
    }

    protected $fillable = [
        'name',
        'prefix',
        'state'
    ];
}
