<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ChucDanh;
use App\Models\ChuyenMon;
use App\Models\GiaoVien;
use App\Models\HocVi;
use Illuminate\Http\Request;

class StaffGiaovienController extends Controller
{
    public function index(Request $request)
    {
        // Get the number of items per page from the request, default to 10 if not specified
        $perPage = $request->input('per_page', 5);

        // Get the search keyword from the request
        $keyword = $request->input('tu_khoa');

        // Start building the query for HocVien
        $query = GiaoVien::with('user');

        // If a search keyword is provided, apply the search filter


        // Paginate the results
        $dsgiaovien = $query->paginate($perPage);

        // Generate new student code
        $lastCourse = GiaoVien::orderBy('magiaovien', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->magiaovien, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'GV' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        $chucdanh = ChucDanh::all();
        $hocvi = HocVi::all();
        $chuyenmon = ChuyenMon::all();
        // Return the view with the paginated list of students
        return view('staff.giaovien.index', compact(
            'dsgiaovien',
            'newMa',
            'chucdanh',
            'hocvi',
            'chuyenmon'
        ));
    }
}
