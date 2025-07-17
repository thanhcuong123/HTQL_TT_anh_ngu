<?php

namespace App\Http\Controllers\Staff;

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
use Illuminate\Validation\Rule;

class StaffLophocController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8); // mặc định 10
        $khoahoc_id = $request->input('khoahoc_id');
        $tuKhoa = $request->input('tu_khoa');
        $trinhdos = TrinhDo::all();
        $khoahocs = KhoaHoc::all();
        $giaovien = GiaoVien::all();
        $giaovien = HocVien::all();
        $lastclass = LopHoc::orderBy('malophoc', 'desc')->first();
        if ($lastclass) {
            $lastNumber = (int) substr($lastclass->malophoc, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newMa = 'LH' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);


        $hocviens = HocVien::with('user')
            ->when($tuKhoa, function ($query, $tuKhoa) {
                $query->where('ten', 'like', "%{$tuKhoa}%")
                    ->orWhereHas('user', function ($q) use ($tuKhoa) {
                        $q->where('email', 'like', "%{$tuKhoa}%");
                    });
            })
            ->get();
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

        return view('staff.lophoc.index', compact('dslophoc', 'khoahocs', 'khoahoc_id', 'trinhdos', 'giaovien', 'giaovien', 'tuKhoa', 'newMa'));
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
        $allKhoaHoc = KhoaHoc::all();
        $alllophoc = LopHoc::all();

        // Lấy thời khóa biểu của lớp học
        $thoikhoabieu = ThoiKhoaBieu::where('lophoc_id', $lophoc->id)->with(['giaovien', 'phonghoc', 'thu', 'cahoc'])->get();
        return view('staff.lophoc.lophocdetail', compact(
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
            'trinhdos',
            'allKhoaHoc',
            'alllophoc',

        ));
    }
    public function update(Request $request, LopHoc $lophoc)
    {

        // 1. Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'khoahoc_id' => 'required|exists:khoahoc,id', // Đảm bảo khóa học tồn tại
            'trinhdo_id' => 'required|exists:trinhdo,id', // Đảm bảo trình độ tồn tại
            'ngaybatdau' => 'required|date',
            'ngayketthuc' => 'required|date|after_or_equal:ngaybatdau',
            'soluonghocvientoida' => 'required|integer|min:1',
            'trangthai' => [
                'required',
                Rule::in(['dang_hoat_dong', 'da_huy', 'sap_khai_giang', 'da_ket_thuc']), // Chỉ cho phép các giá trị trạng thái này
            ],
            'lichoc' => 'nullable|string|max:255', // Có thể là chuỗi mô tả ngày học
        ]);

        // 2. Cập nhật thông tin lớp học
        try {
            $lophoc->update($validatedData);

            // 3. Chuyển hướng về trang chi tiết hoặc trang danh sách với thông báo thành công
            return redirect()->route('staff.lophoc.show', $lophoc->id)
                ->with('success', 'Cập nhật thông tin lớp học thành công!');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return redirect()->back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật lớp học: ' . $e->getMessage());
        }
    }
    public function removeHocVien(Request $request, LopHoc $lophoc, HocVien $hocvien)
    {
        try {
            // Kiểm tra xem học viên có thực sự thuộc lớp này không trước khi xóa
            if (!$lophoc->hocviens->contains($hocvien->id)) {
                return redirect()->back()->with('error', 'Học viên này không thuộc lớp học đã chọn.');
            }

            // Gỡ bỏ mối quan hệ giữa học viên và lớp học (detach từ bảng trung gian)
            // Giả định bạn có mối quan hệ many-to-many 'hocvien' trong LopHoc model
            // và mối quan hệ 'lophoc' trong HocVien model
            $lophoc->hocviens()->detach($hocvien->id);

            // Cập nhật số lượng học viên hiện tại của lớp học
            // Đảm bảo cột soluonghocvienhientai trong bảng lophoc cho phép giảm
            if ($lophoc->soluonghocvienhientai > 0) {
                $lophoc->decrement('soluonghocvienhientai');
            }

            // Chuyển hướng về trang trước đó với thông báo thành công
            return redirect(route('staff.lophoc.show', $lophoc->id) . '#sectionHocVien')
                ->with('success', 'Đã xóa học viên ' . $hocvien->ten . ' khỏi lớp ' . $lophoc->tenlophoc . ' thành công!');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa học viên khỏi lớp: ' . $e->getMessage());
        }
    }
    public function transferLop(Request $request)
    {
        $validatedData = $request->validate([
            'hocvien_id' => 'required|exists:hocvien,id',
            'old_lophoc_id' => 'required|exists:lophoc,id', // ID lớp học cũ
            'new_lophoc_id' => [
                'required',
                'exists:lophoc,id',
                // Đảm bảo lớp mới không phải là lớp cũ
                Rule::notIn([$request->input('old_lophoc_id')]),
            ],
        ]);

        try {
            $hocvien = HocVien::find($validatedData['hocvien_id']);
            $oldLopHoc = LopHoc::find($validatedData['old_lophoc_id']);
            $newLopHoc = LopHoc::find($validatedData['new_lophoc_id']);

            if (!$hocvien || !$oldLopHoc || !$newLopHoc) {
                return redirect()->back()->with('error', 'Dữ liệu không hợp lệ. Vui lòng thử lại.');
            }

            // Kiểm tra xem học viên có đang thuộc lớp cũ không
            if (!$hocvien->lophocs->contains($oldLopHoc->id)) {
                return redirect()->back()->with('error', 'Học viên không thuộc lớp học cũ đã chỉ định.');
            }

            // Kiểm tra xem học viên đã thuộc lớp mới chưa (để tránh thêm trùng lặp)
            if ($hocvien->lophocs->contains($newLopHoc->id)) {
                return redirect()->back()->with('error', 'Học viên đã thuộc lớp học mới này rồi.');
            }

            // 1. Gỡ bỏ mối quan hệ với lớp học cũ
            $hocvien->lopHocs()->detach($oldLopHoc->id);

            // 2. Thêm mối quan hệ với lớp học mới
            $hocvien->lophocs()->attach($newLopHoc->id);

            // 3. Cập nhật số lượng học viên cho cả hai lớp
            if ($oldLopHoc->soluonghocvienhientai > 0) {
                $oldLopHoc->decrement('soluonghocvienhientai');
            }
            $newLopHoc->increment('soluonghocvienhientai');

            // Chuyển hướng về trang chi tiết của lớp học MỚI HOẶC lớp học CŨ
            // Tùy theo mong muốn của bạn, ở đây tôi chuyển về lớp cũ và tab học viên
            return redirect()->route('staff.lophoc.show', $oldLopHoc->id . '#sectionHocVien')
                ->with('success', 'Đã chuyển học viên ' . $hocvien->ten . ' từ lớp ' . $oldLopHoc->tenlophoc . ' sang lớp ' . $newLopHoc->tenlophoc . ' thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi chuyển lớp cho học viên: ' . $e->getMessage());
        }
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
            $syncData[$hocvienId] = [
                'ngaydangky' => $now,
                'trangthai' => 'dang_hoc', // Hoặc trạng thái mặc định phù hợp
                'created_at' => $now, // Thêm created_at
                'updated_at' => $now // Thêm updated_at
            ];
        }

        // Gắn (attach) học viên mới vào lớp
        $lophoc->hocviens()->attach($syncData);

        // Cập nhật số lượng học viên hiện tại trong lớp học
        // Chỉ tăng số lượng bằng số học viên thực sự được thêm mới
        $lophoc->increment('soluonghocvienhientai', count($newIds));

        return back()->with('success', 'Đã thêm học viên vào lớp thành công.');
    }
    public function destroylichhoc(LopHoc $lophoc, ThoiKhoaBieu $thoikhoabieu)
    {
        // Kiểm tra xem lịch học có thực sự thuộc về lớp học này không (tùy chọn)
        if ($thoikhoabieu->lophoc_id !== $lophoc->id) {
            abort(404);
        }

        $thoikhoabieu->delete();

        return redirect()->route('staff.lophoc.show', $lophoc->id)
            ->with('success', 'Lịch học đã được xóa thành công.');
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

        // --- BẮT ĐẦU KIỂM TRA TRÙNG LỊCH PHÒNG HỌC ---
        $existingSchedule = ThoiKhoaBieu::where('phonghoc_id', $validated['phonghoc_id'])
            ->where('thu_id', $validated['thu_id'])
            ->where('cahoc_id', $validated['cahoc_id'])
            ->first(); // Lấy bản ghi đầu tiên nếu có

        if ($existingSchedule) {
            // Nếu tìm thấy lịch học trùng khớp cho phòng, thứ và ca này
            // Lấy thông tin lớp học của lịch trùng để hiển thị thông báo chi tiết
            $conflictingClass = $existingSchedule->lophoc; // Giả sử có mối quan hệ 'lophoc' trong ThoiKhoaBieu model
            $conflictingClassName = $conflictingClass ? $conflictingClass->tenlophoc : 'một lớp khác';

            return redirect()->back()->withInput()
                ->with('error', "Phòng học này đã được sử dụng bởi lớp '{$conflictingClassName}'. Vui lòng chọn phòng hoặc thời gian khác.");
        }
        // --- KẾT THÚC KIỂM TRA TRÙNG LỊCH PHÒNG HỌC ---


        // Create new class schedule associated with this class
        $lichhoc = new ThoiKhoaBieu();
        $lichhoc->lophoc_id = $lophoc->id;
        $lichhoc->giaovien_id = $validated['giaovien_id'];
        $lichhoc->phonghoc_id = $validated['phonghoc_id'];
        $lichhoc->thu_id = $validated['thu_id'];
        $lichhoc->cahoc_id = $validated['cahoc_id'];
        $lichhoc->kynang_id = $validated['kynang_id'];

        $lichhoc->save();

        return redirect()->route('staff.lophoc.show', $lophoc->id)
            ->with('success', 'Lịch học đã được thêm thành công.');
    }
}
