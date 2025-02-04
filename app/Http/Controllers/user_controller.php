<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_user;
use App\Models\tb_type;

class user_controller extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function add(Request $request)
    {
        $tb_user = tb_user::where(['email' => $request -> email])->first();

        if($tb_user)
        {
            return response([
                'message' => "Já tem um funcionário com este email"
            ], 400);
        }
        else{
            tb_user:: create([
                'name' => $request -> name,
                'email' => $request -> email,
                'password' =>'12345678',
                'id_type' => $request -> id_type,
                'id_company' => $request -> id_company
            ]);

            return response([
                'message' => "Funcionário adicionado com sucesso"
            ], 201);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function signup(Request $request)
    {
        $tb_user = tb_user::where(['email' => $request -> email])->first();

        if($tb_user)
        {
            return response([
                'message' => "Já existe um usuário com este email"
            ], 400);
        }
        else{
            tb_user:: create($request -> all());

            return response([
                'message' => "Conta criada com sucesso"
            ], 201);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function signin(Request $request)
    {
        $request -> validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $tb_user = tb_user::where(['email' => $request -> email])->first();

        if(!$tb_user || $request -> password != $tb_user->password)
        {
            return response([
                'message' => "Email ou senha inválida"
            ], 400);
        }

        if($tb_user){
            $response = [
                'user' => $tb_user,
                'message' => "Logado com sucesso..."
            ];
        }
        else{
            $response = [
                'message' => "Email ou senha inválida"
            ];
        }

        return response($response, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function view(string $id)
    {
        $tb_users = tb_user:: query()
        ->where('id_company', $id)
        ->where('id_state', '1')
        ->get();

        $response = [];

        foreach($tb_users as $tb_user){
            $tb_type = tb_type:: findorfail($tb_user -> id_type);

            $response[] = [
                'id' => $tb_user -> id,
                'name' => $tb_user-> name,
                'email' => $tb_user-> email,
                'id_type' => $tb_user-> id_type,
                'type' => $tb_type -> name,
                'id_state' => $tb_user-> id_state,
                'id_company' => $tb_user-> id_company
            ];
        }

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tb_user = tb_user:: findorfail($id);

        $tb_type = tb_type:: findorfail($tb_user -> id_type);

        return [
            'id' => $tb_user -> id,
            'name' => $tb_user-> name,
            'email' => $tb_user-> email,
            'id_type' => $tb_user-> id_type,
            'type' => $tb_type -> name,
            'id_state' => $tb_user-> id_state,
            'id_company' => $tb_user-> id_company
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function count(string $id)
    {
        return $tb_users = tb_user:: query()
        ->where('id_company', $id)
        ->where('id_state', '1')
        ->count();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tb_user = tb_user:: findorfail($id);

        if($tb_user){

            $tb_user -> update($request -> all());

            return response([
                'message' => "Funcionário atualizado com sucesso"
            ], 200);
        }
        else{

            return response([
                'message' => "Funcionário não encontrado"
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tb_user = tb_user:: findorfail($id);

        if($tb_user){
            $tb_user -> update([
                'id_state' => "2"
            ]);

            return response([
                'message' => "Funcionário eliminado com sucesso"
            ], 200);
        }
        else{

            return response([
                'message' => "Funcionário não encontrado"
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function reset(string $id)
    {
        $tb_user = tb_user:: findorfail($id);

        if($tb_user){
            $tb_user -> update([
                'password' => "12345678"
            ]);

            return response([
                'message' => "Senha redefinida com sucesso"
            ], 200);
        }
        else{

            return response([
                'message' => "Funcionário não encontrado"
            ], 200);
        }
    }
}
