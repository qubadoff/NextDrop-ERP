<?php

namespace App\Filament\Widgets;

use App\Employee\EmployeeStatusEnum;
use App\Models\Employee;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{

    use HasWidgetShield;

    protected function getStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentCustomerCount = Employee::query()
            ->where('status', EmployeeStatusEnum::ACTIVE)
            ->where('created_at', '>=', $currentMonth)
            ->count();
        $previousCustomerCount = Employee::query()
            ->where('status', EmployeeStatusEnum::ACTIVE)
            ->where('created_at', '>=', $previousMonth)
            ->where('created_at', '<', $currentMonth)
            ->count();

        $customerIncrease = $currentCustomerCount - $previousCustomerCount;

        return [
            Stat::make('Bütün aktiv Əməkdaşlar', Employee::query()->where('status', EmployeeStatusEnum::ACTIVE)->count())
                ->color($customerIncrease >= 0 ? 'success' : 'danger')
                ->description(abs($customerIncrease) . ' ' . ($customerIncrease >= 0 ? 'Artım' : 'Azalıb'))
                ->descriptionIcon($customerIncrease >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
