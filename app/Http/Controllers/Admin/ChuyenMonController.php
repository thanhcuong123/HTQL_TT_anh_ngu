<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuyenMon;
use Illuminate\Http\Request;

class ChuyenMonController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tuKhoa = $request->input('tu_khoa');

        $query = ChuyenMon::query();

        if (!empty($tuKhoa)) {
            $query->where('machuyenmon', 'like', "%$tuKhoa%")
                ->orWhere('tenchuyenmon', 'like', "%$tuKhoa%");
        }

        $dsChuyenMon = $query->paginate($perPage);

        // Sinh mã tiếp theo
        $lastCM = ChuyenMon::orderBy('id', 'desc')->first();
        if ($lastCM) {
            $lastNumber = (int) substr($lastCM->machuyenmon, 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        $nextMa = 'CM' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        return view('admin.chuyenmon.index', compact('dsChuyenMon', 'nextMa'));
    }


    // Thêm mới chuyên môn
    public function store(Request $request)
    {
        // Lấy mã lớn nhất hiện có
        $lastCM = ChuyenMon::orderBy('id', 'desc')->first();

        if ($lastCM) {
            // Tách phần số ra khỏi mã (ví dụ CM01 -> 01)
            $lastNumber = (int) substr($lastCM->machuyenmon, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Tạo mã mới theo dạng CM01, CM02...
        $machuyenmon = 'CM' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        // Lưu
        ChuyenMon::create([
            'machuyenmon' => $machuyenmon,
            'tenchuyenmon' => $request->tenchuyenmon,
            'mota' => $request->mota,
        ]);

        return redirect()->route('chuyenmon.index')->with('success', 'Thêm chuyên môn thành công!');
    }


    // Cập nhật chuyên môn
    public function update(Request $request, $id)
    {
        $cm = ChuyenMon::findOrFail($id);

        $request->validate([
            'machuyenmon' => 'required|max:50|unique:chuyenmon,machuyenmon,' . $cm->id,
            'tenchuyenmon' => 'required|max:255',
            'mota' => 'nullable|string'
        ]);

        $cm->update([
            'machuyenmon' => $request->machuyenmon,
            'tenchuyenmon' => $request->tenchuyenmon,
            'mota' => $request->mota,
        ]);

        return redirect()->route('chuyenmon.index')->with('success', 'Cập nhật chuyên môn thành công!');
    }

    // Xóa chuyên môn
    public function destroy($id)
    {
        $cm = ChuyenMon::findOrFail($id);
        $cm->delete();

        return redirect()->route('chuyenmon.index')->with('success', 'Xóa chuyên môn thành công!');
    }
}
