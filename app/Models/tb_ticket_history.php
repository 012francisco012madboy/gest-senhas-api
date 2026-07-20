<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_ticket_history extends Model
{
    use HasFactory;

    public function counter()
    {
        return $this->belongsTo(tb_counter::class, 'counter_id')
        ->where('state', true)->select(['id', 'reference']);
    }
    public function ticket()
    {
        return $this->belongsTo(tb_ticket::class, 'ticket_id')->select(['id', 'reference']);
    }

    protected $fillable = [
        'ticket_id',
        'counter_id',
        'daily_id',
        'state_id'
    ];
}
