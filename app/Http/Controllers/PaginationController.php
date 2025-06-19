<?php

namespace App\Http\Controllers;

use App\Models\KhoaHoc;
use Illuminate\Http\Request;

class PaginationController extends Controller
{
    //
    public function index()
    {
        $data['khoahoc'] = KhoaHoc::paginate(3);
        return view('admin.khoahoc.index', compact('data'));
    }
}
