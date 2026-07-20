<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_counter_user extends Model
{
    use HasFactory;

    public function daily() {
        return $this->belongsTo(tb_daily::class, 'daily_id');
    }

    public function counter()
    {
        return $this->belongsTo(tb_counter::class, 'counter_id')
        ->where('state', true)->select(['id', 'reference']);
    }

    protected $fillable = [
        'user_id',
        'counter_id',
        'daily_id',
        'state'
    ];
}
