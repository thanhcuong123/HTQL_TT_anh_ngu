<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LopHoc; // Import Model LopHoc
use App\Models\ThoiKhoaBieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherClassesController extends Controller
{
    /**
     * Hiển thị danh sách các lớp học mà giáo viên đang đăng nhập được phân công.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Lấy thông tin giáo viên của người dùng đang đăng nhập
        $user = Auth::user();
        // Giả định User model có mối quan hệ hasOne hoặc belongsTo với GiaoVien
        // Ví dụ: public function giaovien() { return $this->hasOne(GiaoVien::class, 'user_id'); } trong User model
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            // Nếu tài khoản không liên kết với hồ sơ giáo viên, chuyển hướng hoặc báo lỗi
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // 3. Lấy danh sách các lớp học mà giáo viên này được phân công thông qua Thời Khóa Biểu
        // Đầu tiên, lấy tất cả các lophoc_id từ bảng thoikhoabieu mà giáo viên này dạy
        $assignedLopHocIds = ThoiKhoaBieu::where('giaovien_id', $giaoVien->id)
            ->pluck('lophoc_id')
            ->unique() // Đảm bảo chỉ lấy các ID lớp học duy nhất
            ->toArray();

        // Nếu không có lớp học nào được phân công qua thời khóa biểu, trả về một collection rỗng
        if (empty($assignedLopHocIds)) {
            $assignedClasses = collect();
        } else {
            // Sau đó, lấy thông tin chi tiết của các lớp học này
            // Eager load các mối quan hệ cần thiết: khoahoc, trinhdo, và đếm số học viên
            // 'thoikhoabieus' vẫn được thêm vào để có thể truy cập thông tin thời khóa biểu của từng lớp
            $assignedClasses = LopHoc::whereIn('id', $assignedLopHocIds)
                ->with(['khoahoc', 'trinhdo', 'thoikhoabieus'])
                ->withCount('hocviens') // Đếm số học viên trong mỗi lớp
                ->orderBy('ngaybatdau', 'desc')
                ->get();
        }

        // 4. Truyền dữ liệu sang view
        return view('teacher.classes.index', compact('giaoVien', 'assignedClasses'));
    }

    /**
     * Hiển thị chi tiết một lớp học cụ thể mà giáo viên được phân công.
     * (Bạn có thể phát triển thêm phương thức này sau nếu cần trang chi tiết lớp riêng cho GV)
     *
     * @param  \App\Models\LopHoc  $lophoc
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(LopHoc $lophoc)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('teacher.classes.index')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // Kiểm tra xem giáo viên này có được phân công dạy lớp học này thông qua thời khóa biểu không
        $isAssignedToClass = ThoiKhoaBieu::where('giaovien_id', $giaoVien->id)
            ->where('lophoc_id', $lophoc->id)
            ->exists();

        if (!$isAssignedToClass) {
            return redirect()->route('teacher.classes.index')->with('error', 'Bạn không có quyền truy cập lớp học này hoặc không được phân công dạy lớp này.');
        }

        // Tải thêm các mối quan hệ cần thiết cho trang chi tiết
        // CHỈNH SỬA TẠI ĐÂY: Lọc thoikhoabieus chỉ lấy của giáo viên hiện tại
        $lophoc->load([
            'khoahoc',
            'trinhdo.dongias', // Thêm dongia để có học phí
            'giaovien', // Giáo viên chủ nhiệm của lớp (nếu có)
            'thoikhoabieus' => function ($query) use ($giaoVien) {
                $query->where('giaovien_id', $giaoVien->id)
                    ->with(['thu', 'cahoc', 'phonghoc.tang.nhahoc', 'kynang']); // Eager load các mối quan hệ con của TKB
            },
            'hocviens' // Tải thông tin user (bao gồm email) của học viên
        ]);

        return view('teacher.classes.class_detail', compact('lophoc'));
    }
}
