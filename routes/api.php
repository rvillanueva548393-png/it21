<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\PacketLog;
use App\Models\ThreatLog;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/packets', function (Request $request) {
    PacketLog::create($request->all());
    return response()->json(['status' => 'success']);
});

Route::post('/threats', function (Request $request) {
    ThreatLog::create($request->all());
    return response()->json(['status' => 'success']);
});
