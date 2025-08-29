<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use App\Models\ThoiKhoaBieu;
use App\Models\DiemDanh;
use App\Models\Thu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends Controller
{

    /**
     * @property int $giaovien_id
     * @property-read \App\Models\GiaoVien $giaovien
     */
    /**
     * Hiển thị danh sách các buổi dạy của giáo viên
     * và tóm tắt trạng thái điểm danh cho một ngày cụ thể (hoặc ngày hiện tại).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // Lấy ngày được chọn từ request, nếu không có thì mặc định là ngày hiện tại
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $selectedDateString = $selectedDate->toDateString(); // Dạng YYYY-MM-DD

        $currentDayOfWeek = $selectedDate->dayOfWeek; // Lấy thứ trong tuần của ngày được chọn

        $thuSelected = Thu::where('thutu', $currentDayOfWeek)->first();

        $scheduleForSelectedDay = collect();
        $attendanceSummaries = [];

        if ($thuSelected) {
            $scheduleForSelectedDay = ThoiKhoaBieu::with([
                'lophoc.hocviens',
                'phonghoc',
                'thu',
                'cahoc',
                'kynang'
            ])
                ->where('giaovien_id', $giaoVien->id)
                ->where('thu_id', $thuSelected->id)
                ->whereHas('lophoc', function ($query) use ($selectedDate) {
                    // Lọc các lớp học có ngày bắt đầu trước hoặc bằng ngày được chọn
                    // và ngày kết thúc sau hoặc bằng ngày được chọn
                    $query->where('ngaybatdau', '<=', $selectedDate->toDateString())
                        ->where('ngayketthuc', '>=', $selectedDate->toDateString())
                        ->whereIn('trangthai', ['dang_hoat_dong', 'sap_khai_giang']);
                })
                ->orderBy('cahoc_id', 'asc')
                ->get();

            foreach ($scheduleForSelectedDay as $item) {
                // Lấy tóm tắt điểm danh cho buổi học và ngày được chọn
                $attendanceCounts = DiemDanh::where('lophoc_id', $item->lophoc_id)
                    ->where('thoikhoabieu_id', $item->id)
                    ->where('giaovien_id', $giaoVien->id)
                    ->where('ngaydiemdanh', $selectedDateString)
                    ->select('trangthaidiemdanh', DB::raw('count(*) as total'))
                    ->groupBy('trangthaidiemdanh')
                    ->pluck('total', 'trangthaidiemdanh')
                    ->toArray();

                $totalStudentsInClass = $item->lophoc->hocviens->count();

                $attendanceSummaries[$item->id] = [
                    'has_attendance_taken' => !empty($attendanceCounts),
                    'co_mat' => $attendanceCounts['co_mat'] ?? 0,
                    'vang_mat' => $attendanceCounts['vang_mat'] ?? 0,
                    'co_phep' => $attendanceCounts['co_phep'] ?? 0,
                    'di_muon' => $attendanceCounts['di_muon'] ?? 0,
                    'total_students_in_class' => $totalStudentsInClass,
                    'total_recorded' => array_sum($attendanceCounts),
                ];
            }
        }

        return view('teacher.attendence.index', compact('giaoVien', 'scheduleForSelectedDay', 'selectedDate', 'attendanceSummaries'));
    }

    /**
     * Hiển thị form điểm danh cho một lớp học và buổi học cụ thể.
     *
     * @param  \App\Models\LopHoc  $lophoc
     * @param  \App\Models\ThoiKhoaBieu  $thoikhoabieu
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(LopHoc $lophoc, ThoiKhoaBieu $thoikhoabieu, Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('teacher.attendance.index')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        if ($thoikhoabieu->giaovien_id !== $giaoVien->id || $thoikhoabieu->lophoc_id !== $lophoc->id) {
            return redirect()->route('teacher.attendance.index')->with('error', 'Bạn không có quyền điểm danh cho buổi học này.');
        }

        // Lấy ngày điểm danh từ request, nếu không có thì mặc định là ngày hiện tại
        $ngayDiemDanh = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $ngayDiemDanhString = $ngayDiemDanh->toDateString();
        $thoiGianDiemDanh = Carbon::now()->toTimeString('minute'); // Thời gian điểm danh hiện tại

        $students = $lophoc->hocviens()->orderBy('ten', 'asc')->get();

        $existingAttendance = DiemDanh::where('lophoc_id', $lophoc->id)
            ->where('thoikhoabieu_id', $thoikhoabieu->id)
            ->where('ngaydiemdanh', $ngayDiemDanhString)
            ->get()
            ->keyBy('hocvien_id');

        return view('teacher.attendence.form', compact(
            'lophoc',
            'thoikhoabieu',
            'giaoVien',
            'students',
            'ngayDiemDanhString', // Truyền ngày điểm danh dưới dạng string
            'thoiGianDiemDanh',
            'existingAttendance'
        ));
    }

    /**
     * Xử lý lưu trữ/cập nhật điểm danh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LopHoc  $lophoc
     * @param  \App\Models\ThoiKhoaBieu  $thoikhoabieu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, LopHoc $lophoc, ThoiKhoaBieu $thoikhoabieu)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('teacher.attendance.index')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        if ($thoikhoabieu->giaovien_id !== $giaoVien->id || $thoikhoabieu->lophoc_id !== $lophoc->id) {
            return redirect()->route('teacher.attendance.index')->with('error', 'Bạn không có quyền điểm danh cho buổi học này.');
        }

        $ngayDiemDanh = $request->input('ngay_diem_danh');
        $thoiGianDiemDanh = $request->input('thoi_gian_diem_danh');

        // $request->validate([
        //     'ngay_diem_danh' => 'required|date',
        //     'thoi_gian_diem_danh' => 'required|date_format:H:i:s',
        //     'attendance_status.*' => 'required|in:co_mat,vang_mat,co_phep,di_muon',
        //     'note.*' => 'nullable|string|max:500',
        // ], [
        //     'attendance_status.*.required' => 'Trạng thái điểm danh của học viên không được để trống.',
        //     'attendance_status.*.in' => 'Trạng thái điểm danh không hợp lệ.',
        //     'note.*.max' => 'Ghi chú không được vượt quá :max ký tự.',
        // ]);

        try {
            $studentsInClass = $lophoc->hocviens->pluck('id')->toArray();

            foreach ($request->input('attendance_status') as $hocVienId => $status) {
                if (!in_array($hocVienId, $studentsInClass)) {
                    Log::warning("Học viên ID {$hocVienId} không thuộc lớp ID {$lophoc->id} khi điểm danh.");
                    continue;
                }

                $note = $request->input('note.' . $hocVienId);

                DiemDanh::updateOrCreate(
                    [
                        'lophoc_id' => $lophoc->id,
                        'hocvien_id' => $hocVienId,
                        'thoikhoabieu_id' => $thoikhoabieu->id,
                        'ngaydiemdanh' => $ngayDiemDanh,
                    ],
                    [
                        'giaovien_id' => $giaoVien->id,
                        'thoigiandiemdanh' => $thoiGianDiemDanh,
                        'trangthaidiemdanh' => $status,
                        'ghichu' => $note,
                    ]
                );
            }

            // Chuyển hướng về trang index với ngày vừa điểm danh
            return redirect()->route('teacher.attendance.index', ['date' => $ngayDiemDanh])->with('success', 'Điểm danh đã được lưu thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi khi lưu điểm danh: " . $e->getMessage() . " tại dòng " . $e->getLine() . " trong file " . $e->getFile());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lưu điểm danh: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị báo cáo điểm danh chi tiết cho một buổi học cụ thể.
     *
     * @param  \App\Models\LopHoc  $lophoc
     * @param  \App\Models\ThoiKhoaBieu  $thoikhoabieu
     * @param  string  $ngayDiemDanhString Ngày điểm danh dưới dạng YYYY-MM-DD
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showReport(LopHoc $lophoc, ThoiKhoaBieu $thoikhoabieu, $ngayDiemDanhString)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // Kiểm tra xem giáo viên có quyền xem báo cáo cho buổi học này không
        if ($thoikhoabieu->giaovien_id !== $giaoVien->id || $thoikhoabieu->lophoc_id !== $lophoc->id) {
            return redirect()->route('teacher.attendance.index')->with('error', 'Bạn không có quyền xem báo cáo điểm danh cho buổi học này.');
        }

        // Chuyển đổi ngày điểm danh từ string sang Carbon object
        $ngayDiemDanh = Carbon::parse($ngayDiemDanhString);

        // Lấy tất cả các bản ghi điểm danh cho buổi học và ngày cụ thể
        $attendanceRecords = DiemDanh::with(['hocvien', 'giaovien', 'lophoc', 'thoikhoabieu.cahoc', 'thoikhoabieu.phonghoc', 'thoikhoabieu.thu'])
            ->where('lophoc_id', $lophoc->id)
            ->where('thoikhoabieu_id', $thoikhoabieu->id)
            ->where('ngaydiemdanh', $ngayDiemDanh->toDateString())
            ->orderBy('hocvien_id', 'asc')
            ->get();

        // Lấy tổng số học viên trong lớp để so sánh
        $totalStudentsInClass = $lophoc->hocviens->count();

        // Chuẩn bị dữ liệu tóm tắt (có thể dùng lại logic từ index)
        $summary = [
            'co_mat' => $attendanceRecords->where('trangthaidiemdanh', 'co_mat')->count(),
            'vang_mat' => $attendanceRecords->where('trangthaidiemdanh', 'vang_mat')->count(),
            'co_phep' => $attendanceRecords->where('trangthaidiemdanh', 'co_phep')->count(),
            'di_muon' => $attendanceRecords->where('trangthaidiemdanh', 'di_muon')->count(),
            'total_recorded' => $attendanceRecords->count(),
            'total_students_in_class' => $totalStudentsInClass,
        ];

        return view('teacher.attendence.report', compact(
            'lophoc',
            'thoikhoabieu',
            'ngayDiemDanh',
            'attendanceRecords',
            'summary'
        ));
    }
}
