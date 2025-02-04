<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_front_desk;
use App\Models\tb_user_assistant;

class front_desk_controntroller extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function add(Request $request)
    {
        $result = tb_front_desk:: where('id_service', $request -> id_service)
        ->where('id_counter', $request -> id_counter)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if(!$result){
            tb_front_desk:: create($request -> all());

            return response([
                'message' => 'Serviço associado ao balcão'
            ], 201);
        }
        else{
            return response([
                'message' => 'Este balcão já está associado ao serviço selecionado'
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function remove(Request $request, string $id)
    {
        $tb_front_desk = tb_front_desk:: findorfail($id);

        if($tb_front_desk){
            $tb_front_desk -> update([
                'id_state' => "2"
            ]);

            return response([
                'message' => 'Serviço desassociado'
            ], 200);
        }
        else{

            return response([
                'message' => 'Serviço não encontrado'
            ], 200);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function view(string $id)
    {
        $tb_front_desks = tb_front_desk:: query()
        ->join('tb_counters as TC', 'TC.id', '=', 'tb_front_desks.id_counter')
        ->join('tb_services as TS', 'TS.id', '=', 'tb_front_desks.id_service')
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

        foreach($tb_front_desks as $tb_front_desk){
            $tb_user_assistant = tb_user_assistant:: query()
            ->where('id_front_desk', $tb_front_desk -> id)
            ->where('id_state', '1')
            ->latest()
            ->first();

            if(!$tb_user_assistant){
                $response[] = [
                    'id_counter' => $tb_front_desk -> id_front_desk,
                    'id_counter' => $tb_front_desk -> id_counter,
                    'ref' => $tb_front_desk -> ref,
                    'id_service' => $tb_front_desk -> id_service,
                    'service' => $tb_front_desk -> service,
                ];
            }
        }

        return $response;
    }
}
