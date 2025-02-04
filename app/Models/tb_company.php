<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'id_state'
    ];
}
