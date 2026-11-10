<?php

namespace App\Services;

use App\Models\AuditEvent;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JobTicket;
use App\Models\PaymentReminder;
use App\Models\ServiceRequest;
use App\Models\Technician;

class BusinessMetrics
{
    public function overview(): array
    {
        return [
            'customers' => Customer::query()->count(),
            'service_requests' => ServiceRequest::query()->count(),
            'job_tickets' => JobTicket::query()->count(),
            'technicians' => Technician::query()->count(),
            'invoices' => Invoice::query()->count(),
            'draft_invoices' => Invoice::query()->where('status', 'draft')->count(),
            'open_ar' => (float) Invoice::query()->sum('amount_due'),
            'reminders' => PaymentReminder::query()->count(),
            'audit_events' => AuditEvent::query()->count(),
            'application' => config('app.name'),
            'author' => config('app.author'),
        ];
    }
}
