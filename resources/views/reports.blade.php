@extends('layouts.app')

@section('content')
<div class="topbar">
        <div><div class="title">Reports</div><div class="subtitle">Generated traffic &amp; threat summaries</div></div>
      </div>
      <div class="panel">
        <div class="report-row">
          <div style="display:flex; align-items:center;">
            <div class="report-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#5B8CFF" stroke-width="2"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"/></svg></div>
            <div><div class="report-name">Weekly Threat Summary</div><div class="report-meta">Aug 17 – Aug 23, 2026 · PDF</div></div>
          </div>
          <div class="btn-ghost">Download</div>
        </div>
        <div class="report-row">
          <div style="display:flex; align-items:center;">
            <div class="report-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#5B8CFF" stroke-width="2"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"/></svg></div>
            <div><div class="report-name">Port Scan Incident Report</div><div class="report-meta">Aug 24, 2026 · CSV</div></div>
          </div>
          <div class="btn-ghost">Download</div>
        </div>
        <div class="report-row">
          <div style="display:flex; align-items:center;">
            <div class="report-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#5B8CFF" stroke-width="2"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"/></svg></div>
            <div><div class="report-name">Monthly Traffic Overview</div><div class="report-meta">July 2026 · PDF</div></div>
          </div>
          <div class="btn-ghost">Download</div>
        </div>
      </div>
@endsection
