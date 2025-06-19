<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HocVi;
use App\Models\HocVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use illuminate\Support\Str;
use App\Models\User;
use illuminate\Support\Facades\Validator;

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
        $query = HocVien::with('user');

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
        $dshocvien = $query->paginate($perPage);

        // Generate new student code
        $lastCourse = HocVien::orderBy('mahocvien', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->mahocvien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'HV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        // Return the view with the paginated list of students
        return view('admin.hocvien.index', compact('dshocvien', 'newMa'));
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
    public function store(Request $request)
    {
        // Lấy mã học viên cuối cùng từ cơ sở dữ liệu
        $lastCourse = HocVien::orderBy('mahocvien', 'desc')->first();

        if ($lastCourse) {
            // Tách phần số: HV01 => 01
            $lastNumber = (int) substr($lastCourse->mahocvien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1; // Nếu chưa có học viên nào
        }

        // Tạo mã mới: HV + số có 2 chữ số (01, 02, ...)
        $newMa = 'HV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        // Validate the input data
        $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'sdt' => 'required|string|max:20',
            'diachi' => 'required|string|max:255',
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required|string|in:Nam,Nữ,Khác',
            'ngaydangki' => 'required|date',
            'trangthai' => 'required|string|in:Đang học,Đã tốt nghiệp,Bảo lưu',
        ]);

        // Tạo hoặc tìm kiếm người dùng dựa trên email
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->ten,
                'password' => Hash::make(Str::random(10)),
            ]
        );

        // Tạo mới học viên và liên kết với người dùng
        $hocvien = new HocVien();
        $hocvien->mahocvien = $newMa; // Gán mã học viên mới
        $hocvien->ten = $request->ten;
        $hocvien->sdt = $request->sdt;
        $hocvien->diachi = $request->diachi;
        $hocvien->ngaysinh = $request->ngaysinh;
        $hocvien->gioitinh = $request->gioitinh;
        $hocvien->ngaydangki = $request->ngaydangki;
        $hocvien->trangthai = $request->trangthai;
        $hocvien->user_id = $user->id; // Liên kết với user_id
        $hocvien->save();

        // Redirect back to the student list page with a success message
        return redirect()->route('hocvien.index')->with('success', 'Thêm học viên mới thành công!');
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
}
