<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class STIndexCotroller extends Controller
{




    public function index(Request $request)
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Lấy thông tin học viên của người dùng đang đăng nhập
        $user = Auth::user();
        // Giả định User model có mối quan hệ hasOne hoặc belongsTo với HocVien
        // Ví dụ: public function hocvien() { return $this->hasOne(HocVien::class); } trong User model
        $hocvien = $user->hocvien;

        if (!$hocvien) {
            // Nếu tài khoản không liên kết với hồ sơ học viên, chuyển hướng hoặc báo lỗi
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // 3. Lấy danh sách ID các lớp học mà học viên này đang tham gia
        // Giả định HocVien model có mối quan hệ belongsToMany với LopHoc
        // Ví dụ: public function lophocs() { return $this->belongsToMany(LopHoc::class, 'hocvien_lophoc'); }
        $enrolledClassIds = $hocvien->lophocs->pluck('id')->toArray();

        // 4. Xác định ngày hiện tại và thứ trong tuần
        $today = Carbon::now();
        // Carbon::dayOfWeek trả về 0 cho Chủ Nhật, 1 cho Thứ Hai, ..., 6 cho Thứ Bảy
        $currentDayOfWeek = $today->dayOfWeek;

        // Lấy ánh xạ từ Thu model để tìm thu_id tương ứng với currentDayOfWeek
        // Dựa trên thu.sql, thutu 0 là Chủ Nhật, 1 là Thứ 2, v.v.
        $thuToday = Thu::where('thutu', $currentDayOfWeek)->first();

        $scheduleToday = collect(); // Khởi tạo một collection rỗng

        if ($thuToday && !empty($enrolledClassIds)) {
            // 5. Lấy lịch học của học viên cho ngày hôm nay
            $scheduleToday = ThoiKhoaBieu::with([
                'lophoc',
                'giaovien',
                'phonghoc',
                'thu',
                'cahoc',
                'kynang'
            ])
                ->where('thu_id', $thuToday->id) // Lọc theo thứ của ngày hôm nay
                ->whereIn('lophoc_id', $enrolledClassIds) // Lọc theo các lớp học của học viên
                ->whereHas('lophoc', function ($query) use ($today) {
                    // Đảm bảo lớp học đang hoạt động trong khoảng thời gian hiện tại
                    $query->where('ngaybatdau', '<=', $today->toDateString())
                        ->where('ngayketthuc', '>=', $today->toDateString());
                })
                ->orderBy('cahoc_id', 'asc') // Sắp xếp theo ca học để hiển thị theo thứ tự thời gian
                ->get();
        } else {
            Log::info("Học viên ID: {$hocvien->id} không có lớp học nào hoặc không tìm thấy Thu cho ngày hôm nay.");
        }

        // 6. Truyền dữ liệu sang view
        return view('student.dashboard.index', compact('hocvien', 'scheduleToday', 'today'));
    }

    public function profile()
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Lấy thông tin học viên của người dùng đang đăng nhập
        // Eager load mối quan hệ 'user' để lấy email (nếu email nằm trong bảng users)
        $user = Auth::user();
        $hocvien = \App\Models\HocVien::where('user_id', $user->id)
            ->with('user') // Eager load mối quan hệ user
            ->first();

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // 3. Truyền dữ liệu sang view
        return view('student.profile.index', compact('hocvien'));
    }

    public function editprofile()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $hocvien = \App\Models\HocVien::where('user_id', $user->id)
            ->with('user')
            ->first();

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        return view('student.profile.edit', compact('hocvien'));
    }


    public function updateprofile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $hocvien = \App\Models\HocVien::where('user_id', $user->id)
            ->first();

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // Validate dữ liệu đầu vào
        $request->validate([
            // 'ten' => 'required|string|max:255',
            'sdt' => [
                'required',
                'string',
                'max:20',
                // Đảm bảo số điện thoại là duy nhất, trừ số điện thoại hiện tại của học viên
                Rule::unique('hocvien', 'sdt')->ignore($hocvien->id),
            ],
            'diachi' => 'nullable|string|max:255',
            'ngaysinh' => 'nullable|date',
            'gioitinh' => 'nullable|string|in:Nam,Nữ,Khác', // Hoặc các giá trị khác bạn dùng
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh tối đa 2MB
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
            'sdt.unique' => 'Số điện thoại này đã được sử dụng bởi học viên khác.',
            'sdt.max' => 'Số điện thoại không được vượt quá :max ký tự.',
            'diachi.max' => 'Địa chỉ không được vượt quá :max ký tự.',
            'ngaysinh.date' => 'Ngày sinh không hợp lệ.',
            'gioitinh.in' => 'Giới tính không hợp lệ.',
            'hinhanh.image' => 'File tải lên phải là ảnh.',
            'hinhanh.mimes' => 'Ảnh phải có định dạng JPEG, PNG, JPG hoặc GIF.',
            'hinhanh.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            // 'email.unique' => 'Email này đã được sử dụng.',
        ]);

        // Cập nhật thông tin học viên
        // $hocvien->ten = $request->input('ten');
        $hocvien->sdt = $request->input('sdt');
        $hocvien->diachi = $request->input('diachi');
        $hocvien->ngaysinh = $request->input('ngaysinh');
        $hocvien->gioitinh = $request->input('gioitinh');

        // Xử lý ảnh đại diện
        if ($request->hasFile('hinhanh')) {
            // Xóa ảnh cũ nếu có
            if ($hocvien->hinhanh && Storage::disk('public')->exists($hocvien->hinhanh)) {
                Storage::disk('public')->delete($hocvien->hinhanh);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('hinhanh')->store('avatars', 'public');
            $hocvien->hinhanh = $imagePath;
        }

        $hocvien->save();

        // Nếu bạn cho phép cập nhật email, bạn sẽ cập nhật model User tại đây
        // if ($request->has('email') && $user->email !== $request->input('email')) {
        //     $user->email = $request->input('email');
        //     $user->save();
        // }

        return redirect()->route('student.profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
    }
}
