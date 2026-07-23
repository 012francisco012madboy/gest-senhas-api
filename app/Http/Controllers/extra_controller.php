<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\tb_user; 
use App\Models\tb_service;
use App\Models\tb_counter;
use App\Models\tb_ticket;
use App\Models\tb_daily;

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

        $day = now()->format('Y-m-d');

        $tb_daily = tb_daily::where('day', $day)->first();

        $response = [
            'users' => tb_user::where('state', true)->count(),
            'services' => tb_service::where('state', true)->count(),
            'counters' => tb_counter::where('state', true)->count(),
            'tickets' => tb_ticket::where('daily_id', $tb_daily->id)->count()
        ];

        return response()->json($response);
    }
}
