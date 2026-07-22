<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['project_name' => config('app.name'), 'version' => app()->version()]);
});

Route::get('/login', function(Request $request) {
    return "Hello World";
})->name('login');