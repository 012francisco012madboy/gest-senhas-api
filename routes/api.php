<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auth_controller;
use App\Http\Controllers\user_controller;
use App\Http\Controllers\counter_controller;
use App\Http\Controllers\service_controller;
use App\Http\Controllers\counter_service_controller;
use App\Http\Controllers\ticket_controller;
use App\Http\Controllers\extra_controller;

/* AUTH */
Route::post("/login", [auth_controller::class, "login"]);

/* SERVICE */
Route::get("/service/active", [service_controller::class, "active"]);
    
/* TICKET */
Route::get("/ticket", [ticket_controller::class, "index"]);
Route::post("/ticket", [ticket_controller::class, "store"]);


Route::middleware('auth:api')->group(function (){
    /* AUTH */
    Route::get("/user", [auth_controller::class, "index"]);
    Route::post("/user/out", [auth_controller::class, "out"]);
    Route::get("/user/refresh", [auth_controller::class, "refresh"]);
    
    /* USER */
    Route::post("/user", [user_controller::class, "store"]);
    Route::patch("/user", [user_controller::class, "update"]);
    Route::get("/user/list", [user_controller::class, "list"]);
    Route::delete("/user/{id}", [user_controller::class, "delete"]);

    /* SERVICE */
    Route::get("/service", [service_controller::class, "index"]);
    Route::post("/service", [service_controller::class, "store"]);
    Route::patch("/service", [service_controller::class, "update"]);
    Route::delete("/service/{id}", [service_controller::class, "delete"]);

    /* BALCÃO */
    Route::get("/counter", [counter_controller::class, "index"]);
    Route::get("/counter/active", [counter_controller::class, "active"]);
    Route::post("/counter", [counter_controller::class, "store"]);
    Route::patch("/counter", [counter_controller::class, "update"]);
    Route::delete("/counter/{id}", [counter_controller::class, "delete"]);
    
    /* COUNTER SERVICE */    
    Route::get("/counter/service", [counter_service_controller::class, "index"]);
    Route::post("/counter/service", [counter_service_controller::class, "store"]);
    Route::patch("/counter/service", [counter_service_controller::class, "update"]);
    Route::delete("/counter/service/{id}", [counter_service_controller::class, "delete"]);
    
    /* EXTRA */    
    Route::get("/count", [extra_controller::class, "count"]);
});
