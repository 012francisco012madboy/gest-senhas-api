<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Events\ticket_created;
use App\Models\tb_daily;
use App\Models\tb_ticket;
use App\Models\tb_service;
use App\Models\tb_counter_user;

class ticket_controller extends Controller
{
    public function index()
    {
        $day = now()->format('Y-m-d');

        $tb_daily = tb_daily::where('day', $day)->first();

        if (!$tb_daily) {
            return response()->json([
                'message' => 'Nenhuma sessão encontrada'
            ], 404);
        }

        $datas = tb_ticket:: query()
        ->with(['service'])
        ->where('daily_id', $tb_daily->id)
        ->where('state', true)
        ->orderBy('updated_at')
        ->latest('updated_at')
        ->get()
        ->map(function ($data){
            return [
                'id' => $data->id,
                'reference' => $data->reference,
                'service' => $data->service->name
            ];
        });

        return $datas;
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => [
                'required', 'integer',
                Rule::exists('tb_services', 'id')->where(fn ($query) => $query->where('state', true))
            ]
        ], [
            'service_id.required' => 'O serviço é obrigatório',
            'service_id.integer' => 'Formato do serviço inválido',
            'service_id.exists' => 'serviço não encotrado'
        ]);

        $day = now()->format('Y-m-d');

        $service = tb_service::findOrFail($request->service_id);

        DB::beginTransaction();

        try {
            $tb_daily = tb_daily::firstOrCreate(['day' => $day]);

            $num = tb_ticket::query()
            ->where('service_id', $service->id)
            ->where('daily_id', $tb_daily->id)
            ->count();

            $next = $num + 1;

            $ticket = $service->prefix . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);

            tb_ticket:: create([
                'reference' => $ticket,
                'service_id' => $service->id,
                'daily_id' => $tb_daily->id
            ]);
        
            event(new ticket_created());

            DB::commit();

            return response()->json([
                'ticket'  => $ticket
            ], 201);
        } catch (\Throwable $th) {
            throw $th;

            DB::rollback();
        }
    }

    public function counter()
    {
        $auth_id = auth()->id();

        $day = now()->format('Y-m-d');

        $tb_daily = tb_daily::where('day', $day)->first();

        if (!$tb_daily) {
            return response()->json([
                'message' => 'Nenhuma sessão encontrada'
            ], 404);
        }

        $tb_counter_user = tb_counter_user::query()
        ->with(['counter.counterService']) 
        ->where('user_id', $auth_id)
        ->where('daily_id', $tb_daily->id)
        ->where('state', true)
        ->first();

        if (!$tb_counter_user) {
            return response()->json([
                'message' => 'Nenhum caixa aberto'
            ], 404);
        }

        $serviceIds = $tb_counter_user->counter?->counterService
        ->pluck('service_id')
        ->toArray() ?? [];

        if (empty($serviceIds)) {
            return response()->json([], 200);
        }

        $datas = tb_ticket::query()
        ->with(['service'])
        ->where('daily_id', $tb_daily->id)
        ->whereIn('service_id', $serviceIds)
        ->where('state', true)
        ->orderBy('updated_at')
        ->latest('updated_at')
        ->get()
        ->map(function ($data) {
            return [
                'id' => $data->id,
                'reference' => $data->reference,
                'service' => $data->service?->name
            ];
        });

        return $datas;
    }
}
