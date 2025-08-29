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
use App\Models\LopHoc;

class ClassStudentReportSheet implements
    FromCollection,
    WithHeadings,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    protected $selectedClass;
    protected $students;
    protected $reportDate;

    public function __construct(LopHoc $selectedClass, Collection $students, string $reportDate)
    {
        $this->selectedClass = $selectedClass;
        $this->students = $students;
        $this->reportDate = $reportDate;
    }

    public function collection()
    {
        return $this->students->map(function ($student, $key) {
            return [
                'STT' => $key + 1,
                'Mã học viên' => $student->mahocvien ?? '',
                'Họ tên' => $student->ten ?? '',
                'Ngày sinh' => $student->ngaysinh ? \Carbon\Carbon::parse($student->ngaysinh)->format('d-m-Y') : '',
                'Giới tính' => $student->gioitinh ?? '',
                'Số điện thoại' => $student->sdt ?? '',
                'Email' => $student->user->email ?? '',
                'Địa chỉ' => $student->diachi ?? '',
                'Ngày đăng ký' => $student->pivot->ngaydangky ? \Carbon\Carbon::parse($student->pivot->ngaydangky)->format('d-m-Y') : '',
                'Trạng thái' => ucfirst(str_replace('_', ' ', $student->trangthai ?? '')),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã học viên',
            'Họ tên',
            'Ngày sinh',
            'Giới tính',
            'Số điện thoại',
            'Email',
            'Địa chỉ',
            'Ngày đăng ký',
            'Trạng thái',
        ];
    }


    public function title(): string
    {
        return 'Danh sách học viên lớp ' . $this->selectedClass->tenlophoc;
    }

    public function startCell(): string
    {
        return 'A7'; // Bảng dữ liệu sẽ bắt đầu từ ô A7
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Inter');
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Thông tin trung tâm
                $centerName = 'Trung tâm Anh Ngữ river';
                $centerPhone = 'Điện thoại: 0123.456.789';
                $centerEmail = 'Email: info@example.com';

                // Hàng 1: Tên trung tâm
                $sheet->setCellValue('A1', $centerName);
                $sheet->mergeCells('A1:C1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                // Hàng 2: SĐT
                $sheet->setCellValue('A2', $centerPhone);
                $sheet->mergeCells('A2:C2');

                // Hàng 3: Email
                $sheet->setCellValue('A3', $centerEmail);
                $sheet->mergeCells('A3:C3');

                // Tiêu đề báo cáo
                $sheet->setCellValue('D1', 'BÁO CÁO DANH SÁCH HỌC VIÊN');
                $sheet->mergeCells('D1:H3');
                $sheet->getStyle('D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(18);

                // Tên lớp học
                $sheet->setCellValue('A5', 'Lớp: ' . ($this->selectedClass->tenlophoc ?? 'N/A'));
                $sheet->mergeCells('A5:C5');
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);

                // Ngày báo cáo
                $sheet->setCellValue('E5', 'Ngày báo cáo: ' . $this->reportDate);
                $sheet->mergeCells('E5:J5');
                $sheet->getStyle('E5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('E5')->getFont()->setItalic(true);

                // Dòng tiêu đề bảng (row 7)
                $headerRow = 7;
                $highestCol = $sheet->getHighestDataColumn();

                $sheet->getStyle('A' . $headerRow . ':' . $highestCol . $headerRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF000000'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDDEBF7'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Vùng dữ liệu
                $startDataRow = $headerRow + 1;
                $highestRow = $sheet->getHighestRow();

                for ($row = $startDataRow; $row <= $highestRow; $row++) {
                    if (($row - $startDataRow) % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $highestCol . $row)
                            ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFEBF1DE');
                    }
                }

                // Viền dữ liệu
                $sheet->getStyle('A' . $startDataRow . ':' . $highestCol . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Tự động resize cột
                foreach (range('A', $highestCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
