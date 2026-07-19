<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\tb_counter_service;

class counter_service_controller extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $datas = tb_counter_service:: query()
        ->with(['counter', 'service'])
        ->where('state', true)
        ->whereHas('counter')
        ->whereHas('service')
        ->orderBy('counter_id')
        ->get()
        ->map(function ($data){
            return [
                'id' => $data->id,
                'reference' => $data->counter->reference,
                'service' => $data->service->name,
            ];
        });

        return $datas;
    }

    public function store(Request $request)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $request->validate([
            'counter_id' => [
                'required', 'string',
                Rule::exists('tb_counters', 'id')->where(fn ($query) => $query->where('state', true))
            ],
            'service_id' => [
                'required', 'string',
                Rule::exists('tb_services', 'id')->where(fn ($query) => $query->where('state', true))
            ]
        ], [
            'counter_id.required' => 'O balcão é obrigatório',
            'counter_id.string' => 'Formato do balcão inválido',
            'counter_id.exists' => 'Balcão não encotrado',

            'service_id.required' => 'O serviço é obrigatório',
            'service_id.string' => 'Formato do serviço inválido',
            'service_id.exists' => 'serviço não encotrado'
        ]);


        $exists = tb_counter_service::query()
        ->where('counter_id', $request->counter_id)
        ->where('service_id', $request->service_id)
        ->where('state', true)
        ->exists();

        if($exists){
            return response([
                'message' => 'Já existe uma associação ativa entre este balcão e este serviço',
            ], 400);
        }

        tb_counter_service:: create([
            'counter_id' => $request->counter_id,
            'service_id' => $request->service_id
        ]);

        return response([
            'message' => "Associação realizada com sucesso"
        ], 200);
    } 

    public function update(Request $request)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $request->validate([
            'counter_id' => [
                'required', 'string',
                Rule::exists('tb_counters', 'id')->where(fn ($query) => $query->where('state', true))
            ],
            'service_id' => [
                'required', 'string',
                Rule::exists('tb_services', 'id')->where(fn ($query) => $query->where('state', true))
            ]
        ], [
            'counter_id.required' => 'O balcão é obrigatório',
            'counter_id.string' => 'Formato do balcão inválido',
            'counter_id.exists' => 'Balcão não encotrado',

            'service_id.required' => 'O serviço é obrigatório',
            'service_id.string' => 'Formato do serviço inválido',
            'service_id.exists' => 'serviço não encotrado'
        ]);


        $exists = tb_counter_service::query()
        ->where('counter_id', $request->counter_id)
        ->where('service_id', $request->service_id)
        ->where('state', true)
        ->exists();

        if($exists){
            return response([
                'message' => 'Já existe uma associação ativa entre este balcão e este serviço',
            ], 409);
        }

        $tb_counter_service = tb_counter_service::query()
        ->where('id', $request->id)
        ->where('state', true)
        ->first();

        if(!$tb_counter_service){
            return response([
                'message' => 'Associação não encontrada',
            ], 404);
        }

        $tb_counter_service->update([
            'service_id' => $request->service_id
        ]);

        return response([
            'message' => "Associação atualizada com sucesso"
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

        $tb_counter_service = tb_counter_service::query()
        ->where('id', $id)
        ->where('state', true)
        ->first();

        if(!$tb_counter_service){
            return response([
                'message' => 'Associação não encontrada',
            ], 404);
        }

        $tb_counter_service->update([
            'state' => false
        ]);

        return response([
            'message' => "Desassociação realizada com sucesso"
        ], 200);
    } 
}
