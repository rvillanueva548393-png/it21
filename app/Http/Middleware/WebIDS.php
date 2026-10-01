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

        // If the Analyst MANUALLY added them to the Watchlist, THEN we block them.
        if (IpWatchlist::where('ip_address', $ip)->exists()) {
            return response("<h1>ACCESS DENIED</h1><p>Your IP ($ip) has been permanently banned by NetSentinel IPS.</p>", 403);
        }

        $patterns = [
            'SQL Injection' => '/(\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER)\b)|(\' OR 1=1)/i',
            'Cross-Site Scripting (XSS)' => '/(<script>|javascript:|onerror=|onload=)/i',
            'Path Traversal' => '/(\.\.\/|\.\.\\\\|\/etc\/passwd|\/windows\/win.ini)/i'
        ];

        $inputData = urldecode($request->fullUrl() . ' ' . json_encode($request->all()));

        foreach ($patterns as $attackType => $pattern) {
            if (preg_match($pattern, $inputData)) {
                
                // Threat Detected - LOG IT ONLY (IDS Mode / Monitoring Mode)
                ThreatLog::create([
                    'attack_type' => 'Web Attack: ' . $attackType,
                    'severity' => 'Critical',
                    'attacker_ip' => $ip,
                    'victim_ip' => $request->server('SERVER_ADDR', '127.0.0.1'),
                    'attacker_mac' => 'Cloud Web',
                    'status' => 'Monitoring', // Changed from Auto-Blocked
                    'description' => 'Malicious payload detected: ' . $request->fullUrl()
                ]);

                // We break the loop but WE DO NOT BLOCK the request.
                // We let the hacker think they are undetected so the Analyst can watch them.
                break;
            }
        }

        return $next($request);
    }
}
