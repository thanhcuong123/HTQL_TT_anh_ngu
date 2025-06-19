<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChucDanh;
use App\Models\ChuyenMon;
use App\Models\GiaoVien;
use App\Models\HocVi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GiaoVienController extends Controller
{
    public function index(Request $request)
    {
        // Get the number of items per page from the request, default to 10 if not specified
        $perPage = $request->input('per_page', 5);

        // Get the search keyword from the request
        $keyword = $request->input('tu_khoa');

        // Start building the query for HocVien
        $query = GiaoVien::with('user');

        // If a search keyword is provided, apply the search filter


        // Paginate the results
        $dsgiaovien = $query->paginate($perPage);

        // Generate new student code
        $lastCourse = GiaoVien::orderBy('magiaovien', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->magiaovien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'GV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        $chucdanh = ChucDanh::all();
        $hocvi = HocVi::all();
        $chuyenmon = ChuyenMon::all();
        // Return the view with the paginated list of students
        return view('admin.giaovien.index', compact(
            'dsgiaovien',
            'newMa',
            'chucdanh',
            'hocvi',
            'chuyenmon'
        ));
    }

    public function store(Request $request)
    {
        // Lấy mã giáo viên cuối cùng từ cơ sở dữ liệu
        $lastTeacher = Giaovien::orderBy('magiaovien', 'desc')->first();

        if ($lastTeacher) {
            // Tách phần số: GV01 => 01
            $lastNumber = (int) substr($lastTeacher->magiaovien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1; // Nếu chưa có giáo viên nào
        }

        // Tạo mã mới: GV + số có 2 chữ số (01, 02, ...)
        $newMa = 'GV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        // Validate the input data
        $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'sdt' => 'nullable|string|max:20',
            'diachi' => 'nullable|string|max:255',
            'ngaysinh' => 'nullable|date',
            'gioitinh' => 'nullable|string|in:nam,nữ',
            'chucdanh_id' => 'nullable|exists:chucdanh,id',
            'hocvi_id' => 'nullable|exists:hocvi,id',
            'chuyenmon_id' => 'nullable|exists:chuyenmon,id',
            'trangthai' => 'nullable|string|max:50',
        ]);

        // Tạo hoặc tìm kiếm người dùng dựa trên email
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->ten,
                'password' => Hash::make(Str::random(10)), // Tạo mật khẩu ngẫu nhiên
                'role' => 'giaovien', // Gán vai trò cho người dùng
            ]
        );

        // Tạo mới giáo viên và liên kết với người dùng
        $giaovien = new Giaovien();
        $giaovien->magiaovien = $newMa; // Gán mã giáo viên mới
        $giaovien->ten = $request->ten;
        $giaovien->sdt = $request->sdt;
        $giaovien->diachi = $request->diachi;
        $giaovien->ngaysinh = $request->ngaysinh;
        $giaovien->gioitinh = $request->gioitinh;
        $giaovien->chucdanh_id = $request->chucdanh_id;
        $giaovien->hocvi_id = $request->hocvi_id;
        $giaovien->chuyenmon_id = $request->chuyenmon_id;
        $giaovien->trangthai = $request->trangthai ?? 'đang dạy'; // Trạng thái mặc định
        $giaovien->user_id = $user->id; // Liên kết với user_id
        $giaovien->save();

        // Redirect back to the teacher list page with a success message
        return redirect()->route('giaovien.index')->with('success', 'Thêm giáo viên mới thành công!');
    }
    public function update(Request $request, $id)
    {
        // Tìm giáo viên theo ID
        $giaovien = Giaovien::findOrFail($id);

        // Validate dữ liệu đầu vào
        $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'sdt' => 'nullable|string|max:20',
            'diachi' => 'nullable|string|max:255',
            'ngaysinh' => 'nullable|date',
            'gioitinh' => 'nullable|string|in:nam,nữ',
            'chucdanh_id' => 'nullable|exists:chucdanh,id',
            'hocvi_id' => 'nullable|exists:hocvi,id',
            'chuyenmon_id' => 'nullable|exists:chuyenmon,id',
            'trangthai' => 'nullable|string|max:50',
        ]);

        // Cập nhật thông tin giáo viên
        $giaovien->ten = $request->ten;
        $giaovien->sdt = $request->sdt;
        $giaovien->diachi = $request->diachi;
        $giaovien->ngaysinh = $request->ngaysinh;
        $giaovien->gioitinh = $request->gioitinh;
        $giaovien->chucdanh_id = $request->chucdanh_id;
        $giaovien->hocvi_id = $request->hocvi_id;
        $giaovien->chuyenmon_id = $request->chuyenmon_id;
        $giaovien->trangthai = $request->trangthai ?? 'đang dạy'; // Trạng thái mặc định

        // Lưu thay đổi
        $giaovien->save();

        // Cập nhật email trong bảng users
        $user = $giaovien->user; // Giả sử bạn đã thiết lập quan hệ giữa Giaovien và User
        if ($user) {
            $user->email = $request->email;
            $user->save();
        }

        // Redirect về trang danh sách giáo viên với thông báo thành công
        return redirect()->route('giaovien.index')->with('success', 'Cập nhật giáo viên thành công!');
    }
}
