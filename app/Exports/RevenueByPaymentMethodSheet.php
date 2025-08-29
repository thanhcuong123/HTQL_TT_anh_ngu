<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RevenueByPaymentMethodSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $revenueByPaymentMethod;

    public function __construct(array $revenueByPaymentMethod)
    {
        $this->revenueByPaymentMethod = $revenueByPaymentMethod;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = new Collection();
        foreach ($this->revenueByPaymentMethod as $method => $item) {
            $data->push([
                $method,
                $item['total_amount'],
                $item['percentage'],
            ]);
        }
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Phương thức thanh toán',
            'Tổng doanh thu (VNĐ)',
            'Tỷ lệ (%)',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Doanh Thu Theo Phuong Thuc';
    }
}
