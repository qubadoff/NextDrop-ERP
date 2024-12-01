<?php

namespace App\Filament\Widgets;

use App\Employee\EmployeeAvansStatus;
use App\Employee\EmployeeAwardStatus;
use App\Employee\EmployeePenalStatus;
use App\Employee\EmployeeStatusEnum;
use App\Models\Employee;
use App\Models\EmployeeAvans;
use App\Models\EmployeeAward;
use App\Models\EmployeePenal;
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

        // Aktif çalışan sayısı
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

        $totalAwardsAmount = EmployeeAward::where('status', EmployeeAwardStatus::APPROVED)->sum('award_amount');

        $totalAvansAmount = EmployeeAvans::where('status', EmployeeAvansStatus::APPROVED)->sum('amount');

        $totalPenalAmount = EmployeePenal::where('status', EmployeePenalStatus::APPROVED)->sum('penal_amount');

        $totalSalaryAmount = 14345;


        return [
            Stat::make('Bütün aktiv Əməkdaşlar', Employee::query()->where('status', EmployeeStatusEnum::ACTIVE)->count())
                ->color($customerIncrease >= 0 ? 'success' : 'danger')
                ->description(abs($customerIncrease) . ' ' . ($customerIncrease >= 0 ? 'Artım' : 'Azalıb'))
                ->descriptionIcon($customerIncrease >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Ümumi Əməkhaqqı Məbləği', $totalSalaryAmount . ' AZN')
                ->color('success')
                ->description('Ümumi Əməkhaqqı məbləği')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([5, 7, 3, 9, 4, 6, 10]),


            Stat::make('Ümumi Mükafat Məbləği', $totalAwardsAmount . ' AZN')
                ->color('success')
                ->description('Ümumi mükafat məbləği')
                ->descriptionIcon('heroicon-m-trophy')
                ->chart([5, 7, 3, 9, 4, 6, 10]),

            Stat::make('Ümumi Avans Məbləği', $totalAvansAmount . ' AZN')
                ->color('info')
                ->description('Ümumi avans məbləği')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([3, 8, 2, 11, 5, 7, 12]),

            Stat::make('Ümumi Cərimə Məbləği', $totalPenalAmount . ' AZN')
                ->color('info')
                ->description('Ümumi Cərimə məbləği')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([3, 8, 2, 11, 5, 7, 12]),
        ];
    }
}
