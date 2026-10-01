@extends('layouts.app')

@section('content')
<div class="topbar">
  <div><div class="title">Threats</div><div class="subtitle">All detected suspicious activity</div></div>
</div>

{{-- ANALYZE MODAL --}}
<div id="analyzeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#0F172A; border:1px solid #1E293B; border-radius:12px; padding:30px; max-width:600px; width:90%; position:relative;">
    <button onclick="closeModal()" style="position:absolute; top:15px; right:15px; background:none; border:none; color:#94A3B8; font-size:20px; cursor:pointer;">✕</button>
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
      <div id="modalIcon" style="font-size:30px;"></div>
      <div>
        <div id="modalTitle" style="font-size:18px; font-weight:bold; color:#F1F5F9;"></div>
        <div id="modalSeverityBadge"></div>
      </div>
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
      <div style="background:#1E293B; border-radius:8px; padding:14px;">
        <div style="font-size:11px; color:#64748B; text-transform:uppercase; margin-bottom:4px;">Attacker IP</div>
        <div id="modalAttackerIP" style="color:#F76E7E; font-family:monospace; font-weight:bold;"></div>
      </div>
      <div style="background:#1E293B; border-radius:8px; padding:14px;">
        <div style="font-size:11px; color:#64748B; text-transform:uppercase; margin-bottom:4px;">Victim IP</div>
        <div id="modalVictimIP" style="color:#4FE0C7; font-family:monospace;"></div>
      </div>
      <div style="background:#1E293B; border-radius:8px; padding:14px;">
        <div style="font-size:11px; color:#64748B; text-transform:uppercase; margin-bottom:4px;">MAC Address</div>
        <div id="modalMAC" style="color:#94A3B8; font-family:monospace; font-size:12px;"></div>
      </div>
      <div style="background:#1E293B; border-radius:8px; padding:14px;">
        <div style="font-size:11px; color:#64748B; text-transform:uppercase; margin-bottom:4px;">Detection Time</div>
        <div id="modalTime" style="color:#94A3B8; font-family:monospace; font-size:12px;"></div>
      </div>
    </div>
    <div style="background:#1E293B; border-radius:8px; padding:14px; margin-bottom:20px;">
      <div style="font-size:11px; color:#64748B; text-transform:uppercase; margin-bottom:6px;">Description</div>
      <div id="modalDesc" style="color:#CBD5E1;"></div>
    </div>
    <div style="background:#1E293B; border-radius:8px; padding:14px; margin-bottom:20px;">
      <div style="font-size:11px; color:#64748B; text-transform:uppercase; margin-bottom:6px;">⚠ Analyst Recommendation</div>
      <div id="modalRecommendation" style="color:#FCD34D; line-height:1.6;"></div>
    </div>
    <div style="display:flex; gap:10px;">
      <button onclick="banFromModal()" id="banBtn" style="flex:1; padding:10px; background:rgba(239,68,68,0.15); color:#EF4444; border:1px solid rgba(239,68,68,0.4); border-radius:6px; cursor:pointer; font-weight:bold;">
        🚫 Add to Watchlist
      </button>
      <button onclick="closeModal()" style="flex:1; padding:10px; background:rgba(100,116,139,0.15); color:#94A3B8; border:1px solid rgba(100,116,139,0.4); border-radius:6px; cursor:pointer;">
        Close
      </button>
    </div>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <div class="panel-title">Threat Detection Logs</div>
  </div>
  <table>
    <thead><tr><th>AttackID</th><th>Attack Type</th><th>Severity</th><th>Attacker IP</th><th>Victim IP</th><th>Attacker MAC</th><th>Detection Time</th><th>Status</th><th>Description</th><th>Action</th></tr></thead>
    <tbody>
      @forelse($threats as $threat)
      <tr>
        <td class="mono">{{ $threat->id }}</td>
        <td>{{ $threat->attack_type }}</td>
        <td><span class="badge badge-{{ strtolower($threat->severity) == 'critical' || strtolower($threat->severity) == 'high' ? 'high' : (strtolower($threat->severity) == 'medium' ? 'med' : 'low') }}">{{ $threat->severity }}</span></td>
        <td class="mono" style="color:#F76E7E;">{{ $threat->attacker_ip }}</td>
        <td class="mono">{{ $threat->victim_ip ?? 'N/A' }}</td>
        <td class="mono">{{ $threat->attacker_mac ?? 'Unknown' }}</td>
        <td class="mono">{{ $threat->created_at }}</td>
        <td>{{ $threat->status }}</td>
        <td>{{ $threat->description }}</td>
        <td>
          <button onclick="analyzeRow('{{ $threat->id }}','{{ $threat->attack_type }}','{{ $threat->severity }}','{{ $threat->attacker_ip }}','{{ $threat->victim_ip ?? 'N/A' }}','{{ $threat->attacker_mac ?? 'Unknown' }}','{{ $threat->created_at }}','{{ addslashes($threat->description) }}')"
            style="padding:5px 12px; background:rgba(79,224,199,0.1); color:#4FE0C7; border:1px solid rgba(79,224,199,0.4); border-radius:5px; cursor:pointer; font-size:12px; font-weight:bold; white-space:nowrap;">
            🔍 Analyze
          </button>
        </td>
      </tr>
      @empty
      <tr><td colspan="10" style="text-align:center; padding: 20px;">No threats detected yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="panel">
  <div class="panel-head">
    <div class="panel-title">Alert Logs</div>
  </div>
  <table>
    <thead><tr><th>AlertID</th><th>AttackID</th><th>Severity</th><th>Alert Message</th><th>Alert Time</th><th>Read Status</th><th>Acknowledged By</th></tr></thead>
    <tbody>
      @forelse($threats as $alert)
      <tr>
        <td class="mono">ALT-{{ $alert->id }}</td>
        <td class="mono">{{ $alert->id }}</td>
        <td><span class="badge badge-{{ strtolower($alert->severity) == 'critical' || strtolower($alert->severity) == 'high' ? 'high' : 'med' }}">{{ $alert->severity }}</span></td>
        <td>{{ $alert->attack_type }} detected from {{ $alert->attacker_ip }}</td>
        <td class="mono">{{ $alert->created_at }}</td>
        <td>Unread</td>
        <td>-</td>
      </tr>
      @empty
      <tr><td colspan="7" style="text-align:center; padding: 20px;">No alerts yet!</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection

@section('scripts')
<script>
let currentAttackerIP = '';

function analyzeRow(id, type, severity, attackerIP, victimIP, mac, time, desc) {
  currentAttackerIP = attackerIP;
  const sev = severity.toLowerCase();

  // Set icon and title
  const icons = { 'critical': '🔴', 'high': '🟠', 'medium': '🟡', 'low': '🟢' };
  document.getElementById('modalIcon').textContent = icons[sev] || '⚪';
  document.getElementById('modalTitle').textContent = '#' + id + ' — ' + type;
  document.getElementById('modalSeverityBadge').innerHTML = '<span class="badge badge-' + (sev === 'critical' || sev === 'high' ? 'high' : 'med') + '">' + severity + '</span>';

  document.getElementById('modalAttackerIP').textContent = attackerIP;
  document.getElementById('modalVictimIP').textContent = victimIP;
  document.getElementById('modalMAC').textContent = mac;
  document.getElementById('modalTime').textContent = time;
  document.getElementById('modalDesc').textContent = desc;

  // Smart recommendations based on attack type
  const recommendations = {
    'Port Scan': '1. The attacker is probing for open ports — this is typically the first phase of a cyberattack.\n2. Immediately add this IP to the Watchlist to block future intrusion attempts.\n3. Check your firewall rules and close any unnecessary open ports.\n4. Monitor for follow-up exploitation attempts from this IP.',
    'Unusual Port': '1. Traffic on this port is associated with unauthorized protocols (e.g., IRC botnets).\n2. This IP should be immediately banned and all traffic blocked.\n3. Investigate if any internal device initiated this connection.\n4. Report this to your network administrator for further forensic analysis.',
    'Suspicious Web Traffic': '1. This is outbound HTTPS traffic that could not be verified.\n2. Verify this is from a known and trusted application on your network.\n3. If the IP is unknown, add it to the Watchlist for monitoring.\n4. Check for installed malware that may be sending data externally.',
    'Watchlist Violation': '1. CRITICAL: This IP was previously flagged and is attempting to communicate again!\n2. This confirms a persistent and repeated attacker — take immediate action.\n3. Escalate to your security team for full forensic investigation.\n4. Consider blocking this IP at the router/firewall level permanently.',
  };

  let rec = recommendations[type] || '1. Review the description and assess the threat level.\n2. If the IP is unknown, add it to the Watchlist immediately.\n3. Investigate the source and destination of this traffic.\n4. Document findings and take appropriate remediation steps.';
  document.getElementById('modalRecommendation').textContent = rec;

  // Show modal
  const modal = document.getElementById('analyzeModal');
  modal.style.display = 'flex';
}

function closeModal() {
  document.getElementById('analyzeModal').style.display = 'none';
}

function banFromModal() {
  const reason = prompt('Enter a reason for banning ' + currentAttackerIP + ':');
  if (!reason) return;

  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/watchlist/add';
  form.innerHTML = `
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="ip_address" value="${currentAttackerIP}">
    <input type="hidden" name="reason" value="${reason}">
  `;
  document.body.appendChild(form);
  form.submit();
}

// Close modal if clicking outside
document.getElementById('analyzeModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});
</script>
@endsection
