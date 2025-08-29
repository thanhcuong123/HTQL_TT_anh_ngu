<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class RevenueReportExport implements
    FromCollection,
    WithHeadings,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    protected $reportData;
    protected $reportDate;

    public function __construct(array $reportData, string $reportDate)
    {
        $this->reportData = $reportData;
        $this->reportDate = $reportDate;
    }

    public function collection()
    {
        $rows = collect();
        $rows->push(['']); // Dòng trống đầu

        // ==== Phần 1: Tổng quan ====
        $rows->push(['BÁO CÁO TỔNG QUAN']); // Tiêu đề phần
        $rows->push(['STT', 'Nội dung', 'Giá trị']);
        $rows->push([1, 'Tổng doanh thu', $this->reportData['totalRevenue']]);
        $rows->push([2, 'Tổng giao dịch', $this->reportData['totalTransactions']]);
        $rows->push([3, 'Giá trị GD trung bình', $this->reportData['averageTransactionValue']]);

        $rows->push(['']); // Dòng trống

        // ==== Phần 2: Doanh thu theo lớp ====
        $rows->push(['DOANH THU THEO LỚP']);
        $rows->push(['STT', 'Lớp học', 'Doanh thu thực tế', 'Học phí dự kiến', 'Tỷ lệ hoàn thành']);
        $stt2 = 1;
        foreach ($this->reportData['revenueByClass'] as $item) {
            $rows->push([
                $stt2++,
                $item['name'],
                $item['actual_revenue'],
                $item['expected_tuition'],
                $item['payment_completion_rate'] . '%',
            ]);
        }

        $rows->push(['']);

        // ==== Phần 3: Doanh thu theo phương thức ====
        $rows->push(['DOANH THU THEO PHƯƠNG THỨC THANH TOÁN']);
        $rows->push(['STT', 'Phương thức', 'Tổng doanh thu', 'Tỷ lệ (%)']);
        $stt3 = 1;
        foreach ($this->reportData['revenueByPaymentMethod'] as $method => $data) {
            $rows->push([
                $stt3++,
                $method,
                $data['total_amount'],
                round($data['percentage'], 2) . '%',
            ]);
        }

        $rows->push(['']);

        // ==== Phần 4: Xu hướng doanh thu ====
        $rows->push(['XU HƯỚNG DOANH THU']);
        $rows->push(['STT', 'Tháng', 'Doanh thu']);
        $stt4 = 1;
        foreach ($this->reportData['monthlyRevenueTrend'] as $item) {
            $rows->push([
                $stt4++,
                $item['month_year'],
                $item['revenue'],
            ]);
        }

        $rows->push(['']);

        // ==== Phần 5: Top HV còn nợ ====
        $rows->push(['TOP HỌC VIÊN CÒN NỢ']);
        $rows->push(['STT', 'Mã HV', 'Tên HV', 'Lớp học', 'Còn nợ']);
        $stt5 = 1;
        foreach ($this->reportData['top5StudentsWithDebt'] as $item) {
            $rows->push([
                $stt5++,
                $item['mahocvien'] ?? '',
                $item['ten'] ?? '',
                $item['lophoc'] ?? '',
                $item['remaining_amount'],
            ]);
        }

        return $rows;
    }


    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Báo cáo doanh thu';
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial');
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Thông tin đầu
                $sheet->setCellValue('A1', 'TRUNG TÂM ANH NGỮ RIVER');
                $sheet->mergeCells('A1:C1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                $sheet->setCellValue('A2', 'Điện thoại: 0123.456.789');
                $sheet->mergeCells('A2:C2');

                $sheet->setCellValue('A3', 'Email: info@example.com');
                $sheet->mergeCells('A3:C3');

                $sheet->setCellValue('E1', 'BÁO CÁO DOANH THU');
                $sheet->mergeCells('E1:H3');
                $sheet->getStyle('E1')->getFont()->setBold(true)->setSize(18);
                $sheet->getStyle('E1')->getAlignment()->setHorizontal('center')->setVertical('center');

                $sheet->setCellValue('E5', 'Ngày báo cáo: ' . $this->reportDate);
                $sheet->mergeCells('E5:H5');
                $sheet->getStyle('E5')->getFont()->setItalic(true);

                $highestRow = $sheet->getHighestRow();

                // Header các section: tìm tất cả dòng có STT header
                $headerRows = [];
                for ($row = 7; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() === 'STT') {
                        $headerRows[] = $row;
                    }
                }

                foreach ($headerRows as $row) {
                    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFE5E5E5'],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => 'center',
                            'vertical' => 'center',
                        ],
                    ]);
                }

                // Kẻ viền
                $sheet->getStyle("A7:F{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Format số tiền: C, D, E
                for ($row = 7; $row <= $highestRow; $row++) {
                    $sheet->getStyle("C{$row}:E{$row}")->getNumberFormat()->setFormatCode('#,##0');
                }

                // Format cột tỷ lệ %: D hoặc E (tùy vị trí)
                for ($row = 7; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell("D{$row}")->getValue();
                    if (is_numeric($cellValue) && $cellValue > 0 && $cellValue <= 100) {
                        $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('0.00"%"');
                    }
                }

                // STT căn giữa
                $sheet->getStyle("A7:A{$highestRow}")->getAlignment()->setHorizontal('center');

                // Giãn cách dòng: auto height
                $sheet->getDefaultRowDimension()->setRowHeight(20);
            },
        ];
    }
}
