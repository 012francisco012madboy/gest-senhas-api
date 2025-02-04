<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_state',
        'id_company'
    ];
}
