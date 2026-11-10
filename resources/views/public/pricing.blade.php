@extends('public.layout', ['appName' => $appName, 'title' => 'Pricing', 'author' => $author])
@section('content')
<main><div class="wrap">
  <h1 style="font-family:Georgia,serif">Pilot packaging for HVAC contractors</h1>
  <p class="muted">Hypothesis from opportunity research: Pilot $199–$799/mo.</p>
  <div class="grid" style="margin-top:1.4rem">
    <div class="card"><h3>Starter Collections</h3><p>$199/mo — Service requests, job tickets, AI invoice drafts, approval queue, email reminders.</p></div>
    <div class="card"><h3>Growth Ops</h3><p>$499/mo — AR aging, QuickBooks/Jobber hooks, technician roster, SMS/WhatsApp consent flows, reports.</p></div>
    <div class="card"><h3>Enterprise Field Finance</h3><p>$799/mo — Multi-branch, API, audit packs, ServiceTitan sync, advanced AI Hub routing.</p></div>
  </div>
</div></main>
@endsection
