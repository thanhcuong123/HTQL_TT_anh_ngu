<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HocVi;
use App\Models\HocVien;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use illuminate\Support\Str;
use App\Models\User;
use illuminate\Support\Facades\Validator;
use illuminate\Support\Facades\DB;
use illuminate\Support\Facades\Log;


class HocVienController extends Controller
{
    //
    public function index(Request $request)
    {
        // Get the number of items per page from the request, default to 10 if not specified
        $perPage = $request->input('per_page', 5);

        // Get the search keyword from the request
        $keyword = $request->input('tu_khoa');

        // Start building the query for HocVien
        $query = HocVien::with('user', 'lophocs');
        $allLopHoc = LopHoc::all();

        // If a search keyword is provided, apply the search filter
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('ten', 'like', '%' . $keyword . '%')
                    ->orWhere('mahocvien', 'like', '%' . $keyword . '%')
                    ->orWhere('diachi', 'like', '%' . $keyword . '%');
            })
                ->orWhereHas('user', function ($query) use ($keyword) {
                    $query->where('email', 'like', '%' . $keyword . '%');
                });
        }

        // Paginate the results
        $dshocvien = $query->orderBy('id', 'desc')->paginate($perPage);

        // Generate new student code
        $lastCourse = HocVien::orderBy('mahocvien', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->mahocvien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'HV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        $dsEmail = HocVien::pluck('email_hv')->toArray();
        $dsSDT = HocVien::pluck('sdt')->toArray();

        // Return the view with the paginated list of students
        return view('admin.hocvien.index', compact('dshocvien', 'newMa', 'allLopHoc', 'dsEmail', 'dsSDT'));
    }

    public function search(Request $request)
    {
        $tuKhoa = $request->input('tu_khoa');

        $dshocvien = HocVien::where(function ($query) use ($tuKhoa) {
            $query->where('ten', 'like', '%' . $tuKhoa . '%')
                ->orWhere('mahocvien', 'like', '%' . $tuKhoa . '%')
                ->orWhere('diachi', 'like', '%' . $tuKhoa . '%');
        })
            ->orWhereHas('user', function ($query) use ($tuKhoa) {
                $query->where('email', 'like', '%' . $tuKhoa . '%');
            })
            ->get();

        return view('admin.hocvien.search_results', compact('dshocvien'));
    }
    public function destroy($id)
    {
        $hv = HocVien::findOrFail($id);
        $hv->delete();
        return redirect()->route('hocvien.index')->with('success', 'xoa hoc vien thanh cong!');
    }
    // public function store(Request $request)
    // {
    //     // Logic tạo mã học viên mới (giữ nguyên từ code của bạn)
    //     $lastHocVien = HocVien::orderBy('mahocvien', 'desc')->first();
    //     if ($lastHocVien) {
    //         $lastNumber = (int) substr($lastHocVien->mahocvien, 2);
    //         $newNumber = $lastNumber + 1;
    //     } else {
    //         $newNumber = 1;
    //     }
    //     $newMa = 'HV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

    //     // --- BỎ QUA VALIDATE VÀ TRANSACTION CHO MỤC ĐÍCH TEST ---

    //     // Tạo hoặc tìm kiếm người dùng dựa trên email
    //     // $user = User::firstOrCreate(
    //     //     ['email' => $request->email],
    //     //     [
    //     //         'name' => $request->ten,
    //     //         'password' => Hash::make(Str::random(10)), // Tạo mật khẩu ngẫu nhiên
    //     //         // 'role' => 'student', // Gán vai trò là 'student'
    //     //     ]
    //     // );
    //     // Lưu ý: Nếu lophoc_id không hợp lệ, dòng này sẽ gây lỗi nếu không có try-catch
    //     $lophoc = LopHoc::findOrFail($request->lophoc_id);

    //     // Kiểm tra sức chứa lớp trước khi thêm học viên
    //     // Lưu ý: Nếu không có try-catch, lỗi sẽ xảy ra nếu lớp đầy
    //     if ($lophoc->soluonghocvienhientai >= $lophoc->soluonghocvientoida) {
    //         // Trong môi trường không có transaction và try-catch,
    //         // bạn có thể cần một cách xử lý lỗi đơn giản hơn hoặc chấp nhận lỗi dừng chương trình.
    //         // return redirect()->back()->with('error', 'Lớp học đã đầy.');
    //         // Hoặc throw new \Exception('Lớp học đã đầy');
    //         // Để phù hợp với yêu cầu "chỉ test", tôi sẽ giữ logic này nhưng không có rollback.
    //         return redirect()->back()->withInput()->with('error', 'Lớp học đã đầy, không thể thêm học viên vào lớp này.');
    //     }

    //     // Liên kết học viên với lớp học đã chọn (lưu vào lophoc_hocvien)
    //     // 'ngaydangky' là trường bổ sung trên bảng pivot


    //     // Cập nhật số lượng học viên hiện tại của lớp
    //     // Tăng lên 1 hoặc đếm lại số lượng học viên sau khi attach

    //     // Tạo mới học viên và liên kết với người dùng
    //     $hocvien = new HocVien();
    //     $hocvien->mahocvien = $newMa; // Gán mã học viên mới đã tạo
    //     $hocvien->ten = $request->ten;
    //     $hocvien->sdt = $request->sdt;
    //     $hocvien->diachi = $request->diachi;
    //     $hocvien->ngaysinh = $request->ngaysinh;
    //     $hocvien->gioitinh = $request->gioitinh;
    //     $hocvien->ngaydangki = now(); // Sử dụng ngaydangki làm ngayvaohoc
    //     $hocvien->trangthai = $request->trangthai;
    //     $hocvien->email_hv = $request->email_hv;
    //     // $hocvien->user_id = $user->id; //dangki Liên kết với user_id
    //     $hocvien->save();
    //     $hocvien->lophocs()->attach($lophoc->id, ['ngaydangky' => now()]);
    //     // Lấy lớp học đã chọn
    //     $lophoc->soluonghocvienhientai = $lophoc->soluonghocvienhientai + 1;
    //     // Hoặc $lophoc->soluonghocvienhientai = $lophoc->hocviens()->count();
    //     $lophoc->save();

    //     // Redirect back to the student list page with a success message
    //     return redirect()->route('hocvien.index')->with('success', 'Thêm học viên mới thành công và đã đăng ký vào lớp học!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'email_hv' => 'required|email|unique:hocvien,email_hv',
            'sdt' => 'required|digits_between:8,15|unique:hocvien,sdt',
        ], [
            'email_hv.unique' => 'Email này đã được sử dụng',
            'sdt.unique' => 'Số điện thoại này đã được sử dụng'
        ]);

        $lastHocVien = HocVien::orderBy('mahocvien', 'desc')->first();
        $newNumber = $lastHocVien ? ((int) substr($lastHocVien->mahocvien, 2)) + 1 : 1;
        $newMa = 'HV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);


        $hocvien = new HocVien();
        $hocvien->mahocvien = $newMa;
        $hocvien->ten = $request->ten;
        $hocvien->sdt = $request->sdt;
        $hocvien->diachi = $request->diachi;
        $hocvien->ngaysinh = $request->ngaysinh;
        $hocvien->gioitinh = $request->gioitinh;
        $hocvien->ngaydangki = now();
        $hocvien->trangthai = $request->trangthai;
        $hocvien->email_hv = $request->email_hv;
        $hocvien->save();


        if ($request->filled('lophoc_id')) {
            $lophoc = LopHoc::findOrFail($request->lophoc_id);

            if ($lophoc->soluonghocvienhientai >= $lophoc->soluonghocvientoida) {
                // Lớp đầy → không gán → chỉ thông báo
                return redirect()->route('hocvien.index')
                    ->with('success', 'Đã thêm học viên, nhưng lớp học đã đầy nên chưa được xếp lớp.');
            }

            $hocvien->lophocs()->attach($lophoc->id, [
                'ngaydangky' => now()
            ]);


            $lophoc->soluonghocvienhientai += 1;
            $lophoc->save();

            return redirect()->route('hocvien.index')
                ->with('success', 'Đã thêm học viên mới và đăng ký vào lớp thành công!');
        }

        return redirect()->route('hocvien.index')
            ->with('success', 'Đã thêm học viên mới (chưa xếp lớp).');
    }

    public function update(Request $request, HocVien $hocvien)
    {

        $user = $hocvien->user;
        if ($user && $user->email !== $request->email) {

            $existingUserWithNewEmail = User::where('email', $request->email)->first();
            if ($existingUserWithNewEmail) {

                $hocvien->user_id = $existingUserWithNewEmail->id;
            } else {

                $user->email = $request->email;
                $user->save();
            }
        } elseif (!$user) {
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->ten,
                    'password' => Hash::make(Str::random(10)),
                ]
            );
            $hocvien->user_id = $user->id;
        }
        $hocvien->ten = $request->ten;
        // $hocvien->email = $request->email; // Update email in the hoc_viens table
        $hocvien->sdt = $request->sdt;
        $hocvien->diachi = $request->diachi;
        $hocvien->ngaysinh = $request->ngaysinh;
        $hocvien->gioitinh = $request->gioitinh;
        $hocvien->ngaydangki = $request->ngaydangki;
        $hocvien->trangthai = $request->trangthai;
        $hocvien->save();
        // 4. Redirect back to the student list page with a success message
        return redirect()->route('hocvien.index')->with('success', 'Cập nhật học viên thành công!');
        // 3. Update student information
    }
    public function createAccount(Request $request)
    {
        $request->validate([
            'hocvien_id' => 'required|exists:hocvien,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Tạo user mới, thêm role là học viên
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'hocvien', // hoặc số, hoặc enum nếu bạn định nghĩa
        ]);

        // Gán user_id cho học viên
        $hocvien = HocVien::findOrFail($request->hocvien_id);
        $hocvien->user_id = $user->id;
        $hocvien->save();

        return back()->with('success', 'Tạo tài khoản thành công!');
    }
}
