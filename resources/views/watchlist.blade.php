@extends('layouts.app')
@section('content')
<div class="topbar">
  <div><div class="title">IP Watchlist</div><div class="subtitle">Flagged and monitored addresses</div></div>
</div>

<div class="panel" style="margin-bottom: 20px;">
  <div class="panel-head"><div class="panel-title">Add New Suspicious IP</div></div>
  <form action="/watchlist" method="POST" style="padding: 15px; display: flex; gap: 15px; align-items: center;">
    @csrf
    <input type="text" name="ip_address" placeholder="e.g. 192.168.1.50" style="padding: 8px; border-radius: 4px; border: 1px solid var(--border); background: var(--bg); color: var(--text); flex: 1;" required>
    <input type="text" name="reason" placeholder="Reason (e.g. Known Port Scanner)" style="padding: 8px; border-radius: 4px; border: 1px solid var(--border); background: var(--bg); color: var(--text); flex: 2;" required>
    <button type="submit" style="padding: 8px 16px; background: #F76E7E; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Flag IP Address</button>
  </form>
</div>

<div class="panel">
  <table>
    <thead><tr><th>ID</th><th>IP Address</th><th>Reason</th><th>Date Flagged</th><th>Action</th></tr></thead>
    <tbody>
      @forelse($watchlists as $ip)
      <tr>
        <td class="mono">{{ $ip->id }}</td>
        <td class="mono" style="color: #F76E7E; font-weight: bold;">{{ $ip->ip_address }}</td>
        <td>{{ $ip->reason }}</td>
        <td class="mono">{{ $ip->created_at }}</td>
        <td>
          <form action="/watchlist/delete/{{ $ip->id }}" method="POST">
            @csrf
            <button type="submit" style="background: none; border: 1px solid var(--border); color: var(--text); padding: 4px 8px; cursor: pointer; border-radius: 4px;">Remove</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" style="text-align: center; padding: 20px; color: var(--text-dim);">No IP addresses are currently flagged on the watchlist.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
