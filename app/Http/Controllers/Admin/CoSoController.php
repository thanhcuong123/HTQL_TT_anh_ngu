<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoSo;
use Illuminate\Http\Request;

class CoSoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $query = CoSo::query();
        $dscoso = $query->paginate($perPage)->appends($request->all());
        // $dstrinhdo = TrinhDo::with('kynang')->paginate($perPage)->appends($request->all());
        // $lastCourse = TrinhDo::orderBy('ma', 'desc')->first();
        // if ($lastCourse) {
        //     $lastNumber = (int) substr($lastCourse->ma, 2);
        //     $newNumber = $lastNumber + 1;
        // } else {
        //     $newNumber = 1;
        // }
        // $newMa = 'KH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        // $dscahoc = CaHoc::all();
        return view('admin.coso.index', compact('dscoso'));
    }
    public function store(Request $request)
    {

        $coso = new CoSo();
        $coso->tencoso = $request->tencoso;
        $coso->diachi = $request->diachi;
        $coso->sdt = $request->sdt;
        $coso->email = $request->email;
        $coso->mota = $request->mota;
        $coso->save();
        return redirect()->route('coso.index')->with('success', 'Thêm cơ sở thành công!');
    }
}
