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

class StudentUnpaidExport implements
    FromCollection,
    WithHeadings,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    protected $paymentData;
    protected $reportDate;
    protected $selectedClass;

    public function __construct(Collection $paymentData, string $reportDate, $selectedClass = null)
    {
        $this->paymentData = $paymentData->values(); // Reset key
        $this->reportDate = $reportDate;
        $this->selectedClass = $selectedClass;
    }

    public function collection()
    {
        return $this->paymentData->map(function ($item, $index) {
            return [
                'STT' => $index + 1,
                'Mã học viên' => $item['mahocvien'],
                'Tên học viên' => $item['tenhocvien'],
                'Mã lớp học' => $item['malophoc'],
                'Tên lớp học' => $item['tenlophoc'],
                'Tổng học phí' => $item['total_tuition'],
                'Đã thanh toán' => $item['amount_paid'],
                'Còn nợ' => $item['remaining_balance'],
                'Trạng thái' => $item['payment_status'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã học viên',
            'Tên học viên',
            'Mã lớp học',
            'Tên lớp học',
            'Tổng học phí (VND)',
            'Đã thanh toán (VND)',
            'Còn nợ (VND)',
            'Trạng thái',
        ];
    }

    public function title(): string
    {
        return 'Học viên chưa đóng học phí';
    }

    public function startCell(): string
    {
        return 'A7'; // Header bắt đầu từ A7
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

                // Thông tin trung tâm
                $sheet->setCellValue('A1', 'Trung tâm Anh Ngữ River');
                $sheet->mergeCells('A1:C1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                $sheet->setCellValue('A2', 'Điện thoại: 0123.456.789');
                $sheet->mergeCells('A2:C2');

                $sheet->setCellValue('A3', 'Email: info@example.com');
                $sheet->mergeCells('A3:C3');

                // Tiêu đề báo cáo
                $sheet->setCellValue('D1', 'BÁO CÁO HỌC VIÊN CHƯA ĐÓNG HỌC PHÍ');
                $sheet->mergeCells('D1:H3');
                $sheet->getStyle('D1')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D1')->getAlignment()->setVertical('center');
                $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(18);

                // Tên lớp (nếu có)
                $className = $this->selectedClass->tenlophoc ?? 'Tất cả lớp';
                $sheet->setCellValue('A5', 'Lớp: ' . $className);
                $sheet->mergeCells('A5:C5');
                $sheet->getStyle('A5')->getFont()->setBold(true);

                // Ngày báo cáo
                $sheet->setCellValue('E5', 'Ngày báo cáo: ' . $this->reportDate);
                $sheet->mergeCells('E5:I5');
                $sheet->getStyle('E5')->getAlignment()->setHorizontal('right');
                $sheet->getStyle('E5')->getFont()->setItalic(true);

                // Header dòng 7
                $headerRow = 7;

                // Định dạng header: đậm + nền xám + căn giữa
                $sheet->getStyle("A{$headerRow}:I{$headerRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD9D9D9'],
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

                // Định dạng toàn bảng: viền + số tiền + căn giữa STT
                $highestRow = $sheet->getHighestRow();

                // Viền tất cả
                $sheet->getStyle("A{$headerRow}:I{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Format số tiền: F, G, H
                for ($row = $headerRow + 1; $row <= $highestRow; $row++) {
                    $sheet->getStyle("F{$row}:H{$row}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }

                // STT căn giữa
                $sheet->getStyle("A" . ($headerRow + 1) . ":A{$highestRow}")->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
