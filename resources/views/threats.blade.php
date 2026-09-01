@extends('layouts.app')

@section('content')
<div class="topbar">
        <div><div class="title">Threats</div><div class="subtitle">All detected suspicious activity</div></div>
      </div>
      <div class="panel">
        <div class="panel-head">
          <div class="panel-title">Threat Detection Logs</div>
        </div>
        <table>
          <thead><tr><th>AttackID</th><th>Attack Type</th><th>Severity</th><th>Attacker IP</th><th>Victim IP</th><th>Attacker MAC</th><th>Detection Time</th><th>Status</th><th>Description</th></tr></thead>
          <tbody>
            @forelse($threats as $threat)
            <tr>
              <td class="mono">{{ $threat->id }}</td>
              <td>{{ $threat->attack_type }}</td>
              <td><span class="badge badge-{{ strtolower($threat->severity) == 'critical' || strtolower($threat->severity) == 'high' ? 'high' : (strtolower($threat->severity) == 'medium' ? 'med' : 'low') }}">{{ $threat->severity }}</span></td>
              <td class="mono">{{ $threat->attacker_ip }}</td>
              <td class="mono">{{ $threat->victim_ip ?? 'N/A' }}</td>
              <td class="mono">{{ $threat->attacker_mac ?? 'Unknown' }}</td>
              <td class="mono">{{ $threat->created_at }}</td>
              <td>{{ $threat->status }}</td>
              <td>{{ $threat->description }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding: 20px;">No threats detected yet! Run the Python script.</td></tr>
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
