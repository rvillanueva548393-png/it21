@extends('layouts.app')
@section('content')
<div class="topbar">
  <div><div class="title">Reports</div><div class="subtitle">Generated traffic & threat summaries</div></div>
</div>
<div style="padding: 24px;">
  <div class="panel">
    <div class="threat-item" style="border:none; padding:12px 0;">
      <div class="icon-badge" style="background:rgba(91,140,255,0.12);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#5B8CFF" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
      <div class="threat-main">
        <div class="threat-title-row"><div class="threat-name">Complete Threat Incident Report</div></div>
        <div class="threat-meta">Dynamically generated CSV containing all historical threat logs</div>
      </div>
      <a href="/reports/download" style="text-decoration:none;">
        <div class="status-badge" style="cursor:pointer; background:rgba(79,224,199,0.1); color:#4FE0C7; border:1px solid rgba(79,224,199,0.2);">Download CSV</div>
      </a>
    </div>
  </div>
</div>
@endsection
