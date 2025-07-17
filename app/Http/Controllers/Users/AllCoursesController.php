<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;

class AllCoursesController extends Controller
{
    public function index()
    {
        $courses = KhoaHoc::all();
        // Use 'paginate()' to fetch courses and ensure it's a Paginator instance.
        // It's also good practice to eager load relationships you'll need,
        // like 'lophocs.giaoVien' if you display teacher names on the course cards.
        $khoahocss = KhoaHoc::with(['lophocs.giaoVien'])->paginate(9); // You can adjust '6' to however many items you want per page.

        // Pass the paginated collection to the view using the correct variable name: 'khoaHocs'.
        return view('pages.allcourses', compact('khoahocss', 'courses'));
    }
}
