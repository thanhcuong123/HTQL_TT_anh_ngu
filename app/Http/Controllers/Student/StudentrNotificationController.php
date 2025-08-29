<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ThongBao; // Import Model ThongBao
use App\Models\HocVien;  // Import Model HocVien
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentrNotificationController extends Controller
{
    /**
     * Hiển thị danh sách thông báo cho học viên đang đăng nhập.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Lấy thông tin học viên của người dùng đang đăng nhập
        $user = Auth::user();
        $hocvien = HocVien::where('user_id', $user->id)->first();

        // Nếu tài khoản không liên kết với hồ sơ học viên, chuyển hướng hoặc báo lỗi
        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // 3. Lấy ID của các lớp học mà học viên này đang tham gia
        // Đảm bảo Model HocVien có mối quan hệ 'lophocs' (belongsToMany)
        $enrolledClassIds = $hocvien->lophocs->pluck('id')->toArray();

        // 4. Lấy tất cả thông báo liên quan đến học viên này
        // Eager load các mối quan hệ cần thiết để hiển thị thông tin người gửi và đối tượng nhận
        $notifications = ThongBao::with([
            'nguoigui.giaovien',        // Để lấy tên giáo viên từ bảng giaovien (nếu người gửi là giáo viên)
            'doiTuongNhanLopHoc',       // Để lấy thông tin lớp học khi loaidoituongnhan là 'lop_hoc'
            'doiTuongNhanHocVien.user'  // Để lấy thông tin học viên cụ thể khi loaidoituongnhan là 'hoc_vien_cu_the'
            // Thêm .user nếu muốn hiển thị email/thông tin từ bảng users của học viên cụ thể
        ])
            ->where(function ($query) use ($hocvien, $enrolledClassIds) {
                // Điều kiện 1: Thông báo gửi đến tất cả học viên
                $query->where('loaidoituongnhan', 'tat_ca_hoc_vien');

                // Điều kiện 2: Hoặc thông báo gửi đến các lớp học mà học viên đang tham gia
                if (!empty($enrolledClassIds)) {
                    $query->orWhere(function ($q) use ($enrolledClassIds) {
                        $q->where('loaidoituongnhan', 'lop_hoc')
                            ->whereIn('doituongnhan_id', $enrolledClassIds);
                    });
                }

                // Điều kiện 3: Hoặc thông báo gửi đến học viên cụ thể này
                $query->orWhere(function ($q) use ($hocvien) {
                    $q->where('loaidoituongnhan', 'hoc_vien_cu_the')
                        ->where('doituongnhan_id', $hocvien->id);
                });
            })
            ->where('trangthai', 'da_gui') // Chỉ lấy thông báo đã được gửi (không phải 'nhap')
            ->orderBy('ngaydang', 'desc') // Sắp xếp thông báo mới nhất lên đầu
            ->paginate(10); // Phân trang để tránh tải quá nhiều dữ liệu

        // 5. Truyền dữ liệu sang view
        return view('student.notifications.index', compact('notifications'));
    }
}
