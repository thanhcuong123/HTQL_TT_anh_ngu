<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Toàn bộ danh sách KH khác nếu cần hiển thị sidebar
        $courses = KhoaHoc::all();

        // Lấy lớp và Eager load đầy đủ quan hệ
        $lopHoc = LopHoc::with([
            'khoaHoc',
            'trinhDo.dongias', // Lấy đơn giá để khỏi query lại
            'thoiKhoaBieus.giaoVien',
            'thoiKhoaBieus.phongHoc'
        ])->findOrFail($id);

        // Lấy danh sách giáo viên & phòng học duy nhất từ thời khoá biểu
        $giaoViens = $lopHoc->thoiKhoaBieus->pluck('giaoVien')->filter()->unique('id');
        $phongHocs = $lopHoc->thoiKhoaBieus->pluck('phongHoc')->filter()->unique('id');

        // Lấy danh sách KH + Trình độ nếu con cần render ở view (sidebar)
        $khoahocss = DB::table('khoahoc')
            ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
            ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
            ->select(
                'khoahoc.id as khoahoc_id',
                'khoahoc.ma as khoahoc_ten',
                'trinhdo.id as trinhdo_id',
                'trinhdo.ten as trinhdo_ten'
            )
            ->distinct()
            ->get();

        // Xác định học phí: Lấy đơn giá khớp với năm học của lớp (nếu có)
        $namhocId = $lopHoc->namhoc_id ?? $lopHoc->khoaHoc->namhoc_id ?? null;

        $hocPhi = null;
        if ($lopHoc->trinhDo && $lopHoc->trinhDo->dongias && $namhocId) {
            $dongia = $lopHoc->trinhDo->dongias
                ->where('namhoc_id', $namhocId)
                ->first();
            $hocPhi = $dongia ? $dongia->hocphi : null;
        }

        // Truyền sang view
        return view('pages.class_detail', compact(
            'lopHoc',
            'khoahocss',
            'giaoViens',
            'phongHocs',
            'courses',
            'hocPhi'
        ));
    }
}
