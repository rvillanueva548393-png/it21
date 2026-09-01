@extends('layouts.app')

@section('content')
<div class="topbar">
        <div><div class="title">IP Watchlist</div><div class="subtitle">Flagged and monitored addresses</div></div>
      </div>
      <div class="panel">
        <div class="watch-grid">
           <div style="padding: 40px; text-align: center; color: var(--text-dim); grid-column: 1 / -1;">No IP addresses are currently flagged on the watchlist.</div>
        </div>
      </div>
@endsection
