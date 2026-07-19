<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\Hash;
use App\Models\tb_user;
use App\Models\tb_counter_user;
use App\Services\validation_service;

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
            'role' => $data->role->name
        ], fn($value) => !is_null($value));

        return $response;
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
    
    public function out()
    {
        $id = auth()->id();
        
        $desk = tb_counter_user::query()
        ->where('id', $id)
        ->where('state', true)
        ->latest()
        ->first();

        if($desk){
            $desk->update([
                'state' => false
            ]);
        }

        JWTAuth::invalidate(JWTAuth::getToken());
    }
}
