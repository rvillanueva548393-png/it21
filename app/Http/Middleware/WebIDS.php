<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ThreatLog;
use App\Models\IpWatchlist;
use Illuminate\Support\Facades\DB;

class WebIDS
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        // 1. Check if IP is already in Watchlist (Auto-Ban)
        if (IpWatchlist::where('ip_address', $ip)->exists()) {
            return response("<h1>ACCESS DENIED</h1><p>Your IP ($ip) has been permanently banned by NetSentinel IPS.</p>", 403);
        }

        // 2. Define malicious patterns (SQLi, XSS, Path Traversal)
        $patterns = [
            'SQL Injection' => '/(\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER)\b)|(\' OR 1=1)/i',
            'Cross-Site Scripting (XSS)' => '/(<script>|javascript:|onerror=|onload=)/i',
            'Path Traversal' => '/(\.\.\/|\.\.\\\\|\/etc\/passwd|\/windows\/win.ini)/i'
        ];

        // Combine all input data (URL, Query String, Form Data)
        $inputData = urldecode($request->fullUrl() . ' ' . json_encode($request->all()));

        foreach ($patterns as $attackType => $pattern) {
            if (preg_match($pattern, $inputData)) {
                // Threat Detected!
                
                // Log to database
                ThreatLog::create([
                    'attack_type' => 'Web Attack: ' . $attackType,
                    'severity' => 'Critical',
                    'attacker_ip' => $ip,
                    'victim_ip' => $request->server('SERVER_ADDR', '127.0.0.1'),
                    'attacker_mac' => 'Cloud Web',
                    'status' => 'Auto-Blocked',
                    'description' => 'Malicious payload detected: ' . $request->fullUrl()
                ]);

                // Auto-Ban the IP
                IpWatchlist::create([
                    'ip_address' => $ip,
                    'reason' => 'Detected performing ' . $attackType
                ]);

                // Block request
                return response("<h1>INTRUSION DETECTED</h1><p>Malicious activity logged. Your IP has been banned.</p>", 403);
            }
        }

        return $next($request);
    }
}
