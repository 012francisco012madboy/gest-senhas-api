<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\tb_company;
use App\Models\tb_user;

class company_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function signup(Request $request)
    {
        $request -> validate([
            'name_company' => 'required|string',
            'email_company' => 'required|string',

            'name_user' => 'required|string',
            'email_user' => 'required|string',
            'password_user' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            $tb_company = tb_company::where('email', $request -> email_company)->first();

            if($tb_company)
            {
                return response([
                    'message' => "Já existe uma empresa com este e-mail"
                ], 400);
            }
            $tb_user = tb_user::where('email', $request -> email_user)->first();

            if($tb_user)
            {
                return response([
                    'message' => "Já existe uma conta com este e-mail"
                ], 400);
            }
            $tb_company = tb_company::create([
                'name' => $request -> name_company,
                'email' => $request -> email_company,
            ]);

            $last_company = tb_company:: query()
            ->where('name', $request -> name_company)
            ->where('email', $request -> email_company)
            ->latest()->firstOrFail();

            $tb_users = tb_user::create([
                'name' => $request -> name_user,
                'email' => $request -> email_user,
                'password' => $request -> password_user,
                'id_type' => '1',
                'id_company' => $last_company->id
            ]);

            $response = [
                'tb_user' => $tb_users,
                'message' => "Conta criada com sucesso"
            ];

            DB:: commit();

            return response($response, 201);
        } catch (\Throwable $th) {
            throw $th;

            DB:: rollback();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
