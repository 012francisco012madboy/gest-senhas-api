<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\tb_service;

class tb_ticket extends Model
{
    use HasFactory;

    public function service()
    {
        return $this->belongsTo(tb_service::class, 'service_id')
        ->where('state', true)->select(['id', 'name']);
    }

    protected $fillable = [
        'reference',
        'service_id',
        'daily_id',
        'state',
        'priority'
    ];
}
