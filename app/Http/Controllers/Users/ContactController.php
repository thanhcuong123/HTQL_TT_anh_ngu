<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $khoahocss = KhoaHoc::all();
        return view('pages.contact', compact('khoahocss'));
    }
}
