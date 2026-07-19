<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\tb_counter;

class counter_controller extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $tb_counters = tb_counter:: query()
        ->where('state', true)
        ->select(
            'id',
            'reference'
        )
        ->get();

        return $tb_counters;
    }

    public function active()
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $tb_counters = tb_counter:: query()
        ->where('state', true)
        ->whereHas('counterService')
        ->select(
            'id',
            'reference'
        )
        ->get();

        return $tb_counters;
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
            'reference' => [
                'required', 'string', 'max:1',
                Rule::unique('tb_counters', 'reference')->where(fn ($query) => $query->where('state', true))
            ]
        ], [
            'reference.required' => 'A referência do balcão é obrigatória',
            'reference.string' => 'Formato da referência inválida',
            'reference.max' => 'A referência pode ter apenas 1 dígito',
            'reference.unique' => 'A referência já pertence a um balcão'
        ]);
        
        $reference = strtoupper($request->reference);

        tb_counter:: create([
            'reference' => $reference
        ]);

        return response([
            'message' => "Balcão adicionado com sucesso"
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
            'reference' => 'required|string'
        ], [
            'reference.required' => 'A referência do balcão é obrigatória',
            'reference.string' => 'Formato da referência inválida'
        ]);

        $counter = tb_counter:: query()
        ->where("id", $request->id)
        ->where('state', true)
        ->latest()
        ->first();

        if(!$counter){
            return response([
                'message' => "Serviço não encontrado"
            ], 404);
        }

        if($counter->reference == $request->reference && $counter->id != $request->id){
            return response([
                'message' => "Existe um balcão com esta referência"
            ], 400);
        }

        $reference = strtoupper($request->reference);

        $counter->update([
            'reference' => $reference
        ]);

        return response([
            'message' => "Balcão atualizado com sucesso"
        ], 200);
    }

    public function delete(Request $request)
    {
        $auth = auth()->user();

        if($auth->role_id != '1' && $auth->role_id != '2'){
            return response([
                'message' => 'Usuário náo permitido',
            ], 409);
        }

        $counter = tb_counter:: query()
        ->where("id", $request->id)
        ->where('state', true)
        ->latest()
        ->first();

        if(!$counter){
            return response([
                'message' => "Balcão não encontrado"
            ], 404);
        }

        $counter->update([
            'state' => false
        ]);

        return response([
            'message' => "Balcão eliminado com sucesso"
        ], 200);
    } 
}
