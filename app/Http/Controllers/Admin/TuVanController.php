<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TuVan;
use Illuminate\Http\Request;

class TuVanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Lấy số lượng item mỗi trang, mặc định là 10
        $tuKhoa = $request->input('tu_khoa'); // Lấy từ khóa tìm kiếm

        $query = TuVan::query();

        // Xử lý tìm kiếm
        if ($tuKhoa) {
            $query->where('hoten', 'like', '%' . $tuKhoa . '%')
                ->orWhere('email', 'like', '%' . $tuKhoa . '%')
                ->orWhere('sdt', 'like', '%' . $tuKhoa . '%')
                ->orWhere('loinhan', 'like', '%' . $tuKhoa . '%');
        }

        // Lấy danh sách yêu cầu tư vấn với phân trang và eager load mối quan hệ khoaHoc
        $dsTuVan = $query->with('khoaHoc')->paginate($perPage)->appends($request->except('page'));

        return view('admin.tuvan.index', compact('dsTuVan'));
    }
}
