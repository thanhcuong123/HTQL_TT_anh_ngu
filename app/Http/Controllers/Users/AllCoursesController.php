<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllCoursesController extends Controller
{
    public function index()
    {
        $courses = KhoaHoc::all();
        $khoahocss =  DB::table('khoahoc')
            ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
            ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
            ->select(
                'khoahoc.id as khoahoc_id',
                'khoahoc.ma as khoahoc_ten',
                'trinhdo.ten as trinhdo_ten'
            )
            ->distinct()
            ->get();
        // Use 'paginate()' to fetch courses and ensure it's a Paginator instance.
        // It's also good practice to eager load relationships you'll need,
        // like 'lophocs.giaoVien' if you display teacher names on the course cards.
        $khoahocsss = KhoaHoc::with(['lophocs.trinhdo'])->paginate(9); // You can adjust '6' to however many items you want per page.

        // Pass the paginated collection to the view using the correct variable name: 'khoaHocs'.
        return view('pages.allcourses', compact('khoahocsss', 'courses', 'khoahocss'));
    }
}
