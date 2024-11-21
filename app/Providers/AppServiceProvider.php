<?php

namespace App\Providers;

use App\Models\EmployeeAttendance;
use App\Models\Position;
use App\Models\VacationDay;
use App\Observers\EmployeeAttendanceObserver;
use App\Observers\PositionObserver;
use App\Observers\VacationOperationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VacationDay::observe(VacationOperationObserver::class);

        EmployeeAttendance::observe(EmployeeAttendanceObserver::class);

        Position::observe(PositionObserver::class);
    }
}
