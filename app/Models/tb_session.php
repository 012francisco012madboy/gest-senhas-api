<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_session extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_company',
        'id_state'
    ];
}
