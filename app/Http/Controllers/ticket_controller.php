<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\tb_ticket;
use App\Models\tb_session;
use App\Models\tb_assistance;

class ticket_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function add(Request $request)
    {
        $result = tb_session:: query()
        ->where('id_company', $request -> actCompany)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if($result){
            if($result && $result -> created_at -> isToday()){
                $num = tb_ticket:: query()
                ->where('id_session', $result -> id)
                ->count();

                $tb_ticket = tb_ticket:: create([
                    'ref' => $num + 1,
                    'id_service' => $request -> service,
                    'id_session' => $result -> id
                ]);

                return response([
                    'ref' => $tb_ticket -> ref,
                    'message' => 'Senha criada com sucesso'
                ], 201);
            }
            else{
                $result -> update([
                    'id_state' => "2"
                ]);

                $tb_session = tb_session:: create([
                    'id_company' => $request -> actCompany
                ]);

                $num = 0;

                $tb_ticket = tb_ticket:: create([
                    'ref' => $num + 1,
                    'id_service' => $request -> service,
                    'id_session' => $tb_session -> id
                ]);

                return response([
                    'ref' => $tb_ticket -> ref,
                    'message' => 'Senha criada com sucesso'
                ], 201);
            }
        }
        else{
            $tb_session = tb_session:: create([
                'id_company' => $request -> actCompany
            ]);

            $num = 0;

            $tb_ticket = tb_ticket:: create([
                'ref' => $num + 1,
                'id_service' => $request -> service,
                'id_session' => $tb_session -> id
            ]);

            return response([
                'ref' => $tb_ticket -> ref,
                'message' => 'Senha criada com sucesso'
            ], 201);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function view(string $service, string $company)
    {
        $result = tb_session:: query()
        ->where('id_company', $company)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if($result -> created_at -> isToday()){
            return tb_ticket:: query()
            ->join('tb_services as TS', 'TS.id', '=', 'tb_tickets.id_service')
            ->where('tb_tickets.id_service', $service)
            ->where('tb_tickets.id_session', $result -> id)
            ->where('tb_tickets.id_state', '1')
            ->orderBy('tb_tickets.updated_at', 'asc')
            ->select(
                'tb_tickets.id',
                'tb_tickets.ref',
                'TS.id as id_service',
                'TS.name as service'
            )
            ->get();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function viewAll(string $company)
    {
        $result = tb_session:: query()
        ->where('id_company', $company)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if($result -> created_at -> isToday()){
            return tb_ticket:: query()
            ->join('tb_services as TS', 'TS.id', '=', 'tb_tickets.id_service')
            ->where('tb_tickets.id_session', $result -> id)
            ->where('tb_tickets.id_state', '1')
            ->orderBy('tb_tickets.updated_at', 'asc')
            ->select(
                'tb_tickets.id',
                'tb_tickets.ref',
                'TS.id as id_service',
                'TS.name as service'
            )
            ->get();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function viewLast(string $company)
    {
        $result = tb_session:: query()
        ->where('id_company', $company)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if($result -> created_at -> isToday()){
            return tb_ticket:: query()
            ->join('tb_assistances as TA', 'TA.id_ticket', '=', 'tb_tickets.id')
            ->join('tb_user_assistants as TUA', 'TUA.id', '=', 'TA.id_assistant')
            ->join('tb_front_desks as TFD', 'TFD.id', '=', 'TUA.id_front_desk')
            ->join('tb_counters as TC', 'TC.id', '=', 'TFD.id_counter')
            ->where('TA.id_state', '3')
            ->where('TC.id_company', $company)
            ->orderBy('tb_tickets.updated_at', 'desc')
            ->select(
                'tb_tickets.ref',
                'TC.ref as counter',
            )
            ->latest('tb_tickets.updated_at', 'desc')
            ->first();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function next(string $service, string $assistant)
    {
        DB::beginTransaction();

        try {
            $tb_ticket = tb_ticket:: query()
            ->join('tb_sessions as TS', 'TS.id', '=', 'tb_tickets.id_session')
            ->where('tb_tickets.id_service', $service)
            ->where('tb_tickets.id_state', "1")
            ->where('TS.id_state', "1")
            ->select('tb_tickets.*')
            ->orderBy('tb_tickets.updated_at', 'asc')
            ->first();

            if($tb_ticket){
                $ticket = tb_ticket::find($tb_ticket->id);

                if($ticket){
                    $ticket -> update([
                        'id_state' => "2"
                    ]);

                    tb_assistance:: create([
                        'id_ticket' => $ticket -> id,
                        'id_assistant' => $assistant,
                        'id_state' => "3"
                    ]);
                }
            }

            DB:: commit();

            return [
                'id_ref' => $tb_ticket -> id,
                'ref' => $tb_ticket -> ref,
            ];
        } catch (\Throwable $th) {
            throw $th;

            DB:: rollback();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function finished(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            if($request -> state != "4"){
                $tb_assistance = tb_assistance:: query()
                ->where('id_ticket', $id)
                ->where('id_state', "3")
                ->latest()
                ->first();

                $tb_assistance -> update([
                    'id_state' => $request -> state
                ]);
            }
            else{
                $tb_ticket = tb_ticket:: findorfail($id);

                $tb_ticket -> update([
                    'id_state' => "1"
                ]);

                $tb_assistance = tb_assistance:: query()
                ->where('id_ticket', $id)
                ->where('id_state', "3")
                ->latest()
                ->first();

                $tb_assistance -> update([
                    'id_state' => $request -> state
                ]);
            }

            DB:: commit();
        } catch (\Throwable $th) {
            throw $th;

            DB:: rollback();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function current(string $id)
    {
        $tb_assistance = tb_assistance:: query()
        ->where('id_assistant', $id)
        ->where('id_state', "3")
        ->latest()
        ->first();

        $tb_ticket = tb_ticket:: findorfail($tb_assistance -> id_ticket);

        return [
            'id_ref' => $tb_ticket -> id,
            'ref' => $tb_ticket -> ref,
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function count(string $id)
    {
        $result = tb_session:: query()
        ->where('id_company', $id)
        ->where('id_state', '1')
        ->latest()
        ->first();

        if($result && $result -> created_at -> isToday()){
            return tb_ticket:: query()
            ->where('id_session', $result -> id)
            ->count();
        }
        else{
            return 0;
        }
    }
}
