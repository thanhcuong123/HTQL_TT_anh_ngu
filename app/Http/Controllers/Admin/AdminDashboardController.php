<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use App\Models\HocVien;
use App\Models\PhieuThu;
use App\Models\TuVan;
use App\Models\GiaoVien;
use App\Models\KhoaHoc;
use App\Models\NhanVien;
use App\Models\PhongHoc;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tham số thời gian từ request
        // Mặc định là 'month' và năm/tháng/quý hiện tại
        $period = $request->input('period', 'month');
        $year = $request->input('year', Carbon::now()->year); // <-- Biến $year này sẽ được dùng cho biểu đồ
        $month = $request->input('month', Carbon::now()->month);
        $quarter = $request->input('quarter', Carbon::now()->quarter);

        $revenueTitle = 'Tổng doanh thu'; // Tiêu đề mặc định cho phần doanh thu tổng quan
        $startDate = null;
        $endDate = null;

        // Xác định khoảng thời gian cho các chỉ số tổng quan (currentPeriodRevenue)
        switch ($period) {
            case 'month':
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();
                $revenueTitle .= ' (tháng ' . $month . '/' . $year . ')';
                break;
            case 'quarter':
                $startMonthOfQuarter = ($quarter - 1) * 3 + 1;
                $startDate = Carbon::create($year, $startMonthOfQuarter, 1)->startOfQuarter();
                $endDate = Carbon::create($year, $startMonthOfQuarter, 1)->endOfQuarter();
                $revenueTitle .= ' (quý ' . $quarter . '/' . $year . ')';
                break;
            case 'year':
                $startDate = Carbon::create($year, 1, 1)->startOfYear();
                $endDate = Carbon::create($year, 12, 31)->endOfYear();
                $revenueTitle .= ' (năm ' . $year . ')';
                break;
            default:
                $period = 'month';
                $month = Carbon::now()->month;
                $year = Carbon::now()->year;
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $revenueTitle .= ' (tháng này)';
                break;
        }

        // --- 1. Thống Kê Tổng Quan ---
        $totalClasses = LopHoc::count();
        $totalStudents = HocVien::count();
        $totalTeachers = GiaoVien::count();
        $totalStaffs = NhanVien::count();
        $totalClassrooms = PhongHoc::count();
        $totalCourses = KhoaHoc::count();

        $currentPeriodRevenue = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
            ->sum('sotien');

        // --- Dữ liệu cho biểu đồ doanh thu: 12 tháng của NĂM ĐƯỢC CHỌN ---
        $monthlyRevenueData = [];
        $revenueLabels = [];

        // Lặp qua 12 tháng của năm được chọn
        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::create($year, $m, 1); // Tạo Carbon object cho tháng và năm đang xét

            $monthLabel = 'Tháng ' . $date->month; // Chỉ cần nhãn tháng, vì năm đã rõ
            // Nếu bạn muốn hiển thị cả năm trên nhãn: $monthLabel = 'Tháng ' . $date->month . '/' . $date->year;

            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $revenue = PhieuThu::where('trangthai', 'da_thanh_toan')
                ->whereBetween('ngaythanhtoan', [$startOfMonth, $endOfMonth])
                ->sum('sotien');

            $revenueLabels[] = $monthLabel;
            $monthlyRevenueData[] = $revenue;
        }

        $monthlyRevenueData = (array) $monthlyRevenueData;
        $revenueLabels = (array) $revenueLabels;

        // --- Logic tính toán học viên nợ học phí và tỷ lệ hoàn thành học phí ---
        // (Giữ nguyên như đoạn code bạn đã cung cấp)
        $studentsDebtSummary = [];
        $fullyPaidStudents = 0;
        $totalStudentsWithDefinedTuition = 0;
        $processedStudentsForTuitionRate = [];

        $allStudentsInClasses = HocVien::has('lophocs')
            ->with([
                'lophocs.trinhdo.dongias',
                'lophocs.khoahoc',
                'phieuthu' => function ($query) {
                    $query->where('trangthai', 'da_thanh_toan');
                }
            ])
            ->get();

        foreach ($allStudentsInClasses as $student) {
            $currentStudentTotalDebt = 0;
            $currentStudentTotalExpectedTuition = 0;
            $currentStudentTotalPaid = 0;
            $studentHasTuitionDefinedInAnyClass = false;

            foreach ($student->lophocs as $lophoc) {

                $trinhdo = $lophoc->trinhdo;
                if (!$trinhdo) continue;

                $namhocId = $lophoc->namhoc_id ?? optional($lophoc->khoahoc)->namhoc_id;

                if (!$namhocId) continue;

                $dongia = $trinhdo->dongias->where('namhoc_id', $namhocId)->first();

                $hocPhiLop = $dongia ? $dongia->hocphi : 0;

                $paidAmountForClass = $student->phieuthu->where('lophoc_id', $lophoc->id)->sum('sotien');

                if ($hocPhiLop > 0) {
                    $studentHasTuitionDefinedInAnyClass = true;
                    $currentStudentTotalExpectedTuition += $hocPhiLop;
                    $currentStudentTotalPaid += $paidAmountForClass;

                    $remainingForClass = $hocPhiLop - $paidAmountForClass;
                    if ($remainingForClass > 0) {
                        $currentStudentTotalDebt += $remainingForClass;
                    }
                }
            }

            if ($studentHasTuitionDefinedInAnyClass) {
                if ($currentStudentTotalDebt > 0) {
                    $studentsDebtSummary[$student->id] = [
                        'ten' => $student->ten,
                        'lophoc' => $student->lophocs->where('pivot.trangthai', 'dang_hoc')->pluck('tenlophoc')->implode(', '),
                        'remaining_amount' => $currentStudentTotalDebt
                    ];
                }

                if (!isset($processedStudentsForTuitionRate[$student->id])) {
                    if ($currentStudentTotalExpectedTuition > 0) {
                        if ($currentStudentTotalPaid >= $currentStudentTotalExpectedTuition) {
                            $fullyPaidStudents++;
                        }
                        $totalStudentsWithDefinedTuition++;
                    }
                    $processedStudentsForTuitionRate[$student->id] = true;
                }
            }
        }

        $totalStudentsWithDebtCount = count($studentsDebtSummary);

        $studentsWithDebt = collect(array_values($studentsDebtSummary))
            ->sortByDesc('remaining_amount')
            ->take(5);

        $tuitionCompletionRate = ($totalStudentsWithDefinedTuition > 0) ? ($fullyPaidStudents / $totalStudentsWithDefinedTuition) * 100 : 0;

        // --- 2. Thống Kê Lớp Học ---
        $activeClasses = LopHoc::where('trangthai', 'dang_hoat_dong')->count();
        $upcomingClasses = LopHoc::where('trangthai', 'sap_khai_giang')->count();
        $endedClasses = LopHoc::where('trangthai', 'da_ket_thuc')->count();

        $thirtyDaysFromNow = Carbon::now()->addDays(30);
        $endingClasses = LopHoc::where('ngayketthuc', '<=', $thirtyDaysFromNow)
            ->where('ngayketthuc', '>=', Carbon::now())
            ->whereIn('trangthai', ['dang_hoat_dong', 'sap_khai_giang'])
            ->withCount('hocviens')
            ->orderBy('ngayketthuc', 'asc')
            ->limit(5)
            ->get();

        // --- 3. Thống Kê Học Viên ---
        $newStudentsThisMonth = HocVien::whereBetween('create_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();

        // --- 5. Thống Kê Yêu Cầu Tư Vấn ---
        $pendingConsultations = TuVan::where('trangthai', 'đang chờ xử lý')
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        return view('admin.thongke.index', compact(
            'totalClasses',
            'totalStudents',
            'currentPeriodRevenue',
            'revenueTitle',
            'totalStudentsWithDebtCount',
            'activeClasses',
            'upcomingClasses',
            'endedClasses',
            'totalTeachers',
            'totalCourses',
            'totalStaffs',
            'totalClassrooms',
            'endingClasses',
            'newStudentsThisMonth',
            'tuitionCompletionRate',
            'studentsWithDebt',
            'pendingConsultations',
            'period',
            'year',
            'month',
            'quarter',
            'monthlyRevenueData',
            'revenueLabels'
        ));
    }
}
