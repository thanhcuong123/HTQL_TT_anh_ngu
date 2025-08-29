<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Db;

class AboutController extends Controller
{
    public function index()
    {
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
        return view('pages.about', compact('khoahocss'));
    }
}
