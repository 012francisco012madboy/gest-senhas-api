<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_ticket_history extends Model
{
    use HasFactory;

    public function service()
    {
        return $this->belongsTo(tb_service::class, 'service_id')
        ->where('state', true)->select('id', 'name');
    }

    protected $fillable = [
        'ticket_id',
        'counter_id',
        'state'
    ];
}
