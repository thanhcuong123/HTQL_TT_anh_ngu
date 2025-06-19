<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhoaHoc;



class KhoaHocController extends Controller
{
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 5);

        $query = KhoaHoc::query();
        $dsKhoaHoc = $query->paginate($perPage)->appends($request->all());
        $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->ma, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'KH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        return view('admin.khoahoc.index', compact('dsKhoaHoc', 'newMa'));
    }
    public  function search(Request $request)
    {
        $tuKhoa = $request->input('tu_khoa');

        $dsKhoaHoc = KhoaHoc::where('ten', 'like', '%' . $tuKhoa . '%')
            ->orWhere('ma', 'like', '%' . $tuKhoa . '%')
            ->get();

        return view('admin.khoahoc.search_results', compact('dsKhoaHoc'));
    }

    public function store(Request $request)
    {
        $lastCourse = KhoaHoc::orderBy('ma', 'desc')->first();

        if ($lastCourse) {
            // Tách phần số: KH01 => 01
            $lastNumber = (int) substr($lastCourse->ma, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1; // Nếu chưa có khóa học nào
        }

        // Tạo mã mới: KH + số có 2 chữ số (01, 02, ...)
        $newMa = 'KH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        $request->validate([

            'ten' => 'required|string|max:255',
            'mota' => 'nullable|string',
            'thoiluong' => 'required|string|max:50',
            'sobuoi' => 'required|integer|min:1'
        ]);

        KhoaHoc::create([
            'ma' => $newMa,
            'ten' => $request->ten,
            'mota' => $request->mota,
            'thoiluong' => $request->thoiluong,
            'sobuoi' => $request->sobuoi,
        ]);

        return redirect()->route('khoahoc.index')->with('success', 'Thêm khóa học thành công!');
    }
    public function update(Request $request, $ma)
    {
        $khoahoc = KhoaHoc::where('ma', $ma)->firstOrFail();

        $khoahoc->ten = $request->input('ten_sua');
        $khoahoc->mota = $request->input('mota_sua');
        $khoahoc->thoiluong = $request->input('thoiluong_sua');
        $khoahoc->sobuoi = $request->input('sobuoi_sua');
        $khoahoc->save();

        return redirect()->route('khoahoc.index')->with('success', 'Cập nhật khóa học thành công!');
    }
    public function destroy($ma)
    {
        $khoahoc = KhoaHoc::findOrFail($ma);
        $khoahoc->delete();
        return  redirect()->route('khoahoc.index')->with('success', 'Xóa khóa học thành công! ');
    }
}
