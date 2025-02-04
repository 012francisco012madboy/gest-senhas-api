<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'id_state',
        'id_company'
    ];
}
