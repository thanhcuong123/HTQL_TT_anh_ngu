<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien; // Import model NhanVien
use App\Models\User;     // Import model User
use App\Models\ChucDanh; // Import model ChucDanh
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class NhanVienController extends Controller
{
    /**
     * Hiển thị danh sách nhân viên với chức năng tìm kiếm và phân trang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request
        $tu_khoa = $request->input('tu_khoa');
        $lastCourse = NhanVien::orderBy('manhanvien', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->manhanvien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'NV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        // Bắt đầu query nhân viên
        $query = NhanVien::with('user', 'chucdanh'); // Eager load user và chucdanh để tránh N+1 query
        $chucdanhs = ChucDanh::all();
        // Áp dụng tìm kiếm nếu có từ khóa
        if ($tu_khoa) {
            $query->where('ten', 'like', '%' . $tu_khoa . '%')
                ->orWhere('manhanvien', 'like', '%' . $tu_khoa . '%');
        }

        // Lấy danh sách nhân viên đã phân trang (ví dụ: 10 nhân viên mỗi trang)
        $dsnhanvien = $query->paginate(10);

        // Lấy tất cả chức danh để đổ vào dropdown trong form thêm/sửa
        $dschucdanh = ChucDanh::all();

        // Trả về view với dữ liệu
        return view('admin.nhanvien.index', compact('dsnhanvien', 'chucdanhs', 'newMa'));
    }

    /**
     * Lưu một nhân viên mới vào cơ sở dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'manhanvien' => 'required|unique:nhanvien,manhanvien',
            'ten' => 'required|string|max:255',
            // 'chucdanh_id' => 'nullable|exists:chucdanh,id',
            'ngaysinh' => 'nullable|string|max:255',
            'gioitinh' => 'nullable|string|max:50',
            'sdt' => 'nullable|string|max:50',
            'diachi' => 'nullable|string|max:255',
        ]);
        $lastCourse = NhanVien::orderBy('manhanvien', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->manhanvien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'NV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        NhanVien::create([
            'manhanvien' => $newMa,
            'ten' => $request->ten,
            'chucdanh_id' => $request->chucdanh_id,
            'sdt' => $request->sdt,
            'ngaysinh' => $request->ngaysinh,
            'gioitinh' => $request->gioitinh,
            'diachi' => $request->diachi,
            'trangthai' => 'dang lam viec',
        ]);

        return redirect()->route('admin.nhanvien')->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Cập nhật thông tin của một nhân viên cụ thể.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NhanVien  $nhanvien
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:nhanvien,id',
            'manhanvien' => 'required|unique:nhanvien,manhanvien,' . $request->id,
            'ten' => 'required',
        ]);

        $nv = NhanVien::findOrFail($request->id);
        $nv->update([
            'manhanvien' => $request->manhanvien,
            'ten' => $request->ten,
            'chucdanh_id' => $request->chucdanh_id,
            'sdt' => $request->sdt,
            'diachi' => $request->diachi,
            'ngaysinh' => $request->ngaysinh,
            'gioitinh' => $request->gioitinh,
            'trangthai' => $request->trangthai,
        ]);

        return redirect()->route('admin.nhanvien')->with('success', 'Cập nhật nhân viên thành công!');
    }

    /**
     * Xóa một nhân viên khỏi cơ sở dữ liệu.
     *
     * @param  \App\Models\NhanVien  $nhanvien
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $nv = NhanVien::findOrFail($id);
        $nv->delete();

        return redirect()->route('nhanvien.index')->with('success', 'Xóa nhân viên thành công!');
    }
}
