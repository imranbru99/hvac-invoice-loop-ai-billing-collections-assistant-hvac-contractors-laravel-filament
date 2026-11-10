@extends('public.layout', ['appName' => $appName, 'title' => 'Docs', 'author' => $author])
@section('content')
<main><div class="wrap">
  <h1 style="font-family:Georgia,serif">Operator handbook</h1>
  <div class="card" style="margin-top:1rem">
    <ol class="muted">
      <li>Run <code>docker compose up -d --build</code> (port 8106).</li>
      <li>Sign in at <code>/admin</code> with a demo user.</li>
      <li>Create or complete a <strong>Job Ticket</strong>, then click <em>AI Draft Invoice</em>.</li>
      <li>Approve the invoice, reconcile payments, draft reminders (consent required).</li>
      <li>Review the <strong>Audit Log</strong> for the maker-checker trail.</li>
      <li>Toggle modules in Admin → Module Control for your HVAC shop mix.</li>
    </ol>
  </div>
</div></main>
@endsection
