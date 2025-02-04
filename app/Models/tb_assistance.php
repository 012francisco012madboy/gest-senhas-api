<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_assistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_ticket',
        'id_assistant',
        'id_state'
    ];
}
