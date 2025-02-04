<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_front_desk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_service',
        'id_counter',
        'id_state'
    ];
}
