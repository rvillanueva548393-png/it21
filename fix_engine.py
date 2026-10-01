import os
import re

with open('routes/web.php', 'r') as f:
    web = f.read()

engine_routes = """
Route::get('/api/engine/start', function () {
    pclose(popen('start "NetSentinelEngine" cmd /k "cd ' . base_path('python_capture') . ' && python capture.py"', 'r'));
    return response()->json(['status' => 'started']);
});

Route::get('/api/engine/stop', function () {
    exec('taskkill /FI "WINDOWTITLE eq NetSentinelEngine*" /T /F');
    return response()->json(['status' => 'stopped']);
});
"""
if "/api/engine/start" not in web:
    with open('routes/web.php', 'a') as f:
        f.write(engine_routes)


with open('resources/views/layouts/app.blade.php', 'r') as f:
    blade = f.read()

buttons = """
    <div style="margin-top: auto; padding: 20px 0; border-top: 1px solid rgba(255,255,255,0.05);">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748B; margin-bottom: 10px;">Engine Control</div>
        <button onclick="fetch('/api/engine/start').then(()=>alert('Python Capture Engine Started!'))" style="width: 100%; padding: 10px; background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; cursor: pointer; margin-bottom: 8px; font-weight: bold; transition: 0.2s;">
            ? Start Capture
        </button>
        <button onclick="fetch('/api/engine/stop').then(()=>alert('Python Capture Engine Stopped!'))" style="width: 100%; padding: 10px; background: rgba(239, 68, 68, 0.1); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.5); border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.2s;">
            ¦ Stop Capture
        </button>
    </div>
"""

if "Engine Control" not in blade:
    blade = re.sub(r'(</div>\s*<div class="main">)', r'\n' + buttons + r'\n\1', blade)
    with open('resources/views/layouts/app.blade.php', 'w') as f:
        f.write(blade)
