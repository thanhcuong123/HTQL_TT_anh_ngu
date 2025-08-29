<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\ChuyenMon;
use App\Models\GiaoVien;
use App\Models\HocVien;
use Illuminate\Http\Request;
use App\Models\Khoahoc;
use App\Models\LopHoc;
use App\Models\TrinhDo;
use Illuminate\Support\Facades\DB;

class Homecontroller extends Controller
{
    public function index()
    {
        // Lấy danh sách khóa học + trình độ (loại bỏ trùng lặp)
        $khoahocss = KhoaHoc::select('khoahoc.id as khoahoc_id', 'khoahoc.ma as khoahoc_ten', 'trinhdo.ten as trinhdo_ten')
            ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
            ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
            ->distinct()
            ->get();
        $khoahocsss = KhoaHoc::with(['lopHocs.trinhDo']) // load luôn lớp học + trình độ
            ->get();
        // Lấy top 6 lớp học có nhiều học viên nhất
        $lopHocs = LopHoc::withCount('hocViens') // Đếm số học viên qua bảng lophoc_hocvien
            ->with(['khoahoc', 'trinhDo']) // Load thông tin khóa học & trình độ
            ->orderByDesc('hoc_viens_count')
            ->take(4)
            ->get();
        // $lopHocs = LopHoc::all();
        // Lấy dữ liệu phụ
        $courses = KhoaHoc::all();
        $teachers = GiaoVien::all();
        $chuyenmon = ChuyenMon::all();
        $trinhdos = TrinhDo::all();
        // Thống kê tổng số
        $totalClasses = LopHoc::count();
        $totalCourses = KhoaHoc::count();
        $totalLevels  = TrinhDo::count();
        $totalStudents = HocVien::count();

        return view('pages.content', compact(
            'courses',
            'lopHocs',
            'teachers',
            'chuyenmon',
            'khoahocss',
            'totalClasses',
            'totalCourses',
            'totalLevels',
            'totalStudents',
            'khoahocsss',
            'trinhdos'
        ));
    }
    public function byKhoaHoc($khoaHocId, $trinhDoId) // Accept both parameters
    {
        // Lấy danh sách khóa học kèm nhiều trình độ (cho dropdown menu)
        // Query này giống với query trong Service Provider của bạn
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

        // Lấy dữ liệu lớp học của khóa học và trình độ được chọn
        $lopHocs = LopHoc::with(['trinhDo', 'khoaHoc', 'giaoVien']) // Eager load necessary relationships
            ->where('khoahoc_id', $khoaHocId)
            ->where('trinhdo_id', $trinhDoId) // Lọc theo cả trình độ ID
            ->orderBy('tenlophoc', 'asc')
            ->paginate(6);

        // Chuyển hướng đến view và truyền dữ liệu
        return view('pages.class-show', compact('lopHocs', 'khoahocss'));
    }
}
