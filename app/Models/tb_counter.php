<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_counter extends Model
{
    use HasFactory;

    public function counterService()
    {
        return $this->hasMany(tb_counter_service::class, 'counter_id')->where('state', true);
    }

    public function current_counter()
    {
        $day = now()->format('Y-m-d');

        return $this->hasOne(tb_counter_user::class, 'counter_id')
            ->where('state', true)
            ->whereHas('daily', function ($query) use ($day) {
                $query->where('day', $day);
            });
    }

    protected $fillable = [
        'reference',
        'state'
    ];
}
