<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HocVi;
use Illuminate\Http\Request;

class HocViController extends Controller
{
    public function index()
    {
        $dsHocVi = HocVi::orderBy('id', 'asc')->paginate(10);
        $nextMa = $this->generateMaHocVi();
        return view('admin.hocvi.index', compact('dsHocVi', 'nextMa'));
    }

    private function generateMaHocVi()
    {
        $lastHV = HocVi::orderBy('id', 'desc')->first();
        if ($lastHV) {
            $lastNumber = (int) substr($lastHV->mahocvi, 3); // bỏ HVI
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        return 'HVI' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenhocvi' => 'required|string|max:255',
            'mota' => 'nullable|string',
        ]);

        HocVi::create([
            'mahocvi' => $this->generateMaHocVi(),
            'tenhocvi' => $request->tenhocvi,
            'mota' => $request->mota,
        ]);

        return redirect()->route('hocvi.index')->with('success', 'Thêm học vị thành công!');
    }

    public function edit($id)
    {
        $hocvi = HocVi::findOrFail($id);
        return response()->json($hocvi); // dùng ajax load dữ liệu vào modal update
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tenhocvi' => 'required|string|max:255',
            'mota' => 'nullable|string',
        ]);

        $hocvi = HocVi::findOrFail($id);
        $hocvi->update([
            'tenhocvi' => $request->tenhocvi,
            'mota' => $request->mota,
        ]);

        return redirect()->route('hocvi.index')->with('success', 'Cập nhật học vị thành công!');
    }

    public function destroy($id)
    {
        HocVi::findOrFail($id)->delete();
        return redirect()->route('hocvi.index')->with('success', 'Xóa học vị thành công!');
    }
}
