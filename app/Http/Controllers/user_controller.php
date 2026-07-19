<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\Hash;
use App\Models\tb_user;
use App\Services\validation_service;

class user_controller extends Controller
{
    public function store(Request $request, validation_service $validator)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $request->validate([
            'email' => [
                'email',
                'unique:tb_users,email'
            ]
        ], [
            'email.email' => 'O email é inválido.',
            'email.unique' => 'Já existe uma conta com este e-mail.'
        ]);

        $name = $request -> name;
        $email = $request -> email;

        if (($result = $validator->Name_Validate($name)) !== true) return $result;
        if (($result = $validator->Email_Validate($email)) !== true) return $result;
        if (($result = $validator->Password_Validate($request -> password, $request -> passwordConfirm)) !== true) return $result;

        $role_id = $request -> idRole == "2" ? "2" : "3";

        $tb_user = tb_user::create([
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id,
            'password' => bcrypt($request -> password)
        ]);

        $response = [
            'message' => "Usuário adicionado com sucesso"
        ];

        return $response;
    }

    public function list()
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $tb_users = tb_user::query()
        ->with(['role'])
        ->where('state', true)
        ->orderBy('name')
        ->get()
        ->map(function ($user){
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->name
            ];
        });
        
        return $tb_users;
    }
    
    public function update(Request $request, validation_service $validator)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $name = $request -> name;
        $email = $request -> email;

        if (($result = $validator->Name_Validate($name)) !== true) return $result;
        if (($result = $validator->Email_Validate($email)) !== true) return $result;
        
        $tb_user = tb_user::query()
        ->where('id', $request->id)
        ->where('state', true)
        ->latest()
        ->first();

        if(!$tb_user){
            return response([
                'message' => 'Usuário não encontrado',
            ], 409);
        }

        $tb_user->update([
            'name' => $name,
            'email' => $email
        ]);

        return response()->json([
            'message' => "Usuário atualizados com sucesso"
        ], 200);
    }
    
    public function delete(string $id)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }
        
        $tb_user = tb_user::query()
        ->where('id', $id)
        ->where('state', true)
        ->latest()
        ->first();

        if(!$tb_user){
            return response([
                'message' => 'Usuário não encontrado',
            ], 409);
        }

        $tb_user->update([
            'state' => false
        ]);

        return response()->json([
            'message' => "Usuário eliminado com sucesso"
        ], 200);
    }
    
    public function password(Request $request)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }
        
        $tb_user = tb_user::query()
        ->where('id', $request->id)
        ->where('state', true)
        ->latest()
        ->first();

        if(!$tb_user){
            return response([
                'message' => 'Usuário não encontrado',
            ], 409);
        }

        $tb_user->update([
            'password' => bcrypt('12345678')
        ]);

        return response()->json([
            'message' => "Palavra passe alterado com sucesso"
        ], 200);
    }
}
