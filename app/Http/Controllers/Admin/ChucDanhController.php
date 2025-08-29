<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChucDanh;
use Illuminate\Http\Request;

class ChucDanhController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $keyword = $request->get('tu_khoa');

        $query = ChucDanh::query();

        if ($keyword) {
            $query->where('ten', 'LIKE', "%$keyword%")
                ->orWhere('ma', 'LIKE', "%$keyword%");
        }

        $dsChucDanh = $query->orderBy('id', 'DESC')->paginate($perPage);

        return view('admin.chucdanh.index', compact('dsChucDanh'));
    }

    /**
     * Sinh mã chức danh tự động (CD01, CD02, ...)
     */
    private function generatema()
    {
        $last = ChucDanh::orderBy('id', 'DESC')->first();
        if (!$last) {
            return "CD01";
        }

        $lastNumber = intval(substr($last->ma, 2));
        $newNumber = $lastNumber + 1;

        return "CD" . str_pad($newNumber, 2, "0", STR_PAD_LEFT);
    }

    /**
     * Lưu chức danh mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
        ]);

        $chucdanh = new ChucDanh();
        $chucdanh->ma = $this->generatema();
        $chucdanh->ten = $request->ten;
        $chucdanh->mota = $request->mota;
        $chucdanh->save();

        return redirect()->route('chucdanh.index')->with('success', 'Thêm chức danh thành công!');
    }

    /**
     * Cập nhật chức danh
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
        ]);

        $chucdanh = ChucDanh::findOrFail($id);
        $chucdanh->ten = $request->ten;
        $chucdanh->mota = $request->mota;
        $chucdanh->save();

        return redirect()->route('chucdanh.index')->with('success', 'Cập nhật chức danh thành công!');
    }

    /**
     * Xóa chức danh
     */
    public function destroy($id)
    {
        $chucdanh = ChucDanh::findOrFail($id);
        $chucdanh->delete();

        return redirect()->route('chucdanh.index')->with('success', 'Xóa chức danh thành công!');
    }
}
