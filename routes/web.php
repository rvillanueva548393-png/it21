<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// NetSentinel routes
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/overview', function () {
        $packetCount = \App\Models\PacketLog::count();
        $threatCount = \App\Models\ThreatLog::count();
        $recentThreats = \App\Models\ThreatLog::orderBy('created_at', 'desc')->take(5)->get();
        return view('overview', compact('packetCount', 'threatCount', 'recentThreats'));
    });

    Route::get('/threats', function () {
        $threats = \App\Models\ThreatLog::orderBy('created_at', 'desc')->get();
        return view('threats', compact('threats'));
    });

    Route::get('/history', function () {
        $packets = \App\Models\PacketLog::orderBy('created_at', 'desc')->take(100)->get();
        return view('history', compact('packets'));
    });

Route::get('/watchlist', function () {
    return view('watchlist');
});

Route::get('/reports', function () {
    return view('reports');
});

    Route::get('/settings', function () {
        $users = \App\Models\User::all();
        return view('settings', compact('users'));
    });
});
