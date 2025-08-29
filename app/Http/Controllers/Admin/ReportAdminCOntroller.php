<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HocVien;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\ClassStudentReportSheet;
use App\Exports\ExportPaidStudentReport;
use App\Exports\StudentUnpaidExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ReportAdminController extends Controller
{
    /**
     * Hiển thị form chọn lớp và danh sách học viên trong lớp.
     */
    public function classStudentReport(Request $request)
    {
        $classes = LopHoc::orderBy('tenlophoc')->get();

        $selectedClass = null;
        $students = collect();

        if ($request->filled('class_id')) {
            $selectedClass = LopHoc::with('hocviens')->find($request->class_id);

            if ($selectedClass) {
                $students = $selectedClass->hocviens->sortBy('ten');
            } else {
                return back()->with('error', 'Lớp học không tồn tại.');
            }
        }

        return view('admin.reports.class_student_report', compact('classes', 'selectedClass', 'students'));
    }

    /**
     * Xuất file Excel danh sách học viên trong lớp.
     */
    public function exportClassStudentReport(Request $request)
    {
        $classId = $request->class_id;

        if (!$classId) {
            return back()->with('error', 'Vui lòng chọn lớp học.');
        }

        $selectedClass = LopHoc::with('hocviens')->find($classId);

        if (!$selectedClass) {
            return back()->with('error', 'Lớp học không tồn tại.');
        }

        $students = $selectedClass->hocviens->sortBy('ten');

        $reportDate = 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y');

        return Excel::download(
            new ClassStudentReportSheet($selectedClass, $students, $reportDate),
            'bao_cao_lop_' . Str::slug($selectedClass->tenlophoc) . '.xlsx'
        );
    }

    /**
     * Lấy dữ liệu thanh toán học viên, chính xác quan hệ dongias.
     */
    private function _getStudentPaymentData($classId = null)
    {
        Log::debug("Lấy dữ liệu thanh toán. Lớp ID: " . ($classId ?? 'Tất cả'));

        $students = HocVien::has('lophocs')
            ->with([
                'lophocs' => function ($query) use ($classId) {
                    if ($classId) {
                        $query->where('id', $classId);
                    }
                    $query->with(['trinhdo.dongias', 'khoahoc']);
                },
                'phieuthu' => function ($q) {
                    $q->where('trangthai', 'da_thanh_toan');
                }
            ])
            ->get();

        $paymentData = [];

        foreach ($students as $student) {
            foreach ($student->lophocs as $lophoc) {
                $trinhdo = $lophoc->trinhdo;
                $namhocId = $lophoc->namhoc_id ?? optional($lophoc->khoahoc)->namhoc_id;

                $dongia = $trinhdo ? $trinhdo->dongias->where('namhoc_id', $namhocId)->first() : null;
                $hocphi = $dongia ? $dongia->hocphi : 0;

                $amountPaid = $student->phieuthu->where('lophoc_id', $lophoc->id)->sum('sotien');

                $remaining = $hocphi - $amountPaid;

                $status = 'Chưa thanh toán';
                if ($hocphi == 0) {
                    $status = 'Miễn phí / Không xác định';
                } elseif ($amountPaid >= $hocphi) {
                    $status = 'Đã thanh toán';
                } elseif ($amountPaid > 0) {
                    $status = 'Thanh toán một phần';
                }

                $paymentData[] = [
                    'hocvien_id' => $student->id,
                    'mahocvien' => $student->mahocvien,
                    'tenhocvien' => $student->ten,
                    'lophoc_id' => $lophoc->id,
                    'malophoc' => $lophoc->malophoc,
                    'tenlophoc' => $lophoc->tenlophoc,
                    'total_tuition' => $hocphi,
                    'amount_paid' => $amountPaid,
                    'remaining_balance' => $remaining,
                    'payment_status' => $status,
                ];
            }
        }

        return collect($paymentData);
    }

    /**
     * Báo cáo học viên chưa đóng học phí.
     */
    public function unpaidStudentsReport(Request $request)
    {
        $classes = LopHoc::orderBy('tenlophoc')->get();
        $classId = $request->class_id;

        $data = $this->_getStudentPaymentData($classId);

        $unpaidStudents = $data->filter(fn($item) => $item['remaining_balance'] > 0 && $item['payment_status'] !== 'Miễn phí / Không xác định')
            ->sortBy('tenhocvien');

        $selectedClass = $classId ? LopHoc::find($classId) : null;

        return view('admin.reports.student_unpaid_report', compact('classes', 'unpaidStudents', 'selectedClass'));
    }

    /**
     * Báo cáo học viên đã đóng học phí.
     */
    public function paidStudentsReport(Request $request)
    {
        $classes = LopHoc::orderBy('tenlophoc')->get();
        $classId = $request->class_id;

        $data = $this->_getStudentPaymentData($classId);

        $paidStudents = $data->filter(fn($item) => $item['remaining_balance'] <= 0 || $item['payment_status'] === 'Miễn phí / Không xác định')
            ->sortBy('tenhocvien');

        $selectedClass = $classId ? LopHoc::find($classId) : null;

        return view('admin.reports.student_paid_report', compact('classes', 'paidStudents', 'selectedClass'));
    }

    /**
     * Xuất Excel học viên chưa đóng học phí.
     */
    public function exportUnpaidStudentsReport(Request $request)
    {
        $classId = $request->class_id;

        $data = $this->_getStudentPaymentData($classId);

        $unpaidStudents = $data->filter(fn($item) => $item['remaining_balance'] > 0 && $item['payment_status'] !== 'Miễn phí / Không xác định')
            ->sortBy('tenhocvien');

        $date = 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y');

        $fileName = 'bao_cao_chua_dong.xlsx';
        $selectedClass = $classId ? LopHoc::find($classId) : null;

        if ($selectedClass) {
            $fileName = 'bao_cao_chua_dong_' . Str::slug($selectedClass->tenlophoc) . '.xlsx';
        }

        return Excel::download(
            new StudentUnpaidExport($unpaidStudents, $date, $selectedClass),
            $fileName
        );
    }

    /**
     * Xuất Excel học viên đã đóng học phí.
     */
    public function exportPaidStudentsReport(Request $request)
    {
        $classId = $request->class_id;

        $data = $this->_getStudentPaymentData($classId);

        $paidStudents = $data->filter(fn($item) => $item['remaining_balance'] <= 0 || $item['payment_status'] === 'Miễn phí / Không xác định')
            ->sortBy('tenhocvien');

        $date = 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y');

        $fileName = 'bao_cao_da_thanh_toan.xlsx';
        $selectedClass = $classId ? LopHoc::find($classId) : null;

        if ($selectedClass) {
            $fileName = 'bao_cao_da_thanh_toan_' . Str::slug($selectedClass->tenlophoc) . '.xlsx';
        }

        return Excel::download(
            new ExportPaidStudentReport($paidStudents, $date, $selectedClass),
            $fileName
        );
    }
}
