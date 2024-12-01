<?php

namespace App\Filament\Resources\VacationDayResource\Pages;

use App\Filament\Resources\VacationDayResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;

class CreateVacationDay extends CreateRecord
{
    protected static string $resource = VacationDayResource::class;
}
