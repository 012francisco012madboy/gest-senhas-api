<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Services\validation_service;

use App\Models\tb_user;
use App\Models\tb_daily;
use App\Models\tb_counter_user;

class auth_controller extends Controller
{
    public function login(Request $request, validation_service $validator)
    {
        $email = $request -> email;

        if (($result = $validator->Email_Validate($email)) !== true) return $result;
        
        $request->validate([
            'password' => 'required|min:8'
        ],[
            'password.required' => 'A senha é obrigatório.',
            'password.min' => 'A senha contem no mínimo 8 dígitos',
        ]);

        $tb_user = tb_user:: query()
        ->where('email', $email)
        ->where('state', true)
        ->first();

        if(!$tb_user || !Hash::check($request -> password, $tb_user -> password)){
            return response([
                'message' => "Email ou palavra-passe incorreta"
            ], 400);
        }
        
        $token = JWTAuth::fromUser($tb_user);

        $response = [
            'token' => $token,
            'message' => "Logado com sucesso"
        ];

        return response($response, 200);
    }

    public function index()
    {
        $id = auth()->id();

        $data = tb_user:: query()
        ->with(['role'])
        ->withExists('current_counter')
        ->where('id', $id)
        ->first();

        if (is_null($data)) {
            return response([
                'message' => "Usuário não encontrado!"
            ], 404);
        }

        $response = array_filter([
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role->name,
            'current_counter' => $data->current_counter_exists
        ], fn($value) => !is_null($value));

        return $response;
    }
    
    public function open(Request $request)
    {
        $auth_id = auth()->id();

        $request->validate([
            'counter_id' => [
                'required', 'integer',
                Rule::exists('tb_counters', 'id')->where(fn ($query) => $query->where('state', true))
            ]
        ], [
            'counter_id.required' => 'O balcão é obrigatório',
            'counter_id.integer' => 'Formato do balcão inválido',
            'counter_id.exists' => 'Balcão não encotrado'
        ]);

        $day = now()->format('Y-m-d');

        $tb_daily = tb_daily::firstOrCreate(['day' => $day]);

        tb_counter_user:: create([
            'user_id' => $auth_id,
            'counter_id' => $request->counter_id,
            'daily_id' => $tb_daily->id
        ]);

        return response()->json([
            'message'  => 'Balcão aberto com sucesso'
        ], 201);
    }
    
    public function active()
    {
        $auth_id = auth()->id();

        $day = now()->format('Y-m-d');

        $tb_daily = tb_daily::where('day', $day)->first();

        if (!$tb_daily) {
            return response()->json([
                'message' => 'Nenhuma sessão encontrada'
            ], 404);
        }

        $data = tb_counter_user::query()
        ->with(['counter'])
        ->whereHas('counter')
        ->where('user_id', $auth_id)
        ->where('daily_id', $tb_daily->id)
        ->where('state', true)
        ->latest()
        ->first();

        if (!$data) {
            return response()->json([
                'message' => 'Nenhum caixa aberto'
            ], 404);
        }

        $response = array_filter([
            'id' => $data->counter->id,
            'reference' => $data->counter->reference,
        ], fn($value) => !is_null($value));

        return $response;
    }

    public function out()
    {
        $auth_id = auth()->id();

        $day = now()->format('Y-m-d');

        $tb_daily = tb_daily::where('day', $day)->first();

        if ($tb_daily) {
            tb_counter_user::query()
                ->where('user_id', $auth_id)
                ->where('daily_id', $tb_daily->id)
                ->where('state', true)
                ->update([
                    'state' => false
                ]);
        }

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout efetuado com sucesso.'
        ], 200);
    }

    public function refresh()
    {
        try {
            $newToken = auth()->refresh();

            return response()->json([
                'token' => $newToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível renovar o token.'
            ], 401);
        }
    }
}
