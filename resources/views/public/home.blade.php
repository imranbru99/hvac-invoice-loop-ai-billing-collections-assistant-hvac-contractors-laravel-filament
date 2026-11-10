@extends('public.layout', ['appName' => $appName, 'title' => 'AI Billing & Collections for HVAC Contractors', 'author' => $author])
@section('content')
<section class="hero"><div class="wrap">
  <div class="pill">{{ $industry }}</div>
  <h1 style="margin-top:1rem">HvacInvoiceLoop</h1>
  <p>{{ $tagline }}</p>
  <div class="cta">
    <a class="btn btn-primary" href="/demo">Start Free Demo</a>
    <a class="btn btn-ghost" href="/screens">View Screens</a>
  </div>
</div></section>
<main><div class="wrap">
  <img class="shot" src="/images/dashboard.svg" alt="HvacInvoiceLoop collections dashboard"/>
  <h2 style="margin-top:2.2rem;font-family:Georgia,serif">Core collections loop</h2>
  <div class="grid" style="margin-top:1rem">
    <div class="card"><div class="pill">1 · INTAKE</div><h3>Service request / job ticket</h3><p>Capture completed HVAC work with labor hours, parts, and technician.</p></div>
    <div class="card"><div class="pill">2 · AI DRAFT</div><h3>Invoice draft</h3><p>AI drafts line items and totals from the job ticket for review.</p></div>
    <div class="card"><div class="pill">3 · APPROVE</div><h3>Human approval</h3><p>Owner or accountant approves before customer send.</p></div>
    <div class="card"><div class="pill">4 · AUDIT</div><h3>Remind & reconcile</h3><p>Consent-aware reminders, payment status, immutable audit log.</p></div>
  </div>
  <h2 style="margin-top:2.2rem;font-family:Georgia,serif">Toggleable modules</h2>
  <div class="grid" style="margin-top:1rem">
    @foreach($modules as $key => $module)
      <div class="card">
        <div class="pill">{{ ($module['enabled'] ?? false) ? 'ENABLED' : 'OPTIONAL' }}</div>
        <h3>{{ $module['name'] }}</h3>
        <p>{{ $module['description'] }}</p>
      </div>
    @endforeach
  </div>
</div></main>
@endsection
