<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\company_controller;
use App\Http\Controllers\user_controller;
use App\Http\Controllers\service_controller;
use App\Http\Controllers\counter_controller;
use App\Http\Controllers\front_desk_controntroller;
use App\Http\Controllers\user_assistant_controller;
use App\Http\Controllers\ticket_controller;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* COMPANY */
Route::post("/sign-up", [company_controller::class, "signup"]);

/* USUÁRIO */
Route::post("/user-add", [user_controller::class, "add"]);
Route::post("/user-signup", [user_controller::class, "signup"]);
Route::post("/user-signin", [user_controller::class, "signin"]);
Route::get("/user-view/{id}", [user_controller::class, "view"]);
Route::get("/user-show/{id}", [user_controller::class, "show"]);
Route::get("/user-count/{id}", [user_controller::class, "count"]);
Route::put("/user-update/{id}", [user_controller::class, "update"]);
Route::put("/user-destroy/{id}", [user_controller::class, "destroy"]);
Route::put("/user-reset/{id}", [user_controller::class, "reset"]);

/* SERVIÇO */
Route::post("/service-add", [service_controller::class, "add"]);
Route::get("/service-view/{id}", [service_controller::class, "view"]);
Route::get("/service-count/{id}", [service_controller::class, "count"]);
Route::get("/service-show/{id}", [service_controller::class, "show"]);
Route::put("/service-update/{id}", [service_controller::class, "update"]);
Route::put("/service-destroy/{id}", [service_controller::class, "destroy"]);

/* BALCÃO */
Route::post("/counter-add", [counter_controller::class, "add"]);
Route::get("/counter-view/{id}", [counter_controller::class, "view"]);
Route::get("/counter-count/{id}", [counter_controller::class, "count"]);
Route::get("/counter-show/{id}", [counter_controller::class, "show"]);
Route::put("/counter-update/{id}", [counter_controller::class, "update"]);
Route::put("/counter-destroy/{id}", [counter_controller::class, "destroy"]);

/* BALCÃO & SERVIÇO */
Route::post("/front-desk-add", [front_desk_controntroller::class, "add"]);
Route::get("/front-desk-view/{id}", [front_desk_controntroller::class, "view"]);
Route::put("/front-desk-remove/{id}", [front_desk_controntroller::class, "remove"]);

/* BALCÃO & USUÁRIO */
Route::post("/user-assistant-add", [user_assistant_controller::class, "add"]);
Route::put("/user-assistant-remove/{id}", [user_assistant_controller::class, "remove"]);
Route::get("/user-assistant-view/{user}/{desk}", [user_assistant_controller::class, "view"]);

/* TICKET */
Route::post("/ticket-add", [ticket_controller::class, "add"]);
Route::get("/ticket-count/{id}", [ticket_controller::class, "count"]);
Route::get("/ticket-view/{service}/{company}", [ticket_controller::class, "view"]);
Route::get("/ticket-view-all/{company}", [ticket_controller::class, "viewAll"]);
Route::get("/ticket-view-last/{company}", [ticket_controller::class, "viewLast"]);
Route::get("/ticket-call-next/{service}/{assistant}", [ticket_controller::class, "next"]);
Route::put("/ticket-call-finished/{id}", [ticket_controller::class, "finished"]);
