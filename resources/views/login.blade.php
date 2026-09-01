<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>NetSentinel</title>
<link rel="icon" href="{{ asset('images/netsentinel_icon.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ asset('css/netsentinel.css') }}">

</head>
<body>

<div id="view-login" class="active">
  <canvas id="login-bg"></canvas>
  <div class="login-card">
    <div class="login-brand" style="flex-direction:column; align-items:center; text-align:center; gap:4px;">
      <svg width="72" height="72" viewBox="60 30 380 380" style="margin-bottom:6px;">
        <defs>
          <linearGradient id="shieldGradLogin" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#1E293B" />
            <stop offset="100%" stop-color="#0F172A" />
          </linearGradient>
          <filter id="glowLogin" x="-20%" y="-20%" width="140%" height="140%">
            <feGaussianBlur stdDeviation="4" result="blur" />
            <feComposite in="SourceGraphic" in2="blur" operator="over" />
          </filter>
        </defs>
        <path d="M 250,50 L 410,100 C 410,280 340,380 250,430 C 160,380 90,280 90,100 Z" fill="url(#shieldGradLogin)" stroke="#0EA5E9" stroke-width="6" filter="url(#glowLogin)"/>
        <path d="M 250,68 L 390,112 C 390,265 328,355 250,400 C 172,355 110,265 110,112 Z" fill="#0B132B" stroke="#38BDF8" stroke-width="2" opacity="0.8"/>
        <circle cx="250" cy="220" r="70" fill="none" stroke="#0EA5E9" stroke-width="3" stroke-dasharray="8,6" opacity="0.85"/>
        <circle cx="250" cy="220" r="45" fill="#0284C7" fill-opacity="0.15" stroke="#38BDF8" stroke-width="2"/>
        <circle cx="250" cy="220" r="12" fill="#38BDF8" filter="url(#glowLogin)"/>
        <line x1="250" y1="220" x2="190" y2="150" stroke="#38BDF8" stroke-width="3"/>
        <line x1="250" y1="220" x2="310" y2="150" stroke="#38BDF8" stroke-width="3"/>
        <line x1="250" y1="220" x2="180" y2="280" stroke="#38BDF8" stroke-width="3"/>
        <line x1="250" y1="220" x2="320" y2="280" stroke="#0EA5E9" stroke-width="3"/>
        <circle cx="190" cy="150" r="9" fill="#38BDF8" stroke="#0B1120" stroke-width="2"/>
        <circle cx="310" cy="150" r="9" fill="#38BDF8" stroke="#0B1120" stroke-width="2"/>
        <circle cx="180" cy="280" r="9" fill="#38BDF8" stroke="#0B1120" stroke-width="2"/>
        <circle cx="320" cy="280" r="10" fill="#EF4444" stroke="#0B1120" stroke-width="2" filter="url(#glowLogin)"/>
      </svg>
      <div class="brand-text">Net<span>Sentinel</span></div>
    </div>
    <div class="login-sub mono">Sign in to access the monitoring dashboard</div>
    <form method="POST" action="{{ url('/login') }}">
      @csrf
      <div class="field">
        <label>Email / Username</label>
        <input type="email" name="email" value="{{ old('email', 'analyst01@netsentinel.local') }}" required autofocus />
        @error('email')
          <div style="color:var(--coral); font-size:11px; margin-top:4px;">{{ $message }}</div>
        @enderror
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" value="password" required />
      </div>
      <button type="submit" class="btn-primary">Sign In</button>
    </form>
    <div class="login-foot mono">NetSentinel v1.0 — Threat &amp; Intrusion Detection</div>
  </div>
</div>

<script src="{{ asset('js/netsentinel.js') }}"></script>
</body>
</html>
