<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    // public function show($id)
    // {
    //     $khoahocss = KhoaHoc::all();
    //     $lopHoc = LopHoc::with(['khoaHoc', 'trinhDo', 'giaoVien'])->findOrFail($id);

    //     // Tùy chọn: Để hiển thị các lớp học khác trong cùng khóa, đảm bảo quan hệ `lophocs` trong KhoaHoc được tải
    //     // $lopHoc->khoaHoc->load('lophocs'); // Đảm bảo lophocs được load nếu bạn muốn dùng nó trong sidebar

    //     return view('pages.class_detail', compact('lopHoc', 'khoahocss'));
    // }
    public function show($id)
    {
        $courses = KhoaHoc::all();
        $khoahocss = KhoaHoc::all();
        $lopHoc = LopHoc::with([
            'khoaHoc',
            'trinhDo',
            // 'cahocs', // Vẫn giữ buoiHocs nếu bạn có phần accordion buổi học
            'thoiKhoaBieus.giaoVien', // Tải thông tin giáo viên qua thời khóa biểu
            // 'thoiKhoaBieus.phongHoc' 
            'thoiKhoaBieus.giaoVien', // Tải thông tin giáo viên qua thời khóa biểu
            'thoiKhoaBieus.phongHoc' // Tải thông tin phòng học qua thời khóa biểu
        ])->findOrFail($id);

        // Lấy danh sách giáo viên và phòng học duy nhất từ các thời khóa biểu
        // Để hiển thị trong sidebar nếu muốn hiển thị nhiều
        $giaoViens = $lopHoc->thoiKhoaBieus->pluck('giaoVien')->filter()->unique('id');
        $phongHocs = $lopHoc->thoiKhoaBieus->pluck('phongHoc')->filter()->unique('id');

        return view('pages.class_detail', compact('lopHoc', 'khoahocss', 'giaoViens', 'phongHocs', 'courses'));
    }
}
