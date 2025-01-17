<?php

namespace App\Filament\Resources\EmployeePenalResource\Widgets;

use App\Employee\EmployeePenalStatus;
use App\Models\EmployeePenal;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PenalChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected static ?string $heading = 'Ümumi Cərimələr';

    protected static string $color = 'danger';

    protected static ?string $pollingInterval = '10s';

    public ?string $filter = 'year';

    /**
     * Widget sütun sayısı
     */
    public function getColumns(): int | string | array
    {
        return 1;
    }

    /**
     * Widget açıklama metni
     */
    public function getDescription(): ?string
    {
        return 'Əsas parametr İL olaraq qeyd edilib.';
    }

    /**
     * Filtreleme işlemi
     */
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Gün',
            'week' => 'Həftə',
            'month' => 'Ay',
            'year' => 'İl',
        ];
    }

    /**
     * Kullanıcı tarafından seçilen filtreyi uygula
     */
    public function applyFilter($filter): void
    {
        $this->filter = $filter;
    }

    /**
     * Veriyi hazırla
     */
    protected function getData(): array
    {
        switch ($this->filter) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $data = $this->getPenalData($startDate, $endDate, 'hour');
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                $data = $this->getPenalData($startDate, $endDate, 'day');
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                $data = $this->getPenalData($startDate, $endDate, 'day');
                break;
            case 'year':
            default:
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                $data = $this->getPenalData($startDate, $endDate, 'month');
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cərimə Miktarı',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    /**
     * Grafik türü
     */
    protected function getType(): string
    {
        return 'bar';
    }


    private function getPenalData($startDate, $endDate, $interval): \Illuminate\Support\Collection
    {
        return EmployeePenal::query()
            ->where('status', EmployeePenalStatus::APPROVED->value)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as date, SUM(penal_amount) as aggregate")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')") // Sadece DATE_FORMAT kullanılıyor
            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m') ASC")
            ->get()
            ->map(fn ($item) => new TrendValue(
                date: $item->date,
                aggregate: $item->aggregate
            ));
    }
}
