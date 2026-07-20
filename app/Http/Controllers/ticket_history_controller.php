<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TicketCalled;
use App\Events\TicketCreated;
use App\Models\tb_daily;
use App\Models\tb_ticket;
use App\Models\tb_counter_user;
use App\Models\tb_ticket_history;

class ticket_history_controller extends Controller
{
    public function last()
    {
        $day = now()->format('Y-m-d');

        $tb_daily = tb_daily::firstOrCreate(['day' => $day]);

        $data = tb_ticket_history:: query()
        ->with(['counter', 'ticket'])
        // ->where('daily_id', $tb_daily->id)
        ->where('state_id', '3')
        ->latest()
        ->first();

        if (!$data) {
            return response()->json([
                'message' => 'Nenhuma senha em atendimento'
            ], 404);
        }

        return response()->json([
            'id' => $data->id,
            'reference' => $data->ticket->reference,
            'counter' => $data->counter->reference
        ], 200);
    }

    public function next()
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
        ->latest()
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
            return response()->json([
                'message' => 'Nenhum serviço vinculado a este balcão'
            ], 400);
        }

        $ticket = tb_ticket::query()
            ->where('daily_id', $tb_daily->id)
            ->whereIn('service_id', $serviceIds)
            ->where('state', true)
            // ->orderByDesc('priority')
            ->oldest()
            ->first();

        if (!$ticket) {
            return response()->json([
                'message' => 'Nenhuma senha na fila'
            ], 404);
        }

        $ticket->update([
            'state' => false
        ]);

        tb_ticket_history::create([
            'ticket_id'  => $ticket->id,
            'counter_id' => $tb_counter_user->counter_id, 
            // 'daily_id' => $tb_daily->id
            'state_id' => '3', 
        ]);
        
        event(new TicketCalled());
        event(new TicketCreated());

        return response()->json([
            'id'        => $ticket->id,
            'reference' => $ticket->reference,
            'counter'   => $tb_counter_user->counter?->reference
        ], 200);
    }

    public function finish()
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
        ->where('user_id', $auth_id)
        ->where('daily_id', $tb_daily->id)
        ->where('state', true)
        ->latest()
        ->first();

        if (!$tb_counter_user) {
            return response()->json([
                'message' => 'Nenhum caixa aberto'
            ], 404);
        }

        $tb_ticket_history = tb_ticket_history::query()
        ->where('counter_id', $tb_counter_user->counter_id)
        // ->where('daily_id', $tb_daily->id)
        ->where('state_id', '3')
            // ->orderByDesc('priority')
        ->oldest()
        ->first();

        if (!$tb_ticket_history) {
            return response()->json([
                'message' => 'Nenhuma senha em atendimento'
            ], 404);
        }

        $tb_ticket_history->update([
            'state_id' => '7'
        ]);
        
        event(new TicketCalled());

        return response()->json([
            'message' => 'Atendimento finalizado com sucesso'
        ], 200);
    }
}
