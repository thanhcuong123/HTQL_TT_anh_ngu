<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OverallRevenueSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection([
            ['Báo cáo Doanh thu Tổng quan'],
            ['Kỳ báo cáo:', $this->getPeriodTitle()],
            [''], // Dòng trống
            ['Chỉ số', 'Giá trị'],
            ['Tổng doanh thu', number_format($this->reportData['totalRevenue'], 0, ',', '.') . ' VNĐ'],
            ['Số lượng giao dịch', number_format($this->reportData['totalTransactions'], 0, ',', '.')],
            ['Giá trị giao dịch trung bình', number_format($this->reportData['averageTransactionValue'], 0, ',', '.') . ' VNĐ'],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return []; // Tiêu đề sẽ được tạo thủ công trong collection
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Tong Quan Doanh Thu';
    }

    private function getPeriodTitle(): string
    {
        switch ($this->reportData['period']) {
            case 'month':
                return 'Tháng ' . $this->reportData['month'] . '/' . $this->reportData['year'];
            case 'quarter':
                return 'Quý ' . $this->reportData['quarter'] . '/' . $this->reportData['year'];
            case 'year':
                return 'Năm ' . $this->reportData['year'];
            default:
                return 'Tháng hiện tại';
        }
    }
}
