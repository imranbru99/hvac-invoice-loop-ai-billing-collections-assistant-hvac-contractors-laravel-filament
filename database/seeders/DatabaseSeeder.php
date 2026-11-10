<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\JobTicket;
use App\Models\Lead;
use App\Models\Product;
use App\Models\ServiceRequest;
use App\Models\Supplier;
use App\Models\Technician;
use App\Models\WorkOrder;
use App\Services\AiBillingService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::query()->updateOrCreate(
            ['code' => 'HQ'],
            ['name' => 'HvacInvoiceLoop HQ', 'city' => 'Dallas', 'phone' => '+1-214-555-0100', 'is_active' => true]
        );

        $roles = [
            'super_admin' => ['Super Admin', 'admin@demo.local'],
            'owner' => ['HVAC Owner', 'owner@demo.local'],
            'manager' => ['Operations Lead', 'manager@demo.local'],
            'accountant' => ['Billing Accountant', 'accounts@demo.local'],
            'staff' => ['Office Staff', 'staff@demo.local'],
            'technician' => ['Field Technician', 'tech@demo.local'],
            'customer' => ['Customer Portal', 'customer@demo.local'],
        ];

        foreach ($roles as $role => [$name, $email]) {
            \Spatie\Permission\Models\Role::findOrCreate($role);
            \App\Models\User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => 'password',
                    'user_type' => $role,
                    'branch_id' => $branch->id,
                    'is_active' => true,
                ]
            )->assignRole($role);
        }

        $customer = Customer::query()->updateOrCreate(
            ['code' => 'C-1001'],
            ['name' => 'Summit Property Mgmt', 'city' => 'Dallas', 'email' => 'ap@summit.demo', 'phone' => '+1-214-555-1001', 'balance' => 4820, 'is_active' => true]
        );
        Customer::query()->updateOrCreate(
            ['code' => 'C-1002'],
            ['name' => 'Lakeview Residences', 'city' => 'Plano', 'email' => 'billing@lakeview.demo', 'phone' => '+1-972-555-1002', 'balance' => 1250, 'is_active' => true]
        );

        Supplier::query()->updateOrCreate(['code' => 'S-1001'], ['name' => 'Comfort Parts Supply', 'city' => 'Fort Worth', 'is_active' => true]);
        Lead::query()->updateOrCreate(['code' => 'L-1001'], ['name' => 'New condo maintenance bid', 'stage' => 'qualified', 'value' => 18000, 'is_active' => true]);
        Product::query()->updateOrCreate(['code' => 'SKU-FILTER'], ['name' => 'MERV-13 Filter Pack', 'sku' => 'FIL-13', 'price' => 48, 'cost' => 22, 'stock' => 120, 'reorder_level' => 20, 'is_active' => true]);
        Product::query()->updateOrCreate(['code' => 'SKU-CAP'], ['name' => 'Run Capacitor 45/5', 'sku' => 'CAP-45', 'price' => 86, 'cost' => 34, 'stock' => 40, 'reorder_level' => 8, 'is_active' => true]);
        Employee::query()->updateOrCreate(['code' => 'E-1001'], ['name' => 'Office Coordinator', 'department' => 'Operations', 'designation' => 'Dispatcher', 'salary' => 52000, 'is_active' => true]);
        WorkOrder::query()->updateOrCreate(['code' => 'WO-1001'], ['name' => 'Shop coil clean', 'status' => 'open', 'priority' => 'normal', 'notes' => 'Internal shop work', 'is_active' => true]);

        $tech = Technician::query()->updateOrCreate(
            ['code' => 'T-1001'],
            ['name' => 'Jordan Blake', 'email' => 'jordan@demo.local', 'phone' => '+1-214-555-2001', 'license_no' => 'TX-HVAC-88421', 'specialty' => 'Heat pump & rooftop', 'status' => 'available', 'is_active' => true]
        );
        Technician::query()->updateOrCreate(
            ['code' => 'T-1002'],
            ['name' => 'Sam Rivera', 'email' => 'sam@demo.local', 'phone' => '+1-214-555-2002', 'license_no' => 'TX-HVAC-77102', 'specialty' => 'Residential AC', 'status' => 'on_job', 'is_active' => true]
        );

        $sr = ServiceRequest::query()->updateOrCreate(
            ['code' => 'SR-1001'],
            [
                'name' => 'No-cool rooftop unit ? Suite 400',
                'customer_id' => $customer->id,
                'priority' => 'high',
                'status' => 'completed',
                'equipment' => '10-ton RTU',
                'address' => '400 Commerce St, Dallas TX',
                'requested_at' => now()->subDays(3),
                'notes' => 'Tenant reports warm air since Monday',
                'is_active' => true,
            ]
        );

        $ticket = JobTicket::query()->updateOrCreate(
            ['code' => 'JT-1001'],
            [
                'name' => 'RTU capacitor + filter service',
                'service_request_id' => $sr->id,
                'customer_id' => $customer->id,
                'technician_id' => $tech->id,
                'status' => 'completed',
                'priority' => 'high',
                'labor_hours' => 2.5,
                'parts_total' => 134.00,
                'labor_total' => 287.50,
                'scheduled_at' => now()->subDays(2),
                'completed_at' => now()->subDay(),
                'notes' => 'Replaced run capacitor, changed filters, verified superheat',
                'is_active' => true,
            ]
        );

        $billing = app(AiBillingService::class);
        $invoice = Invoice::query()->where('code', 'INV-DEMO1')->first();
        if (! $invoice) {
            $invoice = $billing->draftInvoiceFromJob($ticket);
            $invoice->update([
                'code' => 'INV-DEMO1',
                'reminder_consent' => true,
            ]);
            $billing->approveInvoice($invoice, 'HVAC Owner');
            $reminder = $billing->draftReminder($invoice->fresh());
            $reminder->update(['consent_ok' => true]);
        }

        Invoice::query()->updateOrCreate(
            ['code' => 'INV-DEMO2'],
            [
                'name' => 'Quarterly PM ? Lakeview',
                'customer_id' => Customer::query()->where('code', 'C-1002')->value('id'),
                'branch_id' => $branch->id,
                'subtotal' => 920,
                'tax' => 73.60,
                'total' => 993.60,
                'amount_paid' => 0,
                'amount_due' => 993.60,
                'status' => 'approved',
                'payment_status' => 'overdue',
                'issued_at' => now()->subDays(20),
                'due_at' => now()->subDays(5),
                'approved_at' => now()->subDays(19),
                'approved_by' => 'HVAC Owner',
                'reminder_consent' => true,
                'ai_draft' => 'Preventive maintenance contract invoice ? Q3.',
                'is_active' => true,
            ]
        );
    }
}
