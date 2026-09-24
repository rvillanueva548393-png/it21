@extends('layouts.app')

@section('content')
<div class="topbar">
        <div>
          <div class="title">Network Overview</div>
          <div class="subtitle">Monitoring eth0 · Real-time packet capture &amp; threat detection</div>
        </div>
        <div class="interface-pill mono">● eth0 — {{ $livePacketRate }} pkt/s</div>
      </div>

      <div class="pulse-card">
        <div class="pulse-label mono">LIVE&nbsp;SIGNAL</div>
        <div class="pulse-canvas-wrap"><canvas id="pulse" width="700" height="44" style="width:100%; height:44px;"></canvas></div>
        <div class="pulse-stat">{{ $livePacketRate }}<small>packets / sec</small></div>
      </div>

      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Packets Captured</div>
            <div class="icon-badge" style="background:rgba(91,140,255,0.12);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#5B8CFF" stroke-width="2"><path d="M3 12h4l3 8 4-16 3 8h4"/></svg></div></div>
          <div class="stat-value mono">{{ number_format($packetCount) }}</div>
          <div class="stat-delta up mono">Live monitoring active</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Active Threats</div>
            <div class="icon-badge" style="background:rgba(247,110,126,0.12);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#F76E7E" stroke-width="2"><path d="M12 2 3 7v6c0 5 4 8 9 9 5-1 9-4 9-9V7z"/></svg></div></div>
          <div class="stat-value mono">{{ number_format($threatCount) }}</div>
          <div class="stat-delta up mono">Database synced</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Flagged IPs</div>
            <div class="icon-badge" style="background:rgba(245,185,77,0.12);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#F5B94D" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg></div></div>
          <div class="stat-value mono">6</div>
          <div class="stat-delta mono" style="color:var(--text-dim);">— no change</div>
        </div>
        <div class="stat-card">
          <div class="stat-top"><div class="stat-label">Uptime</div>
            <div class="icon-badge" style="background:rgba(79,224,199,0.12);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4FE0C7" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div></div>
          <div class="stat-value mono">99.8%</div>
          <div class="stat-delta down mono">▼ 0.1% vs last week</div>
        </div>
      </div>

      <div class="grid-2">
        <div class="panel" style="margin-bottom:0;">
          <div class="panel-head">
            <div><div class="panel-title">Traffic Volume</div><div class="panel-sub">Packets per minute, last 30 minutes</div></div>
            <div class="legend"><span><i style="background:var(--blue)"></i>Inbound</span><span><i style="background:var(--teal)"></i>Outbound</span></div>
          </div>
          <canvas id="traffic" width="700" height="200" style="width:100%; height:200px;"></canvas>
        </div>

        <div class="panel" style="margin-bottom:0;">
          <div class="panel-head"><div><div class="panel-title">Recent Threats</div><div class="panel-sub">Live detection feed</div></div></div>
          
          @forelse($recentThreats as $rt)
          <div class="threat-item">
            <div class="sev-dot {{ strtolower($rt->severity) == 'critical' || strtolower($rt->severity) == 'high' ? 'sev-high' : 'sev-med' }}"></div>
            <div class="threat-main">
              <div class="threat-title-row">
                <div class="threat-name">{{ $rt->attack_type }}</div>
                <div class="threat-time">{{ $rt->created_at->diffForHumans(null, true, true) }}</div>
              </div>
              <div class="threat-meta">{{ $rt->attacker_ip }} &rarr; {{ $rt->description }}</div>
            </div>
          </div>
          @empty
          <div style="padding: 20px; text-align: center; color: var(--text-dim);">No recent threats detected.</div>
          @endforelse
          
        </div>
      </div>
@endsection
