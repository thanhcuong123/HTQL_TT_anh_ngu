<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RevenueByClassSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $revenueByClass;

    public function __construct(array $revenueByClass)
    {
        $this->revenueByClass = $revenueByClass;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = new Collection();
        foreach ($this->revenueByClass as $classData) {
            $data->push([
                $classData['name'],
                $classData['actual_revenue'],
                $classData['expected_tuition'],
                $classData['payment_completion_rate'],
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
            'Lớp học',
            'Doanh thu thực tế (VNĐ)',
            'Học phí dự kiến (VNĐ)',
            'Tỷ lệ hoàn thành thanh toán (%)',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Doanh Thu Theo Lop';
    }
}
