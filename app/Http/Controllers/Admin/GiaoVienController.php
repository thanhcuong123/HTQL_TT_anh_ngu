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
use Illuminate\Support\Facades\Storage;

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
        $allEmails = GiaoVien::pluck('email_gv')->toArray();
        $allSdts = GiaoVien::pluck('sdt')->toArray();
        $allStks = GiaoVien::pluck('stk')->toArray();        // Return the view with the paginated list of students
        return view('admin.giaovien.index', compact(
            'dsgiaovien',
            'newMa',
            'chucdanh',
            'hocvi',
            'chuyenmon',
            'allEmails',
            'allSdts',
            'allStks'

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

        // // Validate the input data
        // $request->validate([
        //     'ten' => 'required|string|max:255',
        //     'email_gv' => 'required|string|email|max:255',
        //     'sdt' => 'nullable|string|max:20',
        //     'diachi' => 'nullable|string|max:255',
        //     'ngaysinh' => 'nullable|date',
        //     'gioitinh' => 'nullable|string|in:nam,nữ',
        //     'chucdanh_id' => 'nullable|exists:chucdanh,id',
        //     'hocvi_id' => 'nullable|exists:hocvi,id',
        //     'chuyenmon_id' => 'nullable|exists:chuyenmon,id',
        //     'trangthai' => 'nullable|string|max:50',
        // ]);
        $imageName = null; // Khởi tạo tên ảnh là null
        if ($request->hasFile('image')) { // Kiểm tra input có tên 'image'
            $imageFile = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->extension(); // Tạo tên file độc đáo
            $imageFile->storeAs('teacher_images', $imageName, 'public'); // Lưu vào storage/app/public/teacher_images
        }
        // Tạo hoặc tìm kiếm người dùng dựa trên email
        // $user = User::firstOrCreate(
        //     ['email' => $request->email],
        //     [
        //         'name' => $request->ten,
        //         'password' => Hash::make(Str::random(10)), // Tạo mật khẩu ngẫu nhiên
        //         'role' => 'giaovien', // Gán vai trò cho người dùng
        //     ]
        // );

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
        $giaovien->email_gv = $request->email;
        // $giaovien->user_id = $user->id;
        $giaovien->hinhanh = $imageName; // Liên kết với user_id
        $giaovien->save();

        // Redirect back to the teacher list page with a success message
        return redirect()->route('giaovien.index')->with('success', 'Thêm giáo viên mới thành công!');
    }
    public function update(Request $request, $id)
    {
        // Tìm giáo viên theo ID
        $giaovien = Giaovien::findOrFail($id);
        // dd($request);
        // Validate dữ liệu đầu vào
        // $request->validate([
        //     'ten' => 'required|string|max:255',
        //     // Email cần là duy nhất, nhưng ngoại trừ email hiện tại của giáo viên này
        //     'email' => 'required|string|email|max:255|unique:users,email,' . $giaovien->user->id,
        //     'sdt' => 'nullable|string|max:20',
        //     'diachi' => 'nullable|string|max:255',
        //     'ngaysinh' => 'nullable|date',
        //     'gioitinh' => 'nullable|string|in:nam,nữ',
        //     'chucdanh_id' => 'nullable|exists:chucdanh,id',
        //     'hocvi_id' => 'nullable|exists:hocvi,id',
        //     'chuyenmon_id' => 'nullable|exists:chuyenmon,id',
        //     'trangthai' => 'nullable|string|max:50',
        //     'stk' => 'nullable|string|max:255', // Thêm validation cho STK
        //     // Validation cho hình ảnh: không bắt buộc, nếu có phải là ảnh và có giới hạn
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        // Cập nhật thông tin giáo viên (sử dụng mảng để dễ dàng hơn)
        $giaovien->ten = $request->ten;
        $giaovien->sdt = $request->sdt;
        $giaovien->diachi = $request->diachi;
        $giaovien->ngaysinh = $request->ngaysinh;
        $giaovien->gioitinh = $request->gioitinh;
        $giaovien->chucdanh_id = $request->chucdanh_id;
        $giaovien->hocvi_id = $request->hocvi_id;
        $giaovien->chuyenmon_id = $request->chuyenmon_id;
        $giaovien->trangthai = $request->trangthai ?? 'đang dạy';
        $giaovien->stk = $request->stk; // Cập nhật STK
        $giaovien->email_gv = $request->email_gv;

        // Xử lý cập nhật hình ảnh
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $imageFile->extension(); // Tạo tên file độc đáo

            // Xóa ảnh cũ nếu có và ảnh cũ tồn tại trên storage
            if ($giaovien->hinhanh && Storage::disk('public')->exists('teacher_images/' . $giaovien->hinhanh)) {
                Storage::disk('public')->delete('teacher_images/' . $giaovien->hinhanh);
            }

            // Lưu ảnh mới vào storage
            $imageFile->storeAs('teacher_images', $imageName, 'public');

            // Cập nhật tên file ảnh mới vào cột 'hinhanh' trong database
            $giaovien->hinhanh = $imageName;
        } elseif ($request->input('clear_image')) { // Nếu người dùng yêu cầu xóa ảnh
            if ($giaovien->hinhanh && Storage::disk('public')->exists('teacher_images/' . $giaovien->hinhanh)) {
                Storage::disk('public')->delete('teacher_images/' . $giaovien->hinhanh);
            }
            $giaovien->hinhanh = null; // Đặt giá trị cột hinhanh về null
        }


        // Lưu thay đổi vào database
        $giaovien->save();

        // Cập nhật email trong bảng users
        // $user = $giaovien->user;
        // if ($user) {
        //     // Validate email cho user update, loại trừ chính user này
        //     $request->validate([
        //         'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        //     ]);
        //     $user->email = $request->email;
        //     $user->save();
        // }

        // Redirect về trang danh sách giáo viên với thông báo thành công
        // return redirect()->route('giaovien.index')->with('success', 'Cập nhật giáo viên thành công!');
        return back()->with('success', 'Cập nhật giáo viên thành công!');
    }


    public function search(Request $request)
    {
        $tu_khoa = $request->input('tu_khoa');

        $giaovien = GiaoVien::with(['user', 'chucdanh', 'chuyenmon', 'hocvi'])
            ->where(function ($query) use ($tu_khoa) {
                $query->where('magiaovien', 'like', '%' . $tu_khoa . '%')
                    ->orWhere('ten', 'like', '%' . $tu_khoa . '%')
                    ->orWhere('sdt', 'like', '%' . $tu_khoa . '%')
                    ->orWhere('diachi', 'like', '%' . $tu_khoa . '%');
            })
            ->orWhereHas('user', function ($query) use ($tu_khoa) {
                $query->where('email', 'like', '%' . $tu_khoa . '%');
            })
            ->orWhereHas('chucdanh', function ($query) use ($tu_khoa) {
                $query->where('ten', 'like', '%' . $tu_khoa . '%');
            })
            ->orWhereHas('chuyenmon', function ($query) use ($tu_khoa) {
                $query->where('tenchuyenmon', 'like', '%' . $tu_khoa . '%');
            })
            ->orWhereHas('hocvi', function ($query) use ($tu_khoa) {
                $query->where('tenhocvi', 'like', '%' . $tu_khoa . '%');
            })
            ->get(); // Use get() for AJAX, not paginate()

        return view('admin.giaovien.search', compact('$giaovien'));
    }
}
