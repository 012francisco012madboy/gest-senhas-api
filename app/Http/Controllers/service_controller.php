<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_service;

class service_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function add(Request $request)
    {
        $service = tb_service:: query()
        ->where("name", $request -> name)
        ->where('id_company', $request -> id_company)
        ->latest()
        ->first();

        if(!$service){
            tb_service:: create($request -> all());

            return response([
                'message' => "Serviço adiciaonado com sucesso"
            ], 201);
        }
        else{
            return response([
                'message' => "Já existe essa categoria"
            ], 400);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function view(string $id)
    {
        return tb_service:: query()
        ->where('id_company', $id)
        ->where('id_state', '1')
        ->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tb_service = tb_service:: findorfail($id);

        return $tb_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function count(string $id)
    {
        return tb_service:: query()
        ->where('id_company', $id)
        ->where('id_state', '1')
        ->count();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tb_service = tb_service:: findorfail($id);

        if($tb_service){
            $service = tb_service:: where("name", $request -> name)
            ->latest()
            ->first();

            if(!$service){
                $tb_service -> update($request -> all());

                return response([
                    'message' => "Serviço atualizado com sucesso"
                ], 200);
            }
            else{
                return response([
                    'message' => "Já existe um serviço com este nome"
                ], 400);
            }
        }
        else{

            return response([
                'message' => "Serviço não encontrado"
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tb_service = tb_service:: findorfail($id);

        if($tb_service){
            $tb_service -> update([
                'id_state' => "2"
            ]);

            return response([
                'message' => "Serviço eliminado com sucesso"
            ], 200);
        }
        else{
            return response([
                'message' => "Serviço não encontrado"
            ], 200);
        }
    }
}
