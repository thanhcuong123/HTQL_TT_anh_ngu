<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KyNang;
use Illuminate\Http\Request;

class KynangController extends Controller
{
    //
    public function index(Request $request)
    {
        // $tuKhoa = $request->input('tu_khoa'); // nếu có tìm kiếm


        // if ($tuKhoa) {
        //     $query->where('ten', 'like', '%' . $tuKhoa . '%')
        //         ->orWhere('ma', 'like', '%' . $tuKhoa . '%');
        // }
        $perPage = $request->input('per_page', 5); // mặc định là 10 nếu không chọn

        $query = KyNang::query();
        $dsKynang = $query->paginate($perPage)->appends($request->all());


        return view('admin.kynang.index', compact('dsKynang'));
    }
    public function destroy($id)
    {
        $kynang = KyNang::findOrFail($id);
        $kynang->delete();
        return redirect()->route('kynang.index')->with('success', 'Đã xóa kỹ năng thành công!');
    }
}
