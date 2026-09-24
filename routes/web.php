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
        
        // Calculate accurate live signal (packets per second over the last 5 minutes)
        $packetsLast5Min = \App\Models\PacketLog::where('created_at', '>=', now()->subMinutes(5))->count();
        // Since we only log 10% of packets in capture.py to save database load, we multiply by 10 to get the true rate
        $livePacketRate = round(($packetsLast5Min * 10) / 300, 1);
        if ($livePacketRate < 0.1) $livePacketRate = 0;

        return view('overview', compact('packetCount', 'threatCount', 'recentThreats', 'livePacketRate'));
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
        $watchlists = \App\Models\IpWatchlist::all();
        return view('watchlist', compact('watchlists'));
    });

    Route::post('/watchlist', function (\Illuminate\Http\Request $request) {
        \App\Models\IpWatchlist::create(['ip_address' => $request->ip_address, 'reason' => $request->reason]);
        return redirect('/watchlist');
    });

    Route::post('/watchlist/delete/{id}', function ($id) {
        \App\Models\IpWatchlist::destroy($id);
        return redirect('/watchlist');
    });

Route::get('/reports', function () {
    return view('reports');
});

    Route::get('/settings', function () {
        $users = \App\Models\User::all();
        $devices = \App\Models\NetworkDevice::orderBy('last_seen', 'desc')->get();
        $path = storage_path('app/settings.json');
        if (!file_exists($path)) {
            file_put_contents($path, json_encode(['realtime_alerts' => true, 'port_scan' => true, 'flooding' => true]));
        }
        $settings = json_decode(file_get_contents($path), true);
        return view('settings', compact('users', 'settings', 'devices'));
    });
});

Route::get("/reports/download", function () {
    $threats = \App\Models\ThreatLog::all();
    $csvFileName = "netsentinel_threat_report.csv";
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$csvFileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];
    $handle = fopen("php://output", "w");
    ob_start();
    fputcsv($handle, ["ID", "Attack Type", "Severity", "Attacker IP", "Victim IP", "Time", "Description"]);
    foreach ($threats as $threat) {
        fputcsv($handle, [$threat->id, $threat->attack_type, $threat->severity, $threat->attacker_ip, $threat->victim_ip, $threat->created_at, $threat->description]);
    }
    fclose($handle);
    return response()->stream(function () { echo ob_get_clean(); }, 200, $headers);
});



Route::get('/api/settings', function () {
    $path = storage_path('app/settings.json');
    if (!file_exists($path)) { file_put_contents($path, json_encode(['realtime_alerts' => true, 'port_scan' => true, 'flooding' => true])); }
    return response()->file($path);
});
Route::post('/settings/toggle', function (\Illuminate\Http\Request $request) {
    $path = storage_path('app/settings.json');
    $settings = json_decode(file_get_contents($path), true);
    $settings[$request->key] = !$settings[$request->key];
    file_put_contents($path, json_encode($settings));
    return response()->json(['status' => 'success', 'state' => $settings[$request->key]]);
});


Route::get('/api/devices', function (\Illuminate\Http\Request $request) {
    \App\Models\NetworkDevice::updateOrCreate(
        ['mac_address' => $request->mac_address],
        ['ip_address' => $request->ip_address, 'last_seen' => now()]
    );
    return response()->json(['status' => 'success']);
});


Route::get('/api/watchlist', function () {
    return response()->json(\App\Models\IpWatchlist::pluck('ip_address')->toArray());
});

Route::get('/api/watchlist/auto', function (\Illuminate\Http\Request $request) {
    \App\Models\IpWatchlist::firstOrCreate(
        ['ip_address' => $request->ip_address],
        ['reason' => 'AUTO-BAN: ' . $request->reason]
    );
    return response()->json(['status' => 'success']);
});

