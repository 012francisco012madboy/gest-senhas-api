<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_user_assistant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_front_desk',
        'id_state'
    ];
}
