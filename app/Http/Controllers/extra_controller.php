<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\tb_user; 
use App\Models\tb_service;
use App\Models\tb_counter;

class extra_controller extends Controller
{
    public function count()
    {
        $auth = auth()->user();

        if ($auth->role_id != '1' && $auth->role_id != '2') {
            return response()->json([
                'message' => 'Usuário não permitido',
            ], 409);
        }

        $response = [
            'users' => tb_user::where('state', true)->count(),
            'services' => tb_service::where('state', true)->count(),
            'counters' => tb_counter::where('state', true)->count(),
            'tickets' => 0
        ];

        return response()->json($response);
    }
}
