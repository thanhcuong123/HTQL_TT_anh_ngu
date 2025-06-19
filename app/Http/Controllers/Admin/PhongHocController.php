<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoSo;
use App\Models\NhaHoc;
use App\Models\PhongHoc;
use App\Models\Tang;
use Illuminate\Http\Request;

class PhongHocController extends Controller
{

    public function index(Request $request)
    {
        // Lấy danh sách phòng học cùng với thông tin liên quan
        $dsphong = PhongHoc::with(['tang.nhahoc.coso'])
            ->paginate($request->input('per_page', 5));
        $cosos = CoSo::all(); // Lấy danh sách cơ sở
        $nhahocs = NhaHoc::all(); // Lấy danh sách nhà học
        $tangs = Tang::all();
        return view('admin.phonghoc.index', compact('dsphong', 'cosos', 'nhahocs', 'tangs'));
    }
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'tenphong' => 'required|string|max:255',
            'succhua' => 'required|integer',
            'tang_id' => 'required|exists:tang,id',

        ]);
        // Tạo mới phòng học
        PhongHoc::create([
            'tenphong' => $request->tenphong,
            'succhua' => $request->succhua,
            'tang_id' => $request->tang_id,

        ]);
        // Trả về thông báo thành công
        return redirect()->back()->with('success', 'Thêm phòng học thành công!');
    }
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $request->validate([
            'tenphong' => 'required|string|max:255',
            'succhua' => 'required|integer',
            'tang_id' => 'required|exists:tang,id',

        ]);

        // Cập nhật phòng học
        $phonghoc = PhongHoc::find($id);
        $phonghoc->tenphong = $request->tenphong;
        $phonghoc->succhua = $request->succhua;
        $phonghoc->tang_id = $request->tang_id;

        $phonghoc->save();

        // Trả về thông báo thành công
        return redirect()->back()->with('success', 'Cập nhật phòng học thành công!');
    }
}
