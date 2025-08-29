<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Để làm việc với ngày tháng
use Illuminate\Support\Facades\Log;

class STLophocController extends Controller
{
    public function index(Request $request)
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Lấy thông tin học viên của người dùng đang đăng nhập
        $user = Auth::user();
        $hocvien = $user->hocvien; // Giả định User model có mối quan hệ 'hocvien'

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // 3. Lấy danh sách các lớp học mà học viên này đang tham gia
        // Eager load các mối quan hệ cần thiết để hiển thị thông tin chi tiết
        $enrolledClasses = $hocvien->lophocs()
            ->with(['khoahoc', 'trinhdo', 'hocviens']) // Thêm 'hocviens' để đếm số lượng
            ->orderBy('ngaybatdau', 'desc') // Sắp xếp theo ngày bắt đầu mới nhất
            ->get();

        // 4. Truyền dữ liệu sang view
        return view('student.classes.index', compact('hocvien', 'enrolledClasses'));
    }
    // public function show(Request $request, LopHoc $lophoc)
    // {
    //     // 1. Kiểm tra xem người dùng đã đăng nhập chưa
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
    //     }

    //     // 2. Lấy thông tin học viên của người dùng đang đăng nhập
    //     $user = Auth::user();
    //     $hocvien = $user->hocvien;

    //     if (!$hocvien) {
    //         return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
    //     }

    //     // 3. Kiểm tra xem học viên này có thuộc lớp học này không
    //     // Lấy danh sách ID các lớp học mà học viên đang tham gia
    //     $enrolledClassIds = $hocvien->lophocs->pluck('id')->toArray();

    //     if (!in_array($lophoc->id, $enrolledClassIds)) {
    //         // Nếu học viên không thuộc lớp này, chuyển hướng với thông báo lỗi
    //         return redirect()->route('student.classes')->with('error', 'Bạn không có quyền truy cập lớp học này.');
    //     }

    //     // 4. Eager load các mối quan hệ cần thiết cho lớp học
    //     $lophoc->load([
    //         'khoahoc',
    //         'trinhdo.dongia', // Để lấy học phí nếu cần
    //         'giaovien',
    //         // 'phonghoc',
    //         'thoikhoabieus.thu', // Lịch học và thứ
    //         'thoikhoabieus.cahoc', // Lịch học và ca học
    //         'hocviens' // Danh sách học viên trong lớp
    //     ]);

    //     // Sắp xếp thời khóa biểu theo thứ tự ngày và ca học để hiển thị đẹp hơn
    //     $sortedSchedule = $lophoc->thoikhoabieus->sortBy(function ($item) {
    //         $thuOrder = $item->thu->thutu ?? 99; // Lấy thứ tự từ cột 'thutu' trong bảng 'thu'
    //         $caHocStart = $item->cahoc->thoigianbatdau ?? '23:59:59'; // Thời gian bắt đầu ca học
    //         return $thuOrder . $caHocStart;
    //     });

    //     return view('student.classes.class_detail', compact('lophoc', 'hocvien', 'sortedSchedule'));
    // }

    public function show(LopHoc $lophoc)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $hocvien = \App\Models\HocVien::where('user_id', $user->id)
            ->with('lophocs')
            ->first();

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // Kiểm tra xem học viên có thuộc lớp học này không
        if (!$hocvien->lophocs->contains($lophoc->id)) {
            return redirect()->route('student.classes')->with('error', 'Bạn không có quyền xem chi tiết lớp học này.');
        }

        // Tải các mối quan hệ cần thiết cho lớp học
        $lophoc->load([
            'khoahoc',
            'trinhdo.dongias', // Tải thông tin đơn giá qua trình độ
            'giaovien',
            // 'phonghoc', // Đảm bảo mối quan hệ phonghoc tồn tại trong LopHoc model nếu bạn muốn hiển thị
            'hocviens',
            'thoiKhoaBieus.thu',
            'thoiKhoaBieus.cahoc',
            'thoiKhoaBieus.kynang',
            'thoiKhoaBieus.giaovien', // Thêm giáo viên vào thời khóa biểu để hiển thị trong lịch học
            'thoiKhoaBieus.phonghoc.tang.nhahoc', // Thêm phòng học vào thời khóa biểu để hiển thị trong lịch học
            'taiLieuHocTaps' => function ($query) { // Eager load tài liệu học tập
                $query->whereNotNull('giaovien_id') // Chỉ lấy tài liệu do giáo viên tải lên
                    ->orderBy('created_at', 'desc');
            },
            'taiLieuHocTaps.giaovien' // Eager load thông tin giáo viên tải lên tài liệu
        ]);

        // Sắp xếp lịch học theo thứ và ca học
        $sortedSchedule = $lophoc->thoiKhoaBieus->sortBy(function ($item) {
            $dayOrder = $item->thu->thutu ?? 99; // Giả sử cột 'thutu' trong bảng 'thu'
            $timeOrder = $item->cahoc->thoigianbatdau ?? '23:59:59';
            return $dayOrder . $timeOrder;
        });

        // Truyền dữ liệu sang view
        return view('student.classes.class_detail', compact('lophoc', 'sortedSchedule'));
    }
}
