<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonGia;
use App\Models\KhoaHoc;
use App\Models\TrinhDo;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\KyNang;
use App\Models\NamHoc;

class TrinhdoController extends Controller
{
    //



    public function test()
    {
        $dsKhoaHoc = KhoaHoc::all();
        return view('admin.trinhdo.phantrangtest', compact('dsKhoaHoc'));
    }


    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        // $query = TrinhDo::query();
        // $dstrinhdo = $query->paginate($perPage)->appends($request->all());
        $dstrinhdo = TrinhDo::with('kynang', 'dongia.namhoc')->paginate($perPage)->appends($request->all());
        $lastCourse = TrinhDo::orderBy('ma', 'desc')->first();
        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->ma, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'TD' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        $dsKyNang = KyNang::all();
        $dsNamHoc = NamHoc::all();
        return view('admin.trinhdo.index', compact('dstrinhdo', 'newMa', 'dsKyNang', 'dsNamHoc'));
    }
    public  function search(Request $request)
    {
        $tuKhoa = $request->input('tu_khoa');

        $dstrinhdo = TrinhDo::where('ten', 'like', '%' . $tuKhoa . '%')
            ->orWhere('ma', 'like', '%' . $tuKhoa . '%')
            ->get();

        return view('admin.trinhdo.search_results', compact('dstrinhdo'));
    }
    public function store(Request $request)
    {
        $lastCourse = TrinhDo::orderBy('ma', 'desc')->first();

        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->ma, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newMa = 'TD' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        $request->validate([

            'ten' => 'required|string|max:255',
            'mota' => 'nullable|string',

        ]);

        $trinhDo = TrinhDo::create([
            'ma' => $newMa,
            'ten' => $request->ten,
            'kynang_id' => $request->kynang_id,
            'mota' => $request->mota,
        ]);

        $hoc_phi_input = $request['hoc_phi'];
        $hoc_phi = preg_replace('/[^\d]/', '', $hoc_phi_input); // Loại bỏ ký tự không phải số
        $hoc_phi = (float)$hoc_phi; // Chuyển đổi thành số
        // Thêm học phí vào bảng học phí
        DonGia::create([
            'trinhdo_id' => $trinhDo->id,
            'namhoc_id' => $request->namhoc_id,
            'hocphi' => $hoc_phi,
        ]);

        return redirect()->route('trinhdo.index')->with('success', 'Thêm trình độ mới thành công!');
    }
    public function update(Request $request, $ma)
    {
        $khoahoc = TrinhDo::where('ma', $ma)->firstOrFail();

        $khoahoc->ten = $request->input('ten_sua');
        $khoahoc->mota = $request->input('mota_sua');
        $khoahoc->kynang_id = $request->input('kynang_id_sua');

        $khoahoc->save();
        $hocphi = str_replace('.', '', $request->input('hoc_phi_sua')); // Xóa dấu phân cách
        $hocphi = (float)$hocphi; // Chuyển sang kiểu số
        DonGia::updateOrCreate(
            ['trinhdo_id' => $khoahoc->id],
            [
                'namhoc_id' => $request->input('namhoc_id_sua'),
                'hocphi' => $hocphi,
                'update_at' => now()
            ]
        );
        return redirect()->back()->with('success', 'Cập nhật thành công!!');
    }
    public function destroy($ma)
    {
        $khoahoc = TrinhDo::findOrFail($ma);
        $khoahoc->delete();
        return redirect()->back()->with('success', 'Đã xóa thành công!!');
    }
}
