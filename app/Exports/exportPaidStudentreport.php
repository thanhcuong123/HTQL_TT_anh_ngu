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

class exportPaidStudentreport implements
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
        $this->paymentData = $paymentData->values(); // reset key
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
        return 'Học viên đã thanh toán';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial');
        return [];
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header TT
                $centerName = 'Trung tâm Anh Ngữ River';
                $centerPhone = 'Điện thoại: 0123.456.789';
                $centerEmail = 'Email: river@egamil.edu.com';

                $sheet->setCellValue('A1', $centerName);
                $sheet->mergeCells('A1:C1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                $sheet->setCellValue('A2', $centerPhone);
                $sheet->mergeCells('A2:C2');

                $sheet->setCellValue('A3', $centerEmail);
                $sheet->mergeCells('A3:C3');

                $sheet->setCellValue('D1', 'BÁO CÁO DANH SÁCH HỌC VIÊN ĐÃ THANH TOÁN');
                $sheet->mergeCells('D1:H3');
                $sheet->getStyle('D1')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D1')->getAlignment()->setVertical('center');
                $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(18);

                $sheet->setCellValue('A5', 'Lớp: ' . ($this->selectedClass->tenlophoc ?? 'Tất cả'));
                $sheet->mergeCells('A5:C5');
                $sheet->getStyle('A5')->getFont()->setBold(true);

                $sheet->setCellValue('E5', 'Ngày báo cáo: ' . $this->reportDate);
                $sheet->mergeCells('E5:I5');
                $sheet->getStyle('E5')->getAlignment()->setHorizontal('right');
                $sheet->getStyle('E5')->getFont()->setItalic(true);

                // Header style
                $sheet->getStyle('A7:I7')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD9D9D9'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                // Bọc viền + format số tiền
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A7:I' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                // Format số tiền (F, G, H)
                for ($row = 8; $row <= $highestRow; $row++) {
                    $sheet->getStyle('F' . $row . ':H' . $row)
                        ->getNumberFormat()->setFormatCode('#,##0');
                }

                // Căn giữa STT
                $sheet->getStyle('A8:A' . $highestRow)->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
