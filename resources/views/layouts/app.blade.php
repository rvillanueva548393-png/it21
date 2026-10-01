<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>NetSentinel</title>
<link rel="icon" href="{{ asset('images/netsentinel_icon.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ asset('css/netsentinel.css') }}">

</head>
<body>

<div id="view-app" class="active">
<div class="sidebar">

    <div class="brand">
      <svg width="30" height="30" viewBox="60 30 380 380" style="flex-shrink:0;">
        <defs>
          <linearGradient id="shieldGradSide" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#1E293B" />
            <stop offset="100%" stop-color="#0F172A" />
          </linearGradient>
        </defs>
        <path d="M 250,50 L 410,100 C 410,280 340,380 250,430 C 160,380 90,280 90,100 Z" fill="url(#shieldGradSide)" stroke="#0EA5E9" stroke-width="8"/>
        <circle cx="250" cy="220" r="70" fill="none" stroke="#0EA5E9" stroke-width="6" stroke-dasharray="14,10" opacity="0.85"/>
        <circle cx="250" cy="220" r="20" fill="#38BDF8"/>
      </svg>
      <div class="brand-text">Net<span>Sentinel</span></div>
    </div>

    <a href="{{ url('/overview') }}" class="nav-item {{ request()->is('overview') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Overview
    </a>
    <a href="{{ url('/threats') }}" class="nav-item {{ request()->is('threats') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 3 7v6c0 5 4 8 9 9 5-1 9-4 9-9V7z"/></svg>
      Threats
    </a>
    <a href="{{ url('/history') }}" class="nav-item {{ request()->is('history') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
      Packet History
    </a>
    <a href="{{ url('/watchlist') }}" class="nav-item {{ request()->is('watchlist') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 17H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3"/><path d="M9 17v4M15 17v4M9 21h6"/></svg>
      IP Watchlist
    </a>
    <a href="{{ url('/reports') }}" class="nav-item {{ request()->is('reports') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"/></svg>
      Reports
    </a>
          @if(auth()->check() && auth()->user()->email == 'admin@netsentinel.local')
          <a href="/settings" class="nav-item {{ request()->is('settings') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg> System Management
          </a>
          @endif

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="sidebar-foot" style="background:none; border:none; width:100%; text-decoration:none; text-align:left;">
        <div class="dot-live"></div>
        Sign out
      </button>
    </form>


    



    <div style="margin-top: auto; padding: 20px 0; border-top: 1px solid rgba(255,255,255,0.05);">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748B; margin-bottom: 10px;">Engine Control</div>
        <button id="toggleBtn" onclick="toggleCapture()" style="width: 100%; padding: 10px; background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s;">
            START CAPTURE
        </button>
    </div>

</div>

<div class="main">
  <div class="page active">
    @yield('content')
  </div>
</div>
</div>

<script src="{{ asset('js/netsentinel.js') }}"></script>
@yield('scripts')
<script>
// =========== Threat Auto-Refresh ===========
let currentThreatCount = null;
fetch('/api/threat-count').then(r=>r.json()).then(d=>{ currentThreatCount = d.count; });
setInterval(()=>{
  if(currentThreatCount !== null){
    fetch('/api/threat-count').then(r=>r.json()).then(d=>{
      if(d.count > currentThreatCount){ window.location.reload(); }
    });
  }
}, 3000);

// =========== Toggle Capture Button (persists across pages) ===========
function updateButtonUI() {
  const btn = document.getElementById('toggleBtn');
  if (!btn) return;
  const capturing = localStorage.getItem('netsentinel_capturing') === 'true';
  if (capturing) {
    btn.innerHTML = 'STOP CAPTURE';
    btn.style.background = 'rgba(239,68,68,0.1)';
    btn.style.color = '#EF4444';
    btn.style.borderColor = 'rgba(239,68,68,0.5)';
  } else {
    btn.innerHTML = 'START CAPTURE';
    btn.style.background = 'rgba(16,185,129,0.1)';
    btn.style.color = '#10B981';
    btn.style.borderColor = 'rgba(16,185,129,0.5)';
  }
}

function toggleCapture() {
  const capturing = localStorage.getItem('netsentinel_capturing') === 'true';
  const newState = !capturing;
  localStorage.setItem('netsentinel_capturing', newState);
  updateButtonUI();
  if (newState) {
    fetch('/api/engine/start').then(r=>r.json()).catch(()=>{});
  } else {
    fetch('/api/engine/stop').catch(()=>{});
  }
}

document.addEventListener('DOMContentLoaded', updateButtonUI);
</script>
</body>
</html>
