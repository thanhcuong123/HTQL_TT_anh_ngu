<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonGia;
use App\Models\LopHoc;
use App\Models\NamHoc;
use App\Models\TrinhDo;
use Illuminate\Http\Request;

class DongiaController extends Controller
{
    public function index()
    {
        $danhsachdongia = DonGia::with(['trinhdo', 'namhoc'])->paginate(10);
        $trinhdos = TrinhDo::all();
        $namhocs = NamHoc::all();

        return view('admin.dongia.index', compact('danhsachdongia', 'trinhdos', 'namhocs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'trinhdo_id' => 'required|exists:trinhdo,id',
            'namhoc_id' => 'required|exists:namhoc,id',
            'hocphi' => 'required|numeric|min:0',
        ]);

        // Kiểm tra trùng trình độ + năm học
        $exists = DonGia::where('trinhdo_id', $request->trinhdo_id)
            ->where('namhoc_id', $request->namhoc_id)
            ->exists();

        if ($exists) {
            return back()->with('errors', 'Đơn giá này đã tồn tại!');
        }

        DonGia::create($request->only(['trinhdo_id', 'namhoc_id', 'hocphi']));
        return back()->with('success', 'Thêm đơn giá thành công!');
    }
    // public function update(Request $request, $id)
    // {
    //     $dongia = DonGia::findOrFail($id);

    //     $request->validate([
    //         'trinhdo_id' => 'required|exists:trinhdo,id',
    //         'namhoc_id' => 'required|exists:namhoc,id',
    //         'hocphi' => 'required|numeric|min:0',
    //     ]);

    //     // Kiểm tra nếu thay đổi thành bản đã có sẵn
    //     $duplicate = DonGia::where('id', '!=', $id)
    //         ->where('trinhdo_id', $request->trinhdo_id)
    //         ->where('namhoc_id', $request->namhoc_id)
    //         ->exists();

    //     if ($duplicate) {
    //         return back()->with('success', 'Đã có đơn giá này trong hệ thống!');
    //     }

    //     $dongia->update($request->only(['trinhdo_id', 'namhoc_id', 'hocphi']));
    //     return back()->with('success', 'Cập nhật đơn giá thành công!');
    // }
    public function update(Request $request, $id)
    {
        $dongia = DonGia::findOrFail($id);

        $request->validate([
            'trinhdo_id' => 'required|exists:trinhdo,id',
            'namhoc_id'  => 'required|exists:namhoc,id',
            'hocphi'     => 'required|numeric|min:0',
        ]);

        // Tìm lớp học đã áp dụng đơn giá này
        $lopDaApDung = LopHoc::where('trinhdo_id', $dongia->trinhdo_id)
            ->where('namhoc_id', $dongia->namhoc_id)
            ->get();

        if ($lopDaApDung->count() > 0) {
            // Lấy tên lớp để hiển thị trong thông báo
            $tenLop = $lopDaApDung->pluck('tenlophoc')->implode(', ');

            return back()->withErrors([
                'update_error' => "Không thể cập nhật! Đơn giá này đã được áp dụng cho các lớp: {$tenLop}."
            ])->withInput();
        }

        // Kiểm tra trùng lặp với đơn giá khác
        $duplicate = DonGia::where('id', '!=', $id)
            ->where('trinhdo_id', $request->trinhdo_id)
            ->where('namhoc_id', $request->namhoc_id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'update_error' => 'Đã tồn tại đơn giá với trình độ và năm học này!'
            ])->withInput();
        }

        // Nếu chưa có lớp nào dùng, thì cho update
        $dongia->update($request->only(['trinhdo_id', 'namhoc_id', 'hocphi']));

        return back()->with('success', 'Cập nhật đơn giá thành công!');
    }



    public function destroy($id)
    {
        $dongia = DonGia::findOrFail($id);
        $dongia->delete();
        return back()->with('success', 'Xóa đơn giá thành công!');
    }
}
