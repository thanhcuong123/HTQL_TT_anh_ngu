<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HocVien;
use App\Models\LopHoc;
use App\Models\PhieuThu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel; // Import facade của Laravel Excel
use App\Exports\RevenueReportExport;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $quarter = $request->input('quarter', Carbon::now()->quarter);

        $reportTitle = 'Báo cáo Doanh thu';
        switch ($period) {
            case 'month':
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();
                $reportTitle .= ' (tháng ' . $month . '/' . $year . ')';
                break;
            case 'quarter':
                $startDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->startOfQuarter();
                $endDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->endOfQuarter();
                $reportTitle .= ' (quý ' . $quarter . '/' . $year . ')';
                break;
            case 'year':
                $startDate = Carbon::create($year, 1, 1)->startOfYear();
                $endDate = Carbon::create($year, 12, 31)->endOfYear();
                $reportTitle .= ' (năm ' . $year . ')';
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $reportTitle .= ' (tháng này)';
                break;
        }

        $totalRevenue = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
            ->sum('sotien');

        $totalTransactions = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
            ->count();

        $averageTransactionValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        $paidReceipts = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
            ->with(['lophoc.trinhdo.dongias', 'lophoc.khoahoc'])
            ->get();

        $revenueByClass = [];

        foreach ($paidReceipts as $receipt) {
            $lophoc = $receipt->lophoc;
            if (!$lophoc) continue;

            $trinhdo = $lophoc->trinhdo;
            $namhocId = $lophoc->namhoc_id ?? optional($lophoc->khoahoc)->namhoc_id;

            $dongia = $trinhdo ? $trinhdo->dongias->where('namhoc_id', $namhocId)->first() : null;
            $expectedTuition = $dongia ? $dongia->hocphi : 0;

            $lophocId = $lophoc->id;
            $lophocName = $lophoc->tenlophoc ?? 'N/A';
            $lophocCode = $lophoc->malophoc ?? 'N/A';

            if (!isset($revenueByClass[$lophocId])) {
                $revenueByClass[$lophocId] = [
                    'name' => $lophocCode . ' - ' . $lophocName,
                    'actual_revenue' => 0,
                    'expected_tuition' => $expectedTuition,
                    'payment_completion_rate' => 0,
                ];
            }
            $revenueByClass[$lophocId]['actual_revenue'] += $receipt->sotien;
        }

        foreach ($revenueByClass as $id => &$data) {
            $lophoc = LopHoc::with(['trinhdo.dongias', 'khoahoc', 'hocviens'])->find($id);
            if (!$lophoc) continue;

            $trinhdo = $lophoc->trinhdo;
            $namhocId = $lophoc->namhoc_id ?? optional($lophoc->khoahoc)->namhoc_id;

            $dongia = $trinhdo ? $trinhdo->dongias->where('namhoc_id', $namhocId)->first() : null;
            $hocphi = $dongia ? $dongia->hocphi : 0;

            $studentCount = $lophoc->hocviens->count();
            $totalExpectedTuition = $studentCount * $hocphi;

            $totalPaid = PhieuThu::where('lophoc_id', $id)
                ->where('trangthai', 'da_thanh_toan')
                ->sum('sotien');

            $data['payment_completion_rate'] = ($totalExpectedTuition > 0)
                ? ($totalPaid / $totalExpectedTuition) * 100 : 0;
        }

        uasort($revenueByClass, fn($a, $b) => $b['actual_revenue'] <=> $a['actual_revenue']);

        $revenueByPaymentMethod = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
            ->selectRaw('phuongthuc, SUM(sotien) as total_amount')
            ->groupBy('phuongthuc')
            ->get()
            ->keyBy('phuongthuc')
            ->toArray();

        foreach ($revenueByPaymentMethod as &$data) {
            $data['percentage'] = ($totalRevenue > 0) ? ($data['total_amount'] / $totalRevenue) * 100 : 0;
        }

        $monthlyRevenueTrend = [];
        $currentDate = clone $startDate;
        while ($currentDate->lte($endDate)) {
            $key = $currentDate->format('Y-m');
            $monthlyRevenueTrend[$key] = [
                'month_year' => $currentDate->format('m/Y'),
                'revenue' => 0
            ];
            $currentDate->addMonth();
        }

        $monthlyReceipts = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
            ->get();

        foreach ($monthlyReceipts as $receipt) {
            $key = Carbon::parse($receipt->ngaythanhtoan)->format('Y-m');
            if (isset($monthlyRevenueTrend[$key])) {
                $monthlyRevenueTrend[$key]['revenue'] += $receipt->sotien;
            }
        }

        $studentsWithDebt = [];

        $allStudentsInClasses = HocVien::has('lophocs')
            ->with(['lophocs.trinhdo.dongias', 'lophocs.khoahoc', 'phieuthu' => function ($q) {
                $q->where('trangthai', 'da_thanh_toan');
            }])
            ->get();

        foreach ($allStudentsInClasses as $student) {
            foreach ($student->lophocs as $lophoc) {
                $trinhdo = $lophoc->trinhdo;
                $namhocId = $lophoc->namhoc_id ?? optional($lophoc->khoahoc)->namhoc_id;

                $dongia = $trinhdo ? $trinhdo->dongias->where('namhoc_id', $namhocId)->first() : null;
                $totalTuition = $dongia ? $dongia->hocphi : 0;

                $paidAmount = $student->phieuthu->where('lophoc_id', $lophoc->id)->sum('sotien');
                $remaining = $totalTuition - $paidAmount;

                if ($remaining > 0) {
                    $studentsWithDebt[] = [
                        'ten' => $student->ten,
                        'mahocvien' => $student->mahocvien,
                        'lophoc' => $lophoc->tenlophoc ?? null,
                        'remaining_amount' => $remaining
                    ];
                }
            }
        }

        usort($studentsWithDebt, fn($a, $b) => $b['remaining_amount'] <=> $a['remaining_amount']);
        $top5StudentsWithDebt = array_slice($studentsWithDebt, 0, 5);

        return view('admin.reports.revenue', compact(
            'reportTitle',
            'totalRevenue',
            'totalTransactions',
            'averageTransactionValue',
            'revenueByClass',
            'revenueByPaymentMethod',
            'monthlyRevenueTrend',
            'top5StudentsWithDebt',
            'period',
            'year',
            'month',
            'quarter'
        ));
    }






    // public function exportRevenueToExcel(Request $request)
    // {
    //     // Lấy lại các tham số lọc để đảm bảo xuất đúng kỳ báo cáo
    //     $period = $request->input('period', 'month');
    //     $year = $request->input('year', Carbon::now()->year);
    //     $month = $request->input('month', Carbon::now()->month);
    //     $quarter = $request->input('quarter', Carbon::now()->quarter);

    //     $startDate = null;
    //     $endDate = null;
    //     $reportTitleSuffix = '';

    //     switch ($period) {
    //         case 'month':
    //             $startDate = Carbon::create($year, $month, 1)->startOfMonth();
    //             $endDate = Carbon::create($year, $month, 1)->endOfMonth();
    //             $reportTitleSuffix = 'Tháng ' . $month . '_' . $year;
    //             break;
    //         case 'quarter':
    //             $startDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->startOfQuarter();
    //             $endDate = Carbon::create($year, $quarter * 3, 1)->endOfMonth()->endOfDay();
    //             $reportTitleSuffix = 'Quy ' . $quarter . '_' . $year;
    //             break;
    //         case 'year':
    //             $startDate = Carbon::create($year, 1, 1)->startOfYear();
    //             $endDate = Carbon::create($year, 12, 31)->endOfYear();
    //             $reportTitleSuffix = 'Nam ' . $year;
    //             break;
    //         default:
    //             $startDate = Carbon::now()->startOfMonth();
    //             $endDate = Carbon::now()->endOfMonth();
    //             $reportTitleSuffix = 'Thang_Hien_Tai';
    //             break;
    //     }

    //     // --- Lấy dữ liệu cho các phần của báo cáo (tương tự như trong index method) ---
    //     $totalRevenue = PhieuThu::where('trangthai', 'da_thanh_toan')
    //         ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
    //         ->sum('sotien');

    //     $totalTransactions = PhieuThu::where('trangthai', 'da_thanh_toan')
    //         ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
    //         ->count();

    //     $averageTransactionValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

    //     $paidReceipts = PhieuThu::where('trangthai', 'da_thanh_toan')
    //         ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
    //         ->with('lophoc.trinhdo.dongias')
    //         ->get();

    //     $revenueByClass = [];
    //     foreach ($paidReceipts as $receipt) {
    //         $lophocId = $receipt->lophoc_id;
    //         $lophocName = $receipt->lophoc->tenlophoc ?? 'Lớp không xác định';
    //         $lophocCode = $receipt->lophoc->malophoc ?? 'N/A';
    //         $expectedTuition = optional(optional($receipt->lophoc->trinhdo)->dongia)->hocphi ?? 0;

    //         if (!isset($revenueByClass[$lophocId])) {
    //             $revenueByClass[$lophocId] = [
    //                 'name' => $lophocCode . ' - ' . $lophocName,
    //                 'actual_revenue' => 0,
    //                 'expected_tuition' => $expectedTuition,
    //                 'payment_completion_rate' => 0,
    //             ];
    //         }
    //         $revenueByClass[$lophocId]['actual_revenue'] += $receipt->sotien;
    //     }
    //     foreach ($revenueByClass as $id => &$data) {
    //         $lophoc = LopHoc::with('trinhdo.dongias')->find($id);
    //         if ($lophoc) {
    //             $totalExpectedTuitionForClass = optional(optional($lophoc->trinhdo)->dongia)->hocphi ?? 0;
    //             $totalPaidForClassOverall = PhieuThu::where('lophoc_id', $id)
    //                 ->where('trangthai', 'da_thanh_toan')
    //                 ->sum('sotien');
    //             $data['payment_completion_rate'] = ($totalExpectedTuitionForClass > 0) ?
    //                 ($totalPaidForClassOverall / $totalExpectedTuitionForClass) * 100 : 0;
    //         }
    //     }
    //     uasort($revenueByClass, function ($a, $b) {
    //         return $b['actual_revenue'] <=> $a['actual_revenue'];
    //     });

    //     $revenueByPaymentMethod = PhieuThu::where('trangthai', 'da_thanh_toan')
    //         ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
    //         ->selectRaw('phuongthuc, SUM(sotien) as total_amount')
    //         ->groupBy('phuongthuc')
    //         ->get()
    //         ->keyBy('phuongthuc')
    //         ->toArray();

    //     foreach ($revenueByPaymentMethod as $method => &$data) {
    //         $data['percentage'] = ($totalRevenue > 0) ? ($data['total_amount'] / $totalRevenue) * 100 : 0;
    //     }

    //     $monthlyRevenueTrend = [];
    //     $currentDate = clone $startDate;
    //     while ($currentDate->lte($endDate)) {
    //         $monthKey = $currentDate->format('Y-m');
    //         $monthlyRevenueTrend[$monthKey] = [
    //             'month_year' => $currentDate->format('m/Y'),
    //             'revenue' => 0
    //         ];
    //         $currentDate->addMonth();
    //     }

    //     $monthlyReceipts = PhieuThu::where('trangthai', 'da_thanh_toan')
    //         ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
    //         ->get();

    //     foreach ($monthlyReceipts as $receipt) {
    //         $monthKey = Carbon::parse($receipt->ngaythanhtoan)->format('Y-m');
    //         if (isset($monthlyRevenueTrend[$monthKey])) {
    //             $monthlyRevenueTrend[$monthKey]['revenue'] += $receipt->sotien;
    //         }
    //     }

    //     $studentsWithDebt = [];

    //     $allStudentsInClasses = HocVien::has('lophocs')
    //         ->with([
    //             'lophocs.trinhdo.dongias',
    //             'lophocs.khoahoc',
    //             'phieuthu' => function ($query) {
    //                 $query->where('trangthai', 'da_thanh_toan');
    //             }
    //         ])
    //         ->get();

    //     foreach ($allStudentsInClasses as $student) {
    //         foreach ($student->lophocs as $lophoc) {
    //             $trinhdo = $lophoc->trinhdo;
    //             if (!$trinhdo) continue;

    //             $namhocId = $lophoc->namhoc_id ?? optional($lophoc->khoahoc)->namhoc_id;
    //             if (!$namhocId) continue;

    //             $dongia = $trinhdo->dongias->where('namhoc_id', $namhocId)->first();
    //             $totalTuitionForClass = $dongia ? $dongia->hocphi : 0;

    //             $paidAmount = $student->phieuthu->where('lophoc_id', $lophoc->id)->sum('sotien');
    //             $remaining = $totalTuitionForClass - $paidAmount;

    //             if ($remaining > 0) {
    //                 $studentsWithDebt[] = [
    //                     'ten' => $student->ten,
    //                     'mahocvien' => $student->mahocvien,
    //                     'lophoc' => $lophoc->tenlophoc ?? null,
    //                     'remaining_amount' => $remaining
    //                 ];
    //             }
    //         }
    //     }

    //     // Sắp xếp giảm dần theo số nợ
    //     usort($studentsWithDebt, function ($a, $b) {
    //         return $b['remaining_amount'] <=> $a['remaining_amount'];
    //     });

    //     // Lấy top 5
    //     $top5StudentsWithDebt = array_slice($studentsWithDebt, 0, 5);


    //     // Tạo một mảng chứa tất cả dữ liệu cần thiết cho các sheet
    //     $reportData = [
    //         'period' => $period,
    //         'year' => $year,
    //         'month' => $month,
    //         'quarter' => $quarter,
    //         'reportTitleSuffix' => $reportTitleSuffix,
    //         'totalRevenue' => $totalRevenue,
    //         'totalTransactions' => $totalTransactions,
    //         'averageTransactionValue' => $averageTransactionValue,
    //         'revenueByClass' => $revenueByClass,
    //         'revenueByPaymentMethod' => $revenueByPaymentMethod,
    //         'monthlyRevenueTrend' => $monthlyRevenueTrend,
    //         'top5StudentsWithDebt' => $top5StudentsWithDebt,
    //     ];

    //     $fileName = 'BaoCaoDoanhThu_' . $reportTitleSuffix . '_' . date('Ymd_His') . '.xlsx';

    //     // Sử dụng lớp RevenueReportExport để tạo và tải file Excel với nhiều sheet
    //     return Excel::download(new RevenueReportExport($reportData, now()->format('d/m/Y')), 'bao_cao_doanh_thu.xlsx');
    // }
    public function exportRevenueToExcel(Request $request)
    {
        $year = $request->input('year', date('Y')); // năm chọn hoặc mặc định hiện tại

        // ====== Lấy dữ liệu doanh thu theo tháng ======
        $monthlyRevenue = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereYear('ngaythanhtoan', $year)
            ->selectRaw('MONTH(ngaythanhtoan) as month, SUM(sotien) as revenue')
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // Đủ 12 tháng
        $monthlyRevenueTrend = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenueTrend[] = [
                'month_year' => str_pad($m, 2, '0', STR_PAD_LEFT) . '/' . $year,
                'revenue'    => $monthlyRevenue[$m] ?? 0,
            ];
        }

        // ====== Tạo file Excel ======
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADER thông tin trung tâm

        $sheet->setCellValue('A1', 'Trung tâm Anh Ngữ River');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->setCellValue('A2', 'Điện thoại: 0123.456.789');
        $sheet->setCellValue('A3', 'Email: river@gmail.com');

        // TIÊU ĐỀ BÁO CÁO
        $sheet->mergeCells('C2:J2');
        $sheet->setCellValue('C2', 'BÁO CÁO DOANH THU');
        $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(20);
        // $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('C2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // NGÀY BÁO CÁO (bên phải)
        $sheet->setCellValue('M3', 'Ngày báo cáo: ' . \Carbon\Carbon::now()->translatedFormat('l, d \t\h\á\n\g m \n\ă\m Y'));
        $sheet->getStyle('M3')->getFont()->setItalic(true);
        $sheet->getStyle('M3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // ====== CHỪA 2 HÀNG TRỐNG SAU TIÊU ĐỀ ======
        $startRow = 6;

        // TABLE HEADER
        $sheet->setCellValue("A{$startRow}", 'Thời gian');
        $sheet->setCellValue("B{$startRow}", 'Doanh thu (VNĐ)');
        $sheet->getStyle("A{$startRow}:B{$startRow}")
            ->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A{$startRow}:B{$startRow}")
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$startRow}:B{$startRow}")
            ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('28a745');

        // TABLE BODY
        $row = $startRow + 1;
        foreach ($monthlyRevenueTrend as $data) {
            $sheet->setCellValue("A{$row}", $data['month_year']);
            $sheet->setCellValue("B{$row}", $data['revenue']);
            $row++;
        }

        // Border + format
        $sheet->getStyle("A{$startRow}:B" . ($row - 1))
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A" . ($startRow + 1) . ":A" . ($row - 1))
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B" . ($startRow + 1) . ":B" . ($row - 1))
            ->getNumberFormat()->setFormatCode('#,##0');

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // CHART
        $dataSeriesLabels = [new DataSeriesValues('String', 'Worksheet!$B$' . $startRow, null, 1)];
        $xAxisTickValues  = [new DataSeriesValues('String', 'Worksheet!$A$' . ($startRow + 1) . ':$A$' . ($row - 1), null, 12)];
        $dataSeriesValues = [new DataSeriesValues('Number', 'Worksheet!$B$' . ($startRow + 1) . ':$B$' . ($row - 1), null, 12)];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            $xAxisTickValues,
            $dataSeriesValues
        );
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $plotArea = new PlotArea(null, [$series]);
        $title    = new Title('Biểu đồ doanh thu theo tháng');
        $legend   = new Legend(Legend::POSITION_RIGHT, null, false);

        $chart = new Chart('revenue_chart', $title, $legend, $plotArea);
        $chart->setTopLeftPosition('E6');
        $chart->setBottomRightPosition('M23');
        $sheet->addChart($chart);

        // Xuất file
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);

        $fileName = 'bao_cao_doanh_thu_' . $year . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
