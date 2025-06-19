<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaHoc;
use App\Models\GiaoVien;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\KyNang;
use App\Models\LopHoc;
use App\Models\PhongHoc;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use App\Models\TrinhDo;
use Illuminate\Http\Request;

class LopHocController extends Controller
{
    // public function index(Request $request)
    // {
    //     $perPage = $request->input('per_page', 2);
    //     $khoahocs = KhoaHoc::all();
    //     $dslophoc = LopHoc::with([
    //         'khoahoc',
    //         'trinhdo',
    //         'giaovien.chucdanh',
    //         'giaovien.hocvi',
    //         'giaovien.chuyenmon'
    //     ])->paginate($perPage); // paginate dùng trực tiếp trên query builder

    //     return view('admin.lophoc.index', compact('dslophoc', 'khoahocs'));
    // }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8); // mặc định 10
        $khoahoc_id = $request->input('khoahoc_id');
        $trinhdos = TrinhDo::all();
        $khoahocs = KhoaHoc::all();
        $giaovien = GiaoVien::all();
        $giaovien = HocVien::all();
        // Query lớp học kèm các quan hệ
        $query = LopHoc::with([
            'khoahoc',
            'trinhdo',
            'giaovien.chucdanh',
            'giaovien.hocvi',
            'giaovien.chuyenmon'
        ]);

        // Nếu có lọc theo khoá học
        if ($khoahoc_id) {
            $query->where('khoahoc_id', $khoahoc_id);
        }

        $dslophoc = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.lophoc.index', compact('dslophoc', 'khoahocs', 'khoahoc_id', 'trinhdos', 'giaovien', 'giaovien'));
    }

    public function show($id)
    {
        // Lấy lớp học theo ID
        $lophoc = LopHoc::with(['giaoVien', 'hocViens', 'thoiKhoaBieus', 'trinhdo.kynang'])->findOrFail($id);
        // Lấy danh sách giáo viên
        $giaovien = $lophoc->giaoVien;
        // Lấy danh sách học viên
        $hocvien = $lophoc->hocViens;
        $hocvienn = HocVien::all();
        $allgiaovien = GiaoVien::all();
        $allphonghoc = PhongHoc::all();
        $allthu = Thu::all();
        $allcahoc =  CaHoc::all();
        $allkynang  = KyNang::all();
        $trinhdos = TrinhDo::all();

        // Lấy thời khóa biểu của lớp học
        $thoikhoabieu = ThoiKhoaBieu::where('lophoc_id', $lophoc->id)->with(['giaovien', 'phonghoc', 'thu', 'cahoc'])->get();
        return view('admin.lophoc.lophocdetail', compact(
            'lophoc',
            'giaovien',
            'hocvien',
            'hocvienn',
            'thoikhoabieu',
            'allgiaovien',
            'allphonghoc',
            'allthu',
            'allcahoc',
            'allkynang',
            'trinhdos'
        ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tenlophoc' => 'required|string|max:255',
            'malophoc' => 'required|string|max:50|unique:lophoc,malophoc',
            'ngaybatdau' => 'required|date',
            'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau',
            'trinhdo_id' => 'required|exists:trinhdo,id',
            'khoahoc_id' => 'required|exists:khoahoc,id',
        ]);
        // Tạo lớp học mới
        LopHoc::create($request->all());
        return redirect()->route('lophoc.index')->with('success', 'Thêm lớp học thành công!');
    }
    public function search(Request $request)
    {
        $tuKhoa = $request->input('tu_khoa');
        $dslophoc = LopHoc::where('tenlophoc', 'like', '%' . $tuKhoa . '%')
            ->orWhere('malophoc', 'like', '%' . $tuKhoa . '%')
            ->get();
        // Trả về view tìm kiếm
        return view('admin.lophoc.search_results', compact('dslophoc'));
    }
    public function destroy($id)
    {
        $lophoc = LopHoc::findOrFail($id);
        $lophoc->delete();

        return redirect()->route('lophoc.index')->with('success', 'Lớp học đã được xóa thành công.');
    }

    public function storeGiaoVien(Request $request, $id)
    {
        // Validation dữ liệu input
        $request->validate([
            'giaovien_id' => 'required|exists:giaovien,id',
        ], [
            'giaovien_id.required' => 'Bạn chưa chọn giáo viên.',
            'giaovien_id.exists' => 'Giáo viên không hợp lệ.',
        ]);
        // Tìm lớp học theo id
        $lophoc = LopHoc::findOrFail($id);
        // Cập nhật giáo viên phụ trách cho lớp học
        $lophoc->giaovien_id = $request->input('giaovien_id');
        $lophoc->save();
        // Chuyển hướng về trang chi tiết lớp học với thông báo thành công
        return redirect()->route('lophoc.show', $lophoc->id)
            ->with('success', 'Đã cập nhật giáo viên phụ trách cho lớp học thành công.');
    }
    public function updateGiaoVien(Request $request, $id)
    {
        $request->validate([
            'giaovien_id' => 'required|exists:giaovien,id',
        ], [
            'giaovien_id.required' => 'Vui lòng chọn một giáo viên.',
            'giaovien_id.exists' => 'Giáo viên không tồn tại.',
        ]);

        $lophoc = LopHoc::findOrFail($id); // tìm đúng bản ghi
        $lophoc->giaovien_id = $request->giaovien_id;
        $lophoc->save();

        return redirect()->back()->with('success', 'Cập nhật giáo viên phụ trách thành công!');
    }

    public function addHocVien(Request $request, $id)
    {
        $request->validate([
            'hocvien_ids' => 'required|array',
            'hocvien_ids.*' => 'exists:hocvien,id',
        ]);

        $lophoc = LopHoc::findOrFail($id);

        $hocvienIds = $request->hocvien_ids;

        // Lọc ra các ID chưa tồn tại trong lớp học
        $existingIds = $lophoc->hocviens()->pluck('hocvien_id')->toArray();
        $newIds = array_diff($hocvienIds, $existingIds);

        $now = now();
        $syncData = [];

        foreach ($newIds as $hocvienId) {
            $syncData[$hocvienId] = ['ngaydangky' => $now];
        }

        $lophoc->hocviens()->attach($syncData);


        return back()->with('success', 'Đã thêm học viên vào lớp.');
    }
    public function addlichoc(Request $request, $lophocId)
    {
        // Validate incoming data
        $validated = $request->validate([
            'giaovien_id' => 'required|exists:giaovien,id',
            'phonghoc_id' => 'required|exists:phonghoc,id',
            'thu_id' => 'required|exists:thu,id',
            'cahoc_id' => 'required|exists:cahoc,id',
            'kynang_id' => 'required|exists:kynang,id',

        ]);
        // Optional: Check that $lophocId corresponds to an existing class
        $lophoc = LopHoc::findOrFail($lophocId);
        // Create new class schedule associated with this class
        $lichhoc = new ThoiKhoaBieu();
        $lichhoc->lophoc_id = $lophoc->id;
        $lichhoc->giaovien_id = $validated['giaovien_id'];
        $lichhoc->phonghoc_id = $validated['phonghoc_id'];
        $lichhoc->thu_id = $validated['thu_id'];
        $lichhoc->cahoc_id = $validated['cahoc_id'];
        $lichhoc->kynang_id = $validated['kynang_id'];

        $lichhoc->save();
        // Redirect back with success message
        return redirect()->route('lophoc.show', $lophoc->id)
            ->with('success', 'Lịch học đã được thêm thành công.');
    }
}
