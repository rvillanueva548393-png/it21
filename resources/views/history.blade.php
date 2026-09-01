@extends('layouts.app')

@section('content')
<div class="topbar">
        <div><div class="title">Packet History</div><div class="subtitle">Full captured packet log</div></div>
      </div>
      <div class="panel">
        <div class="panel-head">
          <div style="display:flex; gap:8px;">
            <div class="panel-title">Packet Capture Logs</div>
          </div>
        </div>
        <table>
          <thead><tr><th>PacketID</th><th>Timestamp</th><th>Source IP</th><th>Destination IP</th><th>Protocol</th><th>Source MAC</th><th>Dest MAC</th><th>Length</th></tr></thead>
          <tbody>
            @forelse($packets as $packet)
            <tr>
              <td class="mono">{{ $packet->id }}</td>
              <td class="mono">{{ $packet->created_at }}</td>
              <td class="mono">{{ $packet->source_ip }}</td>
              <td class="mono">{{ $packet->dest_ip }}</td>
              <td class="mono">{{ $packet->protocol }}</td>
              <td class="mono">{{ $packet->source_mac }}</td>
              <td class="mono">{{ $packet->dest_mac }}</td>
              <td class="mono">{{ $packet->length }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center; padding: 20px;">No packets logged yet! Start the Python capture script.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
@endsection
