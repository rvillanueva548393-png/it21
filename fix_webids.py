code = """<?php

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

        if (IpWatchlist::where('ip_address', $ip)->exists()) {
            return response("<h1>ACCESS DENIED</h1><p>Your IP ($ip) has been permanently banned by NetSentinel IPS.</p>", 403);
        }

        $patterns = [
            'SQL Injection' => '/(\\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER)\\b)|(\\\' OR 1=1)/i',
            'Cross-Site Scripting (XSS)' => '/(<script>|javascript:|onerror=|onload=)/i',
            'Path Traversal' => '/(\\.\\.\\/|\\.\\.\\\\|\\/etc\\/passwd|\\/windows\\/win.ini)/i'
        ];

        $inputData = urldecode($request->fullUrl() . ' ' . json_encode($request->all()));

        foreach ($patterns as $attackType => $pattern) {
            if (preg_match($pattern, $inputData)) {
                
                ThreatLog::create([
                    'attack_type' => 'Web Attack: ' . $attackType,
                    'severity' => 'Critical',
                    'attacker_ip' => $ip,
                    'victim_ip' => $request->server('SERVER_ADDR', '127.0.0.1'),
                    'attacker_mac' => 'Cloud Web',
                    'status' => 'Auto-Blocked',
                    'description' => 'Malicious payload detected: ' . $request->fullUrl()
                ]);

                IpWatchlist::create([
                    'ip_address' => $ip,
                    'reason' => 'Detected performing ' . $attackType
                ]);

                return response("<h1>INTRUSION DETECTED</h1><p>Malicious activity logged. Your IP has been banned.</p>", 403);
            }
        }

        return $next($request);
    }
}
"""

with open("app/Http/Middleware/WebIDS.php", "w", encoding="utf-8") as f:
    f.write(code)
