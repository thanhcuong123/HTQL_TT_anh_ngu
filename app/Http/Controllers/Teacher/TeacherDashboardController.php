<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ChucDanh;
use App\Models\ChuyenMon;
use App\Models\GiaoVien; // Import Model GiaoVien
use App\Models\HocVi;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Để xử lý file ảnh
use Illuminate\Validation\Rule;

class TeacherDashboardController extends Controller
{
    /**
     * Hiển thị trang tổng quan cho giáo viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
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

        // 3. Xác định ngày hiện tại và thứ trong tuần
        $today = Carbon::now();
        // Carbon::dayOfWeek trả về 0 cho Chủ Nhật, 1 cho Thứ Hai, ..., 6 cho Thứ Bảy
        $currentDayOfWeek = $today->dayOfWeek;

        // Lấy ánh xạ từ Thu model để tìm thu_id tương ứng với currentDayOfWeek
        // Dựa trên thu.sql, thutu 0 là Chủ Nhật, 1 là Thứ 2, v.v.
        $thuToday = Thu::where('thutu', $currentDayOfWeek)->first();

        $scheduleToday = collect(); // Khởi tạo một collection rỗng cho lịch dạy hôm nay

        if ($thuToday) {
            // 4. Lấy lịch dạy của giáo viên cho ngày hôm nay
            $scheduleToday = ThoiKhoaBieu::with([
                'lophoc',
                'phonghoc',
                'thu',
                'cahoc',
                'kynang'
            ])
                ->where('giaovien_id', $giaoVien->id) // Lọc theo ID của giáo viên
                ->where('thu_id', $thuToday->id) // Lọc theo thứ của ngày hôm nay
                ->whereHas('lophoc', function ($query) use ($today) {
                    // Đảm bảo lớp học đang hoạt động trong khoảng thời gian hiện tại
                    $query->where('ngaybatdau', '<=', $today->toDateString())
                        ->where('ngayketthuc', '>=', $today->toDateString())
                        ->whereIn('trangthai', ['dang_hoat_dong', 'sap_khai_giang']); // Chỉ lấy lớp đang hoạt động/sắp khai giảng
                })
                ->orderBy('cahoc_id', 'asc') // Sắp xếp theo ca học để hiển thị theo thứ tự thời gian
                ->get();
        } else {
            Log::info("Giáo viên ID: {$giaoVien->id} không tìm thấy Thu cho ngày hôm nay.");
        }

        // 5. Truyền dữ liệu sang view
        return view('teacher.dashboard.index', compact('giaoVien', 'scheduleToday', 'today'));
    }

    public function profile()
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Lấy thông tin giáo viên của người dùng đang đăng nhập
        // Eager load mối quan hệ 'user' và các mối quan hệ mới
        $user = Auth::user();
        $giaoVien = GiaoVien::where('user_id', $user->id)
            ->with(['user', 'chuyenMon', 'chucDanh', 'hocVi']) // THÊM CÁC MỐI QUAN HỆ NÀY
            ->first();

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // 3. Truyền dữ liệu sang view
        return view('teacher.profile.index', compact('giaoVien'));
    }

    public function editprofile()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = GiaoVien::where('user_id', $user->id)
            ->with(['user', 'chuyenMon', 'chucDanh', 'hocVi']) // Eager load các mối quan hệ
            ->first();

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // Lấy danh sách để đổ vào dropdown
        $chuyenMons = ChuyenMon::all();
        $chucDanhs = ChucDanh::all();
        $hocVis = HocVi::all();

        return view('teacher.profile.edit', compact('giaoVien', 'chuyenMons', 'chucDanhs', 'hocVis'));
    }

    /**
     * Xử lý cập nhật thông tin cá nhân của giáo viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $giaoVien = GiaoVien::where('user_id', $user->id)
            ->first();

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        // Validate dữ liệu đầu vào
        $request->validate([
            // 'ten' => 'required|string|max:255',
            'sdt' => [
                'required',
                'string',
                'max:20',
                Rule::unique('giaovien', 'sdt')->ignore($giaoVien->id),
            ],
            'diachi' => 'nullable|string|max:255',
            'ngaysinh' => 'nullable|date',
            'gioitinh' => 'nullable|string|in:Nam,Nữ,Khác', // Hoặc các giá trị khác bạn dùng
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh tối đa 2MB
            'chuyenmon_id' => 'nullable|exists:chuyenmon,id', // Kiểm tra ID có tồn tại trong bảng chuyenmon
            'chucdanh_id' => 'nullable|exists:chucdanh,id',   // Kiểm tra ID có tồn tại trong bảng chucdanh
            'hocvi_id' => 'nullable|exists:hocvi,id',         // Kiểm tra ID có tồn tại trong bảng hocvi
            // Nếu bạn muốn cho phép cập nhật email, bạn cần thêm vào đây và xử lý trên model User
            // 'email' => [
            //     'required',
            //     'string',
            //     'email',
            //     'max:255',
            //     Rule::unique('users')->ignore($user->id),
            // ],
        ], [
            // 'ten.required' => 'Họ tên không được để trống.',
            'sdt.required' => 'Số điện thoại không được để trống.',
            'sdt.unique' => 'Số điện thoại này đã được sử dụng bởi giáo viên khác.',
            'sdt.max' => 'Số điện thoại không được vượt quá :max ký tự.',
            'diachi.max' => 'Địa chỉ không được vượt quá :max ký tự.',
            'ngaysinh.date' => 'Ngày sinh không hợp lệ.',
            'gioitinh.in' => 'Giới tính không hợp lệ.',
            'hinhanh.image' => 'File tải lên phải là ảnh.',
            'hinhanh.mimes' => 'Ảnh phải có định dạng JPEG, PNG, JPG hoặc GIF.',
            'hinhanh.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'chuyenmon_id.exists' => 'Chuyên môn không hợp lệ.',
            'chucdanh_id.exists' => 'Chức danh không hợp lệ.',
            'hocvi_id.exists' => 'Học vị không hợp lệ.',
            // 'email.unique' => 'Email này đã được sử dụng.',
        ]);

        // Cập nhật thông tin giáo viên
        // $giaoVien->ten = $request->input('ten');
        $giaoVien->sdt = $request->input('sdt');
        $giaoVien->diachi = $request->input('diachi');
        $giaoVien->ngaysinh = $request->input('ngaysinh');
        $giaoVien->gioitinh = $request->input('gioitinh');
        $giaoVien->chuyenmon_id = $request->input('chuyenmon_id'); // Cập nhật ID chuyên môn
        $giaoVien->chucdanh_id = $request->input('chucdanh_id');   // Cập nhật ID chức danh
        $giaoVien->hocvi_id = $request->input('hocvi_id');         // Cập nhật ID học vị

        // Xử lý ảnh đại diện
        if ($request->hasFile('hinhanh')) {
            // Xóa ảnh cũ nếu có
            if ($giaoVien->hinhanh && Storage::disk('public')->exists($giaoVien->hinhanh)) {
                Storage::disk('public')->delete($giaoVien->hinhanh);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('hinhanh')->store('teacher_images', 'public');
            $giaoVien->hinhanh = $imagePath;
        }

        $giaoVien->save();

        // Nếu bạn cho phép cập nhật email, bạn sẽ cập nhật model User tại đây
        // if ($request->has('email') && $user->email !== $request->input('email')) {
        //     $user->email = $request->input('email');
        //     $user->save();
        // }

        return redirect()->route('teacher.profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
    }
}
