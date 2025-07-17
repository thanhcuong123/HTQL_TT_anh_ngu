<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use App\Models\HocVien;
use App\Models\PhieuThu;
use App\Models\TuVan;
use App\Models\TrinhDo;
use App\Models\DonGia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tham số thời gian từ request
        $period = $request->input('period', 'month'); // Mặc định là 'month'
        $year = $request->input('year', Carbon::now()->year); // Mặc định là năm hiện tại
        $month = $request->input('month', Carbon::now()->month); // Mặc định là tháng hiện tại
        $quarter = $request->input('quarter', Carbon::now()->quarter); // Mặc định là quý hiện tại

        $revenueTitle = 'Tổng doanh thu'; // Tiêu đề mặc định
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'month':
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();
                $revenueTitle .= ' (tháng ' . $month . '/' . $year . ')';
                break;
            case 'quarter':
                $startDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->startOfQuarter();
                $endDate = Carbon::create($year, ($quarter - 1) * 3 + 1, 1)->endOfQuarter();
                $revenueTitle .= ' (quý ' . $quarter . '/' . $year . ')';
                break;
            case 'year':
                $startDate = Carbon::create($year, 1, 1)->startOfYear();
                $endDate = Carbon::create($year, 12, 31)->endOfYear();
                $revenueTitle .= ' (năm ' . $year . ')';
                break;
            default: // Mặc định là tháng hiện tại
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $revenueTitle .= ' (tháng này)';
                break;
        }

        // --- 1. Thống Kê Tổng Quan ---
        $totalClasses = LopHoc::count();
        $totalStudents = HocVien::count();

        // Doanh thu theo khoảng thời gian đã chọn
        $currentPeriodRevenue = PhieuThu::where('trangthai', 'da_thanh_toan')
            ->whereBetween('ngaythanhtoan', [$startDate, $endDate])
            ->sum('sotien');

        // Tổng số học viên nợ học phí và danh sách học viên còn nợ
        $studentsWithDebt = [];
        $uniqueStudentsWithDebtIds = [];

        $allStudentsInClasses = HocVien::has('lophocs')
            ->with([
                'lophocs.trinhdo.dongia',
                'phieuthu' => function ($query) {
                    $query->where('trangthai', 'da_thanh_toan');
                }
            ])
            ->get();

        foreach ($allStudentsInClasses as $student) {
            $studentHasDebtInAnyClass = false;

            foreach ($student->lophocs as $lophoc) {
                $totalTuitionForClass = optional(optional($lophoc->trinhdo)->dongia)->hocphi ?? 0;
                $paidAmount = $student->phieuthu->where('lophoc_id', $lophoc->id)->sum('sotien');
                $remaining = $totalTuitionForClass - $paidAmount;

                if ($remaining > 0) {
                    $studentHasDebtInAnyClass = true;
                    $studentsWithDebt[] = [
                        'ten' => $student->ten,
                        'lophoc' => $lophoc->tenlophoc ?? null,
                        'remaining_amount' => $remaining
                    ];
                }
            }
            if ($studentHasDebtInAnyClass) {
                $uniqueStudentsWithDebtIds[$student->id] = true;
            }
        }
        $totalStudentsWithDebtCount = count($uniqueStudentsWithDebtIds);


        // --- 2. Thống Kê Lớp Học ---
        $activeClasses = LopHoc::where('trangthai', 'dang_hoat_dong')->count();
        $upcomingClasses = LopHoc::where('trangthai', 'sap_khai_giang')->count();
        $endedClasses = LopHoc::where('trangthai', 'da_ket_thuc')->count();
        $canceledClasses = LopHoc::where('trangthai', 'da_huy')->count();

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

        $fullyPaidStudents = 0;
        $totalStudentsWithDefinedTuition = 0;

        foreach ($allStudentsInClasses as $student) {
            foreach ($student->lophocs as $lophoc) {
                $totalTuition = optional(optional($lophoc->trinhdo)->dongia)->hocphi ?? 0;
                $paidAmount = $student->phieuthu->where('lophoc_id', $lophoc->id)->sum('sotien');

                if ($totalTuition > 0) {
                    $totalStudentsWithDefinedTuition++;
                    if ($paidAmount >= $totalTuition) {
                        $fullyPaidStudents++;
                    }
                }
            }
        }
        $tuitionCompletionRate = ($totalStudentsWithDefinedTuition > 0) ? ($fullyPaidStudents / $totalStudentsWithDefinedTuition) * 100 : 0;


        // --- 4. Thống Kê Tài Chính / Học Phí ---
        usort($studentsWithDebt, function ($a, $b) {
            return $b['remaining_amount'] <=> $a['remaining_amount'];
        });
        $studentsWithDebt = collect(array_slice($studentsWithDebt, 0, 5));


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
            'canceledClasses',
            'endingClasses',
            'newStudentsThisMonth',
            'tuitionCompletionRate',
            'studentsWithDebt',
            'pendingConsultations',
            'period', // Đảm bảo biến này được truyền
            'year',   // Đảm bảo biến này được truyền
            'month',  // Đảm bảo biến này được truyền
            'quarter' // Đảm bảo biến này được truyền
        ));
    }
}
