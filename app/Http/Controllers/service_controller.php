<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\tb_service;
use App\Services\validation_service;

class service_controller extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $tb_services = tb_service:: query()
        ->where('state', true)
        ->select(
            'id',
            'name',
            'prefix'
        )
        ->get();

        return $tb_services;
    }

    public function active()
    {
        $tb_services = tb_service:: query()
        ->where('state', true)
        ->whereHas('counterService')
        ->whereHas('counterService.counter.current_counter')
        ->select(
            'id',
            'name',
            'prefix'
        )
        ->get();

        return $tb_services;
    }

    public function store(Request $request, validation_service $validator)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('tb_services', 'name')->where(fn ($query) => $query->where('state', true))
            ],
            'prefix' => [
                'required', 'string', 'min:3', 'max:3',
                Rule::unique('tb_services', 'prefix')->where(fn ($query) => $query->where('state', true))
            ]
        ], [
            'name.required' => 'O nome do serviço é obrigatório',
            'name.string' => 'Formato do nome inválido',
            'name.unique' => 'O nome já pertence a um serviço',

            'prefix.required' => 'A abreviação do serviço é obrigatória',
            'prefix.string' => 'Formato da abreviação inválida',
            'prefix.min' => 'A abreviação pode ter no mínimo 3 dígitos',
            'prefix.max' => 'A abreviação pode ter no máximo 3 dígitos',
            'prefix.unique' => 'A abreviação já pertence a um serviço'
        ]);

        $name = $request->name;
        $prefix = strtoupper($request->prefix);

        if (($result = $validator->Name_Validate($name)) !== true) return $result;

        tb_service:: create([
            'name' => $name,
            'prefix' => $prefix
        ]);

        return response([
            'message' => "Serviço adicionado com sucesso"
        ], 200);
    } 

    public function update(Request $request, validation_service $validator)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $request->validate([
            'name' => [
                'required', 'string',
                Rule::unique('tb_services', 'name')
                ->ignore($request->id)
                ->where(fn ($q) => $q->where('state', true))
            ],
            'prefix' => [
                'required', 'string', 'min:3', 'max:3',
                Rule::unique('tb_services', 'prefix')
                ->ignore($request->id)
                ->where(fn ($q) => $q->where('state', true))
            ]
        ], [
            'name.required' => 'O nome do serviço é obrigatório',
            'name.string' => 'Formato do nome inválido',
            'name.unique' => 'O nome já pertence a um serviço',

            'prefix.required' => 'A abreviação do serviço é obrigatória',
            'prefix.string' => 'Formato da abreviação inválida',
            'prefix.min' => 'A abreviação pode ter no mínimo 3 dígitos',
            'prefix.max' => 'A abreviação pode ter no máximo 3 dígitos',
            'prefix.unique' => 'A abreviação já pertence a um serviço'
        ]);

        $service = tb_service:: query()
        ->where("id", $request->id)
        ->where('state', true)
        ->latest()
        ->first();

        if(!$service){
            return response([
                'message' => "Serviço não encontrado"
            ], 404);
        }

        if($service->name == $request->name && $service->id != $request->id){
            return response([
                'message' => "Existe um serviço com este nome"
            ], 400);
        }

        if($service->prefix == $request->prefix && $service->id != $request->id){
            return response([
                'message' => "Existe um serviço com este prefixo"
            ], 400);
        }

        $name = $request -> name;
        $prefix = strtoupper($request->prefix);

        if (($result = $validator->Name_Validate($name)) !== true) return $result;

        $service->update([
            'name' => $name,
            'prefix' => $prefix
        ]);

        return response([
            'message' => "Serviço atualizado com sucesso"
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

        $service = tb_service:: query()
        ->where("id", $id)
        ->where('state', true)
        ->latest()
        ->first();

        if(!$service){
            return response([
                'message' => "Serviço não encontrado"
            ], 404);
        }

        $service->update([
            'state' => false
        ]);

        return response([
            'message' => "Serviço eliminado com sucesso"
        ], 200);
    } 
}
