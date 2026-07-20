<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_counter_service extends Model
{
    use HasFactory;

    public function counter()
    {
        return $this->belongsTo(tb_counter::class, 'counter_id')
        ->where('state', true)->select(['id', 'reference']);
    }

    public function service()
    {
        return $this->belongsTo(tb_service::class, 'service_id')
        ->where('state', true)->select(['id', 'name']);
    }

    protected $fillable = [
        'service_id',
        'counter_id',
        'state'
    ];
}
