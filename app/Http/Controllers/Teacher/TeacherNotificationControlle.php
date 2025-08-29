<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ThongBao; // Import Model ThongBao
use App\Models\LopHoc;   // Import Model LopHoc để lấy danh sách lớp của giáo viên
use App\Models\ThoiKhoaBieu; // Import ThoiKhoaBieu để lấy lớp theo giáo viên
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeacherNotificationControlle extends Controller
{
    /**
     * Hiển thị form tạo thông báo mới cho giáo viên.
     * Giáo viên chỉ có thể gửi thông báo cho các lớp mình đang dạy.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // Lấy tất cả thông báo được gửi bởi người dùng (giáo viên) hiện tại
        $notifications = ThongBao::with([
            'doiTuongNhanLopHoc',   // Để lấy thông tin lớp học nếu gửi cho lớp
            'doiTuongNhanHocVien'   // Để lấy thông tin học viên nếu gửi cho học viên cụ thể
        ])
            ->where('nguoigui_id', $user->id) // Lọc theo ID người gửi (user_id của giáo viên)
            ->orderBy('ngaydang', 'desc')
            ->paginate(5); // Phân trang

        return view('teacher.profile.notifications.index', compact('notifications'));
    }
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // Lấy danh sách các lophoc_id mà giáo viên này được phân công dạy qua thời khóa biểu
        $assignedLopHocIds = ThoiKhoaBieu::where('giaovien_id', $giaoVien->id)
            ->pluck('lophoc_id')
            ->unique()
            ->toArray();

        // Lấy thông tin chi tiết của các lớp học đó
        $assignedClasses = LopHoc::whereIn('id', $assignedLopHocIds)
            ->orderBy('tenlophoc')
            ->get();

        return view('teacher.profile.notifications.create', compact('assignedClasses'));
    }

    /**
     * Xử lý lưu thông báo mới từ giáo viên vào cơ sở dữ liệu.
     * Thông báo luôn được gửi đến một lớp học cụ thể.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'tieude' => 'required|string|max:255',
            'noidung' => 'required|string',
            'lophoc_id' => 'required|exists:lophoc,id', // Lớp học phải được chọn và tồn tại
        ], [
            'tieude.required' => 'Tiêu đề thông báo không được để trống.',
            'tieude.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'noidung.required' => 'Nội dung thông báo không được để trống.',
            'lophoc_id.required' => 'Vui lòng chọn lớp học để gửi thông báo.',
            'lophoc_id.exists' => 'Lớp học được chọn không tồn tại.',
        ]);

        // 2. Lấy thông tin người gửi (giáo viên đang đăng nhập)
        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // 3. Kiểm tra xem giáo viên có quyền gửi thông báo cho lớp này không
        // (Tức là giáo viên này có được phân công dạy lớp này qua thời khóa biểu không)
        $isAssignedToClass = ThoiKhoaBieu::where('giaovien_id', $giaoVien->id)
            ->where('lophoc_id', $request->lophoc_id)
            ->exists();

        if (!$isAssignedToClass) {
            return redirect()->back()->with('error', 'Bạn không được phân công dạy lớp học này để gửi thông báo.');
        }

        // 4. Tạo bản ghi thông báo
        try {
            ThongBao::create([
                'tieude' => $request->tieude,
                'noidung' => $request->noidung,
                'nguoigui_id' => $user->id, // ID của người dùng (giáo viên)
                'loaidoituongnhan' => 'lop_hoc', // Luôn là 'lop_hoc' cho giáo viên
                'doituongnhan_id' => $request->lophoc_id,
                'ngaydang' => Carbon::now(),
                'trangthai' => 'da_gui', // Gửi ngay lập tức
            ]);

            return redirect()->route('teacher.notifications.index')->with('success', 'Thông báo đã được gửi thành công đến lớp học!');
        } catch (\Exception $e) {
            Log::error("Lỗi khi gửi thông báo từ giáo viên: " . $e->getMessage() . " tại dòng " . $e->getLine() . " trong file " . $e->getFile());
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi gửi thông báo: ' . $e->getMessage());
        }
    }
}
