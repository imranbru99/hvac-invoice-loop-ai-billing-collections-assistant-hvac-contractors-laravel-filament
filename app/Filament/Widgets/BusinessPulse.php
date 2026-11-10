<?php

namespace App\Filament\Widgets;

use App\Services\BusinessMetrics;
use App\Services\ModuleRegistry;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessPulse extends BaseWidget
{
    protected function getStats(): array
    {
        $metrics = app(BusinessMetrics::class)->overview();
        $enabled = collect(app(ModuleRegistry::class)->all())->where('enabled', true)->count();

        return [
            Stat::make('Enabled modules', (string) $enabled)->description('HvacInvoiceLoop')->color('success'),
            Stat::make('Open AR', '$'.number_format($metrics['open_ar'], 2))->description('Amount due')->color('danger'),
            Stat::make('Draft invoices', (string) $metrics['draft_invoices'])->description('Awaiting approval')->color('warning'),
            Stat::make('Job tickets', (string) $metrics['job_tickets'])->description('Field jobs')->color('primary'),
            Stat::make('Reminders', (string) $metrics['reminders'])->description('Collections queue')->color('info'),
            Stat::make('Audit events', (string) $metrics['audit_events'])->description('Approval trail')->color('gray'),
        ];
    }
}
