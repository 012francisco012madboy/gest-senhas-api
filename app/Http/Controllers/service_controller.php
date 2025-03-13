<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_service;
use App\Models\tb_front_desk;
use App\Models\tb_user_assistant;

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
     * Display a listing of the resource.
     */
    public function available(string $id)
    {
        $tb_front_desks = tb_front_desk:: query()
        ->join('tb_counters as TC', 'TC.id', '=', 'tb_front_desks.id_counter')
        ->join('tb_services as TS', 'TS.id', '=', 'tb_front_desks.id_service')
        ->join('tb_user_assistants as TUA', 'TUA.id_front_desk', '=', 'tb_front_desks.id')
        ->where('TUA.id_state', '1')
        ->where('tb_front_desks.id_state', '1')
        ->where('TC.id_company', $id)
        ->where('TS.id_company', $id)
        ->select(
            'tb_front_desks.id as id_front_desk',
            'TC.id as id_counter',
            'TC.ref',
            'TS.id as id_service',
            'TS.name as service'
        )
        ->get();

        $response = [];
        $service = [];

        foreach($tb_front_desks as $tb_front_desk){
            $tb_user_assistant = tb_user_assistant:: query()
            ->where('id_front_desk', $tb_front_desk -> id)
            ->where('id_state', '1')
            ->latest()
            ->first();

            if(!$tb_user_assistant){
                if (!isset($service[$tb_front_desk->id_service])) {
                    $service[$tb_front_desk->id_service] = [
                        'id_service' => $tb_front_desk->id_service
                    ];

                    $response[] = [
                        'id' => $tb_front_desk->id_service,
                        'name' => $tb_front_desk->service,
                    ];
                }
            }
        }

        return $response;
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
            ], 400);
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
            ], 400);
        }
    }
}
