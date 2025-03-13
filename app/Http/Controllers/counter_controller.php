<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_counter;
use App\Models\tb_service;
use App\Models\tb_user;
use App\Models\tb_front_desk;
use App\Models\tb_user_assistant;

class counter_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function add(Request $request)
    {
        $counter = tb_counter:: query()
        ->where('ref', $request -> ref)
        ->where('id_company', $request -> id_company)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if(!$counter){
            tb_counter:: create($request -> all());

            return response([
                'message' => 'Balcão adiciaonado com sucesso'
            ], 201);
        }
        else{
            return response([
                'message' => 'Já existe um balcão com este nome'
            ], 400);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function view(string $id)
    {
        $tb_counters = tb_counter:: query()
        ->where('id_company', $id)
        ->where('id_state', '1')
        ->get();

        $response = [];

        foreach($tb_counters as $tb_counter){
            $tb_front_desk = tb_front_desk:: query()
            ->where('id_counter', $tb_counter -> id)
            ->where('id_state', '1')
            ->latest() -> first();

            $tb_service = tb_service:: query()
            ->where('id', $tb_front_desk ? $tb_front_desk -> id_service : '0')
            ->where('id_state', '1')
            ->latest() -> first();

            $tb_user_assistant = tb_user_assistant:: query()
            ->where('id_front_desk', $tb_front_desk ? $tb_front_desk -> id : '0')
            ->where('id_state', '1')
            ->latest() -> first();

            $tb_user = tb_user:: query()
            ->where('id', $tb_user_assistant ? $tb_user_assistant -> id_user : '0')
            ->latest() -> first();

            $response[] = [
                'id_counter' => $tb_counter -> id,
                'ref' => $tb_counter -> ref,
                'id_service' => $tb_service ? $tb_service -> id : null,
                'service' => $tb_service ? $tb_service -> name : null,
                'id_user' => $tb_user_assistant ? $tb_user_assistant -> id : null,
                'user' => $tb_user ? $tb_user -> name : null,
            ];
        }

        return $response;
    }

    /**
     * Display a listing of the resource.
     */
    public function count(string $id)
    {
        return tb_counter:: query()
        ->where('id_company', $id)
        ->where('id_state', '1')
        ->count();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tb_counter = tb_counter:: findorfail($id);

        if($tb_counter){
            $counter = tb_counter:: query()
            ->where('ref', $request -> ref)
            ->where('id_company', $request -> id_company)
            ->latest()
            ->first();

            if(!$counter){
                $tb_counter -> update($request -> all());

                return response([
                    'message' => 'Balcão atualizado com sucesso'
                ], 200);
            }
            else{
                return response([
                    'message' => 'Já existe um balcão com este nome'
                ], 400);
            }
        }
        else{
            return response([
                'message' => 'Balcão não encontrado'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tb_counter = tb_counter:: findorfail($id);

        $tb_front_desk = tb_front_desk:: query()
        ->where('id_counter', $tb_counter -> id)
        ->where('id_state', '1')
        ->latest() -> first();

        $tb_service = tb_service:: query()
        ->where('id', $tb_front_desk ? $tb_front_desk -> id_service : '0')
        ->where('id_state', '1')
        ->latest() -> first();

        $tb_user_assistant = tb_user_assistant:: query()
        ->where('id_front_desk', $tb_front_desk ? $tb_front_desk -> id : '0')
        ->where('id_state', '1')
        ->latest() -> first();

        $tb_user = tb_user:: query()
        ->where('id', $tb_user_assistant ? $tb_user_assistant -> id_user : '0')
        ->latest() -> first();

        return [
            'id_counter' => $tb_counter -> id,
            'ref' => $tb_counter -> ref,
            'id_front_desk' => $tb_front_desk -> id,
            'id_service' => $tb_service ? $tb_service -> id: null,
            'service' => $tb_service ? $tb_service -> name : null,
            'id_assistant' => $tb_user_assistant ? $tb_user_assistant -> id : null,
            'id_user' => $tb_user ? $tb_user -> id : null,
            'user' => $tb_user ? $tb_user -> name : null,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tb_counter = tb_counter:: findorfail($id);

        if($tb_counter){
            $tb_front_desk = tb_front_desk:: query()
            ->where('id_counter', $tb_counter -> id)
            ->where('id_state', '1')
            ->latest() -> first();

            $tb_service = tb_service:: query()
            ->where('id', $tb_front_desk ? $tb_front_desk -> id_service : '0')
            ->where('id_state', '1')
            ->latest() -> first();

            $tb_user_assistant = tb_user_assistant:: query()
            ->where('id_front_desk', $tb_front_desk ? $tb_front_desk -> id : '0')
            ->where('id_state', '1')
            ->latest() -> first();

            $tb_user = tb_user:: query()
            ->where('id', $tb_user_assistant ? $tb_user_assistant -> id_user : '0')
            ->latest() -> first();

            if(!$tb_service && !$tb_user){
                $tb_counter -> update([
                    'id_state' => "2"
                ]);

                return response([
                    'message' => 'Balcão eliminado com sucesso'
                ], 200);
            }
            else{
                return response([
                    'message' => 'Remova o serviço ou o funcionário antes de eliminar o balcão'
                ], 400);
            }
        }
        else{
            return response([
                'message' => 'Balcão não encontrado'
            ], 400);
        }
    }
}
