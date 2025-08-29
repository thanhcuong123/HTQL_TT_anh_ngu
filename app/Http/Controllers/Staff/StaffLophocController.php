<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\CaHoc;
use App\Models\DonGia;
use App\Models\GiaoVien;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\KyNang;
use App\Models\LopHoc;
use App\Models\NamHoc;
use App\Models\PhongHoc;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use App\Models\TrinhDo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;


use Carbon\Carbon;

class StaffLophocController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8); // mặc định 10
        $khoahoc_id = $request->input('khoahoc_id');
        $tuKhoa = $request->input('tu_khoa');
        $trinhdos = TrinhDo::all();
        $khoahocs = KhoaHoc::with(['lophocs.trinhdo'])->get();
        $namhocs = NamHoc::all();
        $namhoc_id = $request->namhoc_id;

        $giaovien = GiaoVien::all();
        $allLopHoc = Lophoc::all();
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
            'giaovien.chuyenmon',

        ]);

        // Nếu có lọc theo khoá học
        if ($khoahoc_id) {
            $query->where('khoahoc_id', $khoahoc_id);
        }

        $dslophoc = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('staff.lophoc.index', compact('dslophoc', 'khoahocs', 'khoahoc_id', 'trinhdos', 'giaovien', 'giaovien',  'allLopHoc', 'tuKhoa', 'newMa', 'namhocs', 'namhoc_id'));
    }

    public function show($id)
    {
        // Lấy lớp học theo ID, eager load các mối quan hệ cần thiết
        // Thêm 'khoaHoc' vào eager load để có thể lấy ID khóa học
        $lophoc = LopHoc::with([
            'giaoVien',
            'hocviens.user', // Eager load user của học viên để lấy email/sdt
            'thoiKhoaBieus.giaovien', // Eager load các mối quan hệ của thoikhoabieu
            'thoiKhoaBieus.phonghoc',
            'thoiKhoaBieus.thu',
            'thoiKhoaBieus.cahoc',
            'thoiKhoaBieus.kynang',
            'trinhdo.kynangs',
            'khoaHoc' // Rất quan trọng để lọc học viên theo khóa học
        ])->findOrFail($id);

        // Lấy ID của khóa học mà lớp này thuộc về
        $khoaHocId = $lophoc->khoaHoc ? $lophoc->khoaHoc->id : null;

        // Lấy danh sách ID của các học viên đã có trong lớp này
        $existingStudentIdsInCurrentClass = $lophoc->hocviens->pluck('id')->toArray();

        // Lấy các kỹ năng thuộc Trình độ của lớp này
        $hocvienn = HocVien::whereNotIn('id', $existingStudentIdsInCurrentClass)
            ->orderBy('ten', 'asc')
            ->get();
        // Khởi tạo danh sách học viên đủ điều kiện
        $eligibleHocViens = collect();
        $hocphi = DonGia::where('trinhdo_id', $lophoc->trinhdo_id)
            ->where('namhoc_id', $lophoc->namhoc_id)
            ->value('hocphi');

        if ($khoaHocId) {
            // Lấy tất cả học viên đã đăng ký BẤT KỲ lớp học nào thuộc KHÓA HỌC này
            // và KHÔNG nằm trong danh sách học viên hiện tại của lớp đang xét
            $eligibleHocViens = HocVien::whereHas('lophocs', function ($query) use ($khoaHocId) {
                $query->where('khoahoc_id', $khoaHocId);
            })
                ->whereNotIn('id', $existingStudentIdsInCurrentClass) // Loại bỏ học viên đã có trong lớp
                ->with('user') // Eager load thông tin user để lấy email/sdt
                ->orderBy('ten', 'asc')
                ->get();
        } else {
            // Ghi log nếu lớp học không có khóa học liên kết
            // Log::warning("Lớp học ID {$id} không có khóa học liên kết. Không thể lọc học viên theo khóa học.");
        }

        // Lấy danh sách giáo viên
        $giaovien = $lophoc->giaoVien; // Đây là giáo viên chính của lớp
        // Lấy danh sách học viên hiện tại trong lớp
        $hocvien = $lophoc->hocviens;
        $hocvienn = hocvien::all();
        // Lấy tất cả dữ liệu cho các dropdown/danh sách khác
        $allgiaovien = GiaoVien::all();
        $allphonghoc = PhongHoc::all();
        $allthu = Thu::all();
        $allcahoc = CaHoc::all();
        $allkynang = KyNang::all();
        $trinhdos = TrinhDo::all();
        $allKhoaHoc = KhoaHoc::all();
        $alllophoc = LopHoc::all();

        // Lấy thời khóa biểu của lớp học (đã eager load ở trên)
        $thoikhoabieu = $lophoc->thoiKhoaBieus;

        return view('staff.lophoc.lophocdetail', compact(
            'lophoc',
            'giaovien',
            'hocvien',
            'eligibleHocViens', // THAY THẾ 'hocvienn' BẰNG 'eligibleHocViens'
            'thoikhoabieu',
            'allgiaovien',
            'allphonghoc',
            'allthu',
            'allcahoc',
            'allkynang',
            'trinhdos',
            'allKhoaHoc',
            'alllophoc',
            'hocvienn',
            'hocphi'


        ));
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

        return redirect()->route('staff.lophoc.index')->with('success', 'Lớp học đã được xóa thành công.');
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
        ], [
            'hocvien_ids.required' => 'Vui lòng chọn ít nhất một học viên để thêm.',
            'hocvien_ids.array' => 'Dữ liệu học viên không hợp lệ.',
            'hocvien_ids.*.exists' => 'Một hoặc nhiều học viên được chọn không tồn tại.',
        ]);

        $lophoc = LopHoc::with('thoiKhoaBieus.thu', 'thoiKhoaBieus.cahoc')->findOrFail($id);

        // Lấy thông tin lịch và thời gian của lớp học mới
        $newLophocSchedules = $lophoc->thoiKhoaBieus;
        $newLophocStartDate = Carbon::parse($lophoc->ngaybatdau);
        $newLophocEndDate = Carbon::parse($lophoc->ngayketthuc);

        $hocvienIdsToAdd = $request->hocvien_ids;

        // Lọc ra các ID chưa tồn tại trong lớp học để tránh xử lý lại
        $existingIdsInThisClass = $lophoc->hocviens()->pluck('hocvien_id')->toArray();
        $newIds = array_diff($hocvienIdsToAdd, $existingIdsInThisClass);

        if (empty($newIds)) {
            return back()->with('info', 'Không có học viên mới nào để thêm hoặc tất cả đã có trong lớp.');
        }

        // --- Bắt đầu kiểm tra trùng lịch ---
        foreach ($newIds as $hocvienId) {
            $hocvien = HocVien::with('lopHocs.thoiKhoaBieus.thu', 'lopHocs.thoiKhoaBieus.cahoc')->find($hocvienId);

            if (!$hocvien) {
                // Trường hợp học viên không tồn tại (mặc dù đã validate, nhưng để phòng hờ)
                return back()->with('error', 'Có lỗi xảy ra: Không tìm thấy thông tin học viên ID ' . $hocvienId);
            }

            foreach ($hocvien->lopHocs as $existingLophoc) {
                // Bỏ qua lớp học hiện tại nếu nó là lớp đang được thêm vào (tránh tự so sánh)
                if ($existingLophoc->id == $lophoc->id) {
                    continue;
                }

                $existingLophocStartDate = Carbon::parse($existingLophoc->ngaybatdau);
                $existingLophocEndDate = Carbon::parse($existingLophoc->ngayketthuc);

                // 1. Kiểm tra xem khoảng thời gian của hai lớp có trùng nhau không
                $datesOverlap = ($newLophocStartDate->lte($existingLophocEndDate) && $newLophocEndDate->gte($existingLophocStartDate));

                if ($datesOverlap) {
                    // Nếu khoảng thời gian trùng, kiểm tra lịch học chi tiết
                    foreach ($newLophocSchedules as $newScheduleItem) {
                        foreach ($existingLophoc->thoiKhoaBieus as $existingScheduleItem) {
                            // 2. Kiểm tra xem có trùng Thứ và Ca học không
                            if (
                                $newScheduleItem->thu_id == $existingScheduleItem->thu_id &&
                                $newScheduleItem->cahoc_id == $existingScheduleItem->cahoc_id
                            ) {

                                // Trùng lịch! Báo lỗi và dừng lại
                                $conflictMessage = "Học viên '" . ($hocvien->ten ?? 'N/A') . "' (ID: " . $hocvien->mahocvien . ") bị trùng lịch với lớp '" . ($existingLophoc->tenlophoc ?? 'N/A') . "' (Mã: " . ($existingLophoc->malophoc ?? 'N/A') . ") vào ";
                                $conflictMessage .= ($newScheduleItem->thu->tenthu ?? 'N/A') . " ca " . ($newScheduleItem->cahoc->tenca ?? 'N/A') . " (từ " . ($newScheduleItem->cahoc->thoigianbatdau ?? 'N/A') . " đến " . ($newScheduleItem->cahoc->thoigianketthuc ?? 'N/A') . ").";
                                return back()->with('error', $conflictMessage)->withInput();
                            }
                        }
                    }
                }
            }
        }
        // --- Kết thúc kiểm tra trùng lịch ---
        // --- Kiểm tra số lượng học viên tối đa ---
        $currentCount = $lophoc->soluonghocvienhientai;
        $maxCount = $lophoc->soluonghocvientoida;

        if ($currentCount + count($newIds) > $maxCount) {
            return back()->with('error', "Không thể thêm " . count($newIds) . " học viên. Lớp đã có $currentCount học viên, tối đa chỉ được $maxCount.");
        }

        $now = now();
        $syncData = [];

        foreach ($newIds as $hocvienId) {
            $syncData[$hocvienId] = [
                'ngaydangky' => $now,
                'trangthai' => 'dang_hoc', // Hoặc trạng thái mặc định phù hợp
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        // Gắn (attach) học viên mới vào lớp
        $lophoc->hocviens()->attach($syncData);

        // Cập nhật số lượng học viên hiện tại trong lớp học
        $lophoc->increment('soluonghocvienhientai', count($newIds));

        return back()->with('success', 'Đã thêm học viên vào lớp thành công.');
    }
    // public function addlichoc(Request $request, $lophocId)
    // {
    //     // Validate incoming data
    //     $validated = $request->validate([
    //         'giaovien_id' => 'required|exists:giaovien,id',
    //         'phonghoc_id' => 'required|exists:phonghoc,id',
    //         'thu_id' => 'required|exists:thu,id',
    //         'cahoc_id' => 'required|exists:cahoc,id',
    //         'kynang_id' => 'required|exists:kynang,id',
    //     ]);

    //     // Optional: Check that $lophocId corresponds to an existing class
    //     $lophoc = LopHoc::findOrFail($lophocId);

    //     // --- BẮT ĐẦU KIỂM TRA TRÙNG LỊCH PHÒNG HỌC ---
    //     $existingRoomSchedule = ThoiKhoaBieu::where('phonghoc_id', $validated['phonghoc_id'])
    //         ->where('thu_id', $validated['thu_id'])
    //         ->where('cahoc_id', $validated['cahoc_id'])
    //         ->first(); // Lấy bản ghi đầu tiên nếu có

    //     if ($existingRoomSchedule) {
    //         // Nếu tìm thấy lịch học trùng khớp cho phòng, thứ và ca này
    //         // Lấy thông tin lớp học của lịch trùng để hiển thị thông báo chi tiết
    //         $conflictingClass = $existingRoomSchedule->lophoc; // Giả sử có mối quan hệ 'lophoc' trong ThoiKhoaBieu model
    //         $conflictingClassName = $conflictingClass ? $conflictingClass->tenlophoc : 'một lớp khác';
    //         $conflictingClassCode = $conflictingClass ? $conflictingClass->malophoc : 'N/A';
    //         $conflictingRoomName = $existingRoomSchedule->phonghoc ? $existingRoomSchedule->phonghoc->tenphong : 'N/A';

    //         return redirect()->back()->withInput()
    //             ->with('error', "Phòng học '{$conflictingRoomName}' đã được sử dụng bởi lớp '{$conflictingClassCode} - {$conflictingClassName}' vào cùng thứ và ca này. Vui lòng chọn phòng hoặc thời gian khác.");
    //     }
    //     // --- KẾT THÚC KIỂM TRA TRÙNG LỊCH PHÒNG HỌC ---

    //     // --- BẮT ĐẦU KIỂM TRA TRÙNG LỊCH GIÁO VIÊN ---
    //     $existingTeacherSchedule = ThoiKhoaBieu::where('giaovien_id', $validated['giaovien_id'])
    //         ->where('thu_id', $validated['thu_id'])
    //         ->where('cahoc_id', $validated['cahoc_id'])
    //         ->first(); // Lấy bản ghi đầu tiên nếu có

    //     if ($existingTeacherSchedule) {
    //         // Nếu tìm thấy lịch học trùng khớp cho giáo viên, thứ và ca này
    //         // Lấy thông tin giáo viên và lớp học của lịch trùng để hiển thị thông báo chi tiết
    //         $conflictingTeacher = $existingTeacherSchedule->giaovien; // Giả sử có mối quan hệ 'giaovien' trong ThoiKhoaBieu model
    //         $conflictingTeacherName = $conflictingTeacher ? $conflictingTeacher->ten : 'một giáo viên khác';
    //         $conflictingClassForTeacher = $existingTeacherSchedule->lophoc;
    //         $conflictingClassForTeacherName = $conflictingClassForTeacher ? $conflictingClassForTeacher->tenlophoc : 'một lớp khác';
    //         $conflictingClassForTeacherCode = $conflictingClassForTeacher ? $conflictingClassForTeacher->malophoc : 'N/A';


    //         return redirect()->back()->withInput()
    //             ->with('error', "Giáo viên '{$conflictingTeacherName}' đã có lịch dạy lớp '{$conflictingClassForTeacherCode} - {$conflictingClassForTeacherName}' vào cùng thứ và ca này. Vui lòng chọn giáo viên hoặc thời gian khác.");
    //     }
    //     // --- KẾT THÚC KIỂM TRA TRÙNG LỊCH GIÁO VIÊN ---


    //     // Create new class schedule associated with this class
    //     $lichhoc = new ThoiKhoaBieu();
    //     $lichhoc->lophoc_id = $lophoc->id;
    //     $lichhoc->giaovien_id = $validated['giaovien_id'];
    //     $lichhoc->phonghoc_id = $validated['phonghoc_id'];
    //     $lichhoc->thu_id = $validated['thu_id'];
    //     $lichhoc->cahoc_id = $validated['cahoc_id'];
    //     $lichhoc->kynang_id = $validated['kynang_id'];

    //     $lichhoc->save();

    //     return redirect()->route('lophoc.show', $lophoc->id)
    //         ->with('success', 'Lịch học đã được thêm thành công.');
    // }


    public function addlichoc(Request $request, $lophocId)
    {
        // Validate input với nhiều kỹ năng
        $validated = $request->validate([
            'giaovien_id' => 'required|exists:giaovien,id',
            'phonghoc_id' => 'required|exists:phonghoc,id',
            'thu_ids'     => 'required|array|min:1',
            'thu_ids.*'   => 'exists:thu,id',
            'cahoc_id'    => 'required|exists:cahoc,id',
            'kynang_id'  => 'required|array|min:1',
            'kynang_id.*' => 'exists:kynang,id',
        ]);

        // Tìm lớp học
        $lophoc = LopHoc::with('khoahoc')->findOrFail($lophocId);

        $khoahoc = $lophoc->khoahoc;
        if (!$khoahoc) {
            return back()->with('error', 'Lớp học này chưa gán khóa học.');
        }

        $maxBuoi = $khoahoc->sobuoi; // ✅ Lấy số buổi từ khóa học

        // Tính số buổi đã có
        $currentSchedulesCount = ThoiKhoaBieu::where('lophoc_id', $lophoc->id)
            ->distinct('thu_id')
            ->count('thu_id');

        $newBuoiCount = count($validated['thu_ids']);

        // if (($currentSchedulesCount + $newBuoiCount) > $maxBuoi) {
        //     return redirect()->back()->withInput()
        //         ->with('error', "Khóa học '{$khoahoc->ten}' chỉ quy định tối đa {$maxBuoi} buổi/tuần. Lớp đã có {$currentSchedulesCount} buổi, không thể thêm {$newBuoiCount} buổi nữa.");
        // }
        foreach ($validated['thu_ids'] as $thu_id) {
            foreach ($validated['kynang_id'] as $kynang_id) {

                // 1) Kiểm tra trùng lịch phòng học
                $thu = Thu::find($thu_id);
                $thuName = $thu ? $thu->tenthu : 'Thứ ' . $thu_id;
                $existingRoomSchedule = ThoiKhoaBieu::where('phonghoc_id', $validated['phonghoc_id'])
                    ->where('thu_id', $thu_id)
                    ->where('cahoc_id', $validated['cahoc_id'])
                    ->first();

                if ($existingRoomSchedule) {
                    $conflictingClass = $existingRoomSchedule->lophoc;
                    $conflictingClassName = $conflictingClass ? $conflictingClass->tenlophoc : 'một lớp khác';
                    $conflictingClassCode = $conflictingClass ? $conflictingClass->malophoc : 'N/A';
                    $conflictingRoomName = $existingRoomSchedule->phonghoc ? $existingRoomSchedule->phonghoc->tenphong : 'N/A';

                    return redirect()->back()->withInput()
                        ->with('error', "Phòng học '{$conflictingRoomName}' đã được dùng bởi lớp '{$conflictingClassCode} - {$conflictingClassName}' vào {$thuName} và ca này. Vui lòng chọn lại.");
                }
                $hocvienCount = $lophoc->hocViens()->count();
                $phonghoc = PhongHoc::find($validated['phonghoc_id']);
                if ($phonghoc && $hocvienCount > $phonghoc->succhua) {
                    return redirect()->back()->withInput()
                        ->with('error', "Sĩ số {$hocvienCount} vượt quá sức chứa {$phonghoc->succhua} của phòng '{$phonghoc->tenphong}'.");
                }

                // 2) Kiểm tra trùng lịch giáo viên
                $thu = Thu::find($thu_id);
                $thuName = $thu ? $thu->tenthu : 'Thứ ' . $thu_id;
                $existingTeacherSchedule = ThoiKhoaBieu::where('giaovien_id', $validated['giaovien_id'])
                    ->where('thu_id', $thu_id)
                    ->where('cahoc_id', $validated['cahoc_id'])
                    ->first();

                if ($existingTeacherSchedule) {
                    $conflictingTeacher = $existingTeacherSchedule->giaovien;
                    $conflictingTeacherName = $conflictingTeacher ? $conflictingTeacher->ten : 'Giáo viên khác';
                    $conflictingClassForTeacher = $existingTeacherSchedule->lophoc;
                    $conflictingClassForTeacherName = $conflictingClassForTeacher ? $conflictingClassForTeacher->tenlophoc : 'một lớp khác';
                    $conflictingClassForTeacherCode = $conflictingClassForTeacher ? $conflictingClassForTeacher->malophoc : 'N/A';

                    return redirect()->back()->withInput()
                        ->with('error', "Giáo viên '{$conflictingTeacherName}' đã có lịch dạy lớp '{$conflictingClassForTeacherCode} - {$conflictingClassForTeacherName}' vào   {$thuName} và ca này. Vui lòng chọn lại.");
                }

                // Lưu từng dòng cho từng kỹ năng + thứ
                ThoiKhoaBieu::create([
                    'lophoc_id'   => $lophoc->id,
                    'giaovien_id' => $validated['giaovien_id'],
                    'phonghoc_id' => $validated['phonghoc_id'],
                    'thu_id'      => $thu_id,
                    'cahoc_id'    => $validated['cahoc_id'],
                    'kynang_id'   => $kynang_id,
                ]);
            }
        }

        return redirect()->route('staff.lophoc.show', $lophoc->id)
            ->with('success', 'Đã thêm lịch học thành công.');
    }

    public function update(Request $request, LopHoc $lophoc)
    {
        // ✅ 1. Validate: chỉ các trường có thể sửa
        $validatedData = $request->validate([
            'soluonghocvientoida' => 'required|integer|min:1',
            'trangthai' => [
                'required',
                Rule::in(['dang_hoat_dong', 'da_huy', 'sap_khai_giang', 'da_ket_thuc']),
            ],
            'lichoc' => 'nullable|string|max:255',
            'mota' => 'nullable|string',
            'hinhanh' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $lophoc, $validatedData) {

            $lophoc->soluonghocvientoida = $validatedData['soluonghocvientoida'];
            $lophoc->trangthai = $validatedData['trangthai'];
            $lophoc->lichoc = $validatedData['lichoc'] ?? null;
            $lophoc->mota = $validatedData['mota'] ?? null;

            // Nếu có file ảnh mới thì upload
            if ($request->hasFile('hinhanh')) {
                // Xóa file cũ nếu có
                if ($lophoc->hinhanh && Storage::disk('public')->exists($lophoc->hinhanh)) {
                    Storage::disk('public')->delete($lophoc->hinhanh);
                }

                $path = $request->file('hinhanh')->store('lophoc_images', 'public');
                $lophoc->hinhanh = $path;
            }

            $lophoc->save();
        });

        return redirect()
            ->route('staff.lophoc.show', $lophoc->id)
            ->with('success', 'Cập nhật thông tin lớp học thành công!');
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
}
