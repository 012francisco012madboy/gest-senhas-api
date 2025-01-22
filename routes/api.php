<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* USUÁRIO */

Route::get("/sign-up", function (Request $request) {
    return "Aaaaa";
});
