<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ThreatLog;
use App\Models\IpWatchlist;

class WebIDS
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        // Block if manually added to Watchlist by Analyst
        if (IpWatchlist::where('ip_address', $ip)->exists()) {
            return response("<h1 style='font-family:monospace;color:red;'>ACCESS DENIED</h1><p>Your IP ($ip) has been permanently banned by NetSentinel IPS.</p>", 403);
        }

        // Load settings from settings.json
        $settingsPath = storage_path('app/settings.json');
        $settings = file_exists($settingsPath)
            ? json_decode(file_get_contents($settingsPath), true)
            : ['realtime_alerts' => true, 'port_scan' => true, 'flooding' => true];

        // If real-time alerts are OFF, skip all detection silently
        if (empty($settings['realtime_alerts'])) {
            return $next($request);
        }

        // Base attack patterns (always checked if alerts are ON)
        $patterns = [
            'SQL Injection' => '/(\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER)\b)|(\'\ OR 1=1)/i',
            'Cross-Site Scripting (XSS)' => '/(<script>|javascript:|onerror=|onload=)/i',
        ];

        // Path Traversal is grouped under Port Scan detection toggle
        if (!empty($settings['port_scan'])) {
            $patterns['Path Traversal'] = '/(\.\.\/|\.\.\\\\|\/etc\/passwd|\/windows\/win.ini)/i';
        }

        // Flooding detection toggle controls repeated request detection
        // (currently a placeholder - full flood detection requires session tracking)
        if (!empty($settings['flooding'])) {
            // Future: add flood/DDoS rate detection here
        }

        $inputData = urldecode($request->fullUrl() . ' ' . json_encode($request->all()));

        foreach ($patterns as $attackType => $pattern) {
            if (preg_match($pattern, $inputData)) {

                // IDS Mode: Log silently, do NOT block attacker yet
                ThreatLog::create([
                    'attack_type' => 'Web Attack: ' . $attackType,
                    'severity' => 'Critical',
                    'attacker_ip' => $ip,
                    'victim_ip' => $request->server('SERVER_ADDR', '127.0.0.1'),
                    'attacker_mac' => 'Cloud Web',
                    'status' => 'Monitoring',
                    'description' => 'Malicious payload detected: ' . $request->fullUrl()
                ]);

                break; // Log first match only
            }
        }

        return $next($request);
    }
}
