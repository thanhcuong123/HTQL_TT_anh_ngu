<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\ChuyenMon;
use App\Models\GiaoVien;
use Illuminate\Http\Request;
use App\Models\Khoahoc;
use App\Models\LopHoc;
use App\Models\TrinhDo;

class Homecontroller extends Controller
{
    public function index()
    {
        $khoahocss = Khoahoc::all();
        $courses = KhoaHoc::all();
        $teachers = GiaoVien::all();
        $chuyenmon = ChuyenMon::all();
        return view('pages.content', compact('courses', 'teachers', 'chuyenmon', 'khoahocss'));
    }
}
