@extends('layouts.app')

@section('content')
<div class="topbar">
        <div><div class="title">Settings</div><div class="subtitle">Configure monitoring and alert behavior</div></div>
      </div>
      <div class="panel settings-form">
        <div class="panel-head"><div class="panel-title">System Configuration & Settings</div></div>
        <div class="settings-row">
          <div><div class="settings-label">Real-time alerts</div><div class="settings-desc">Notify immediately when a threat is detected</div></div>
          <div class="toggle on" onclick="this.classList.toggle('on')"><div class="toggle-dot"></div></div>
        </div>
        <div class="settings-row">
          <div><div class="settings-label">Port scan detection</div><div class="settings-desc">Flag repeated connections across multiple ports</div></div>
          <div class="toggle on" onclick="this.classList.toggle('on')"><div class="toggle-dot"></div></div>
        </div>
        <div class="settings-row">
          <div><div class="settings-label">Flooding detection</div><div class="settings-desc">Flag abnormal packet rate from a single source</div></div>
          <div class="toggle on" onclick="this.classList.toggle('on')"><div class="toggle-dot"></div></div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head"><div class="panel-title">System Activity Logs</div></div>
        <table>
          <thead><tr><th>Timestamp</th><th>User</th><th>Action</th><th>Status</th></tr></thead>
          <tbody>
            <tr><td class="mono">2026-08-31 21:05:12</td><td>admin</td><td>Updated Detection Rules</td><td>Success</td></tr>
            <tr><td class="mono">2026-08-31 20:33:01</td><td>netadmin</td><td>Added IP to Watchlist</td><td>Success</td></tr>
            <tr><td class="mono">2026-08-31 19:12:44</td><td>secofficer</td><td>Exported Threat Report</td><td>Success</td></tr>
            <tr><td class="mono">2026-08-31 18:00:22</td><td>analyst1</td><td>Logged In</td><td>Success</td></tr>
          </tbody>
        </table>
      </div>

      <div class="panel">
        <div class="panel-head">
          <div class="panel-title">User Information</div>
        </div>
        <table>
          <thead><tr><th>UserID</th><th>Fullname</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>LastLogin</th></tr></thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td class="mono">{{ $user->id }}</td>
              <td>{{ $user->name ?? 'Admin User' }}</td>
              <td>{{ explode('@', $user->email)[0] }}</td>
              <td>{{ $user->email }}</td>
              <td>Administrator</td>
              <td>Active</td>
              <td class="mono">{{ $user->updated_at ?? 'Never' }}</td>
            </tr>
            @empty
            <tr><td colspan="7">No users found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="panel">
        <div class="panel-head">
          <div class="panel-title">Network Device Inventory</div>
        </div>
        <table>
          <thead><tr><th>DeviceID</th><th>Hostname</th><th>IP Address</th><th>MAC Address</th><th>Vendor</th><th>Device Type</th><th>Status</th><th>Last Seen</th></tr></thead>
          <tbody>
            <tr><td colspan="8" style="text-align:center; padding: 20px;">No network devices discovered yet.</td></tr>
          </tbody>
        </table>
      </div>
@endsection
