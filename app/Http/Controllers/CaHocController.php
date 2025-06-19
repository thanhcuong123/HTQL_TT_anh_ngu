<?php

namespace App\Http\Controllers;

use App\Models\CaHoc;
use Illuminate\Http\Request;

class CaHocController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $query = CaHoc::query();
        $dscahoc = $query->paginate($perPage)->appends($request->all());
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
        return view('admin.cahoc.index', compact('dscahoc'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào

        // Tạo ca học mới
        $cahoc = new CaHoc();

        $cahoc->tenca = $request->tenca;
        $cahoc->thoigianbatdau = $request->thoigianbatdau;
        $cahoc->thoigianketthuc = $request->thoigianketthuc;
        $cahoc->ghichu = $request->ghichu;
        $cahoc->save();
        return redirect()->back()->with('success', 'Thêm ca học thành công!');
    }

    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu đầu vào

        // Tìm ca học theo ID
        $cahoc = CaHoc::findOrFail($id);
        // Cập nhật thông tin ca học
        $cahoc->tenca = $request->tenca;
        $cahoc->thoigianbatdau = $request->thoigianbatdau;
        $cahoc->thoigianketthuc = $request->thoigianketthuc;
        $cahoc->ghichu = $request->ghichu;
        $cahoc->save(); // Lưu thay đổi vào cơ sở dữ liệu
        // Trả về thông báo thành công
        return redirect()->back()->with('success', 'Cập nhật ca học thành công!');
    }
    public function destroy($id)
    {
        $cahoc = CaHoc::findOrFail($id);
        $cahoc->delete();
        return redirect()->route('cahoc.index')->with('success', 'đã xóa ca học thành công!');
    }
}
