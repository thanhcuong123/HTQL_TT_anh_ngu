<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\TuVan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Để lấy thông tin người dùng đang đăng nhập
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    // public function index()
    // {

    //     $employeeId = Auth::id();


    //     $consultations = TuVan::where('created_at', $employeeId)
    //         ->whereDate('created_at', Carbon::today())
    //         ->orderBy('created_at', 'asc')
    //         ->get();

    //     return view('staff.dashboard', compact('consultations'));
    // }
    public function index()
    {
        $user = Auth::user();
        // Giả định User model có mối quan hệ hasOne hoặc belongsTo với HocVien
        // Ví dụ: public function hocvien() { return $this->hasOne(HocVien::class); } trong User model
        $nhanvien = $user->nhanvien;
        // Lấy danh sách tất cả các buổi tư vấn được tạo trong ngày hôm nay
        // và sắp xếp theo thời gian tạo tăng dần.
        $consultations = TuVan::whereDate('created_at', Carbon::today())
            ->where('trangthai', 'đang chờ xử lý')
            ->orderBy('created_at', 'asc')
            ->get();

        // Trả về view với dữ liệu đã lấy
        return view('staff.dashboard', compact('consultations', 'nhanvien'));
    }
}
