<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_user_assistant;

class user_assistant_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function add(Request $request)
    {
        $result = tb_user_assistant:: query()
        ->where('id_front_desk', $request -> id_front_desk)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if(!$result){
            tb_user_assistant:: create($request -> all());

            return response([
                'message' => 'Balcão selecionado'
            ], 201);
        }
        else{
            if($result -> id_user == $request -> id_user)
            {
                return response([
                    'message' => 'Balcão selecionado'
                ], 201);
            }
            else{
                return response([
                    'message' => 'Este balcão já está associado a um outro funcionário'
                ], 400);
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function view(string $user, string $desk)
    {
        return tb_user_assistant:: query()
        ->join('tb_users as TU', 'TU.id', '=', 'tb_user_assistants.id_user')
        ->join('tb_front_desks as TFD', 'TFD.id', '=', 'tb_user_assistants.id_front_desk')
        ->join('tb_counters as TC', 'TC.id', '=', 'TFD.id_counter')
        ->join('tb_services as TS', 'TS.id', '=', 'TFD.id_service')
        ->where('tb_user_assistants.id_user', $user)
        ->where('tb_user_assistants.id_front_desk', $desk)
        ->where('tb_user_assistants.id_state', "1")
        ->select(
            'tb_user_assistants.id as id_assistant',
            'TU.id as id_user',
            'TU.name as name_user',
            'TC.id as id_counter',
            'TC.ref as ref_counter',
            'TS.id as id_service',
            'TS.name as name_service',
        )
        ->latest('tb_user_assistants.created_at')
        ->first();
    }

    /**
     * Display a listing of the resource.
     */
    public function remove(Request $request, string $id)
    {
        $tb_user_assistant = tb_user_assistant:: findorfail($id);

        if($tb_user_assistant){
            $tb_user_assistant -> update([
                'id_state' => "2"
            ]);

            return response([
                'message' => 'Balcão desassociado ao funcionário'
            ], 200);
        }
        else{

            return response([
                'message' => 'Balcão não encontrado'
            ], 400);
        }
    }
}
