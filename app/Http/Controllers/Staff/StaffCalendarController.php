<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\CaHoc;
use App\Models\GiaoVien;
use App\Models\KyNang;
use App\Models\LopHoc;
use App\Models\PhongHoc;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StaffCalendarController extends Controller
{
    public function getCalendarEvents(Request $request)
    {
        try {
            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'));

            $events = [];

            // Lấy ánh xạ giữa ID của Thứ và số thứ tự ngày trong tuần của Carbon từ database
            // Dựa trên thu.sql, cột 'thutu' đã tồn tại và có dữ liệu chính xác
            $thuMap = Thu::pluck('thutu', 'id')->toArray();

            // Lấy tất cả các thời khóa biểu đang hoạt động hoặc trong tương lai
            $thoikhoabieuItems = ThoiKhoaBieu::with([
                'lophoc',
                'giaovien',
                'phonghoc',
                'thu',
                'cahoc',
                'kynang'
            ])
                ->whereHas('lophoc', function ($query) use ($start, $end) {
                    $query->where('ngaybatdau', '<=', $end)
                        ->where('ngayketthuc', '>=', $start);
                })
                ->get();

            foreach ($thoikhoabieuItems as $item) {
                // Kiểm tra xem các mối quan hệ có tồn tại không trước khi truy cập
                // Nếu một trong số này là null, có nghĩa là eager loading thất bại hoặc dữ liệu không hợp lệ
                if (!$item->lophoc || !$item->giaovien || !$item->phonghoc || !$item->thu || !$item->cahoc || !$item->kynang) {
                    Log::warning("Mục thời khóa biểu ID " . ($item->id ?? 'N/A') . " thiếu dữ liệu mối quan hệ.");
                    continue;
                }

                $lophoc = $item->lophoc;
                $giaovien = $item->giaovien;
                $phonghoc = $item->phonghoc;
                $thu = $item->thu;
                $cahoc = $item->cahoc;
                $kynang = $item->kynang;

                // Lấy số thứ tự ngày trong tuần của Carbon từ ánh xạ ID của Thứ
                $targetDayOfWeek = $thuMap[$thu->id] ?? null;

                if (is_null($targetDayOfWeek)) {
                    Log::warning("Không tìm thấy ánh xạ 'thutu' cho Thu ID: {$thu->id} (Tên: {$thu->tenthu}).");
                    continue;
                }

                // Đảm bảo ngaybatdau và ngayketthuc của lophoc là ngày hợp lệ
                try {
                    $actualLopHocStartDate = Carbon::parse($lophoc->ngaybatdau)->max($start);
                    $actualLopHocEndDate = Carbon::parse($lophoc->ngayketthuc)->min($end);
                } catch (\Exception $e) {
                    Log::error("Lỗi phân tích ngày của Lớp học ID {$lophoc->id}: " . $e->getMessage());
                    continue;
                }

                for ($date = clone $actualLopHocStartDate; $date->lte($actualLopHocEndDate); $date->addDay()) {
                    if ($date->dayOfWeek == $targetDayOfWeek) {
                        // SỬA TÊN CỘT THỜI GIAN TỪ 'giobatdau'/'gioketthuc' SANG 'thoigianbatdau'/'thoigianketthuc'
                        $eventStartDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $cahoc->thoigianbatdau);
                        $eventEndDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $cahoc->thoigianketthuc);

                        $events[] = [
                            'id'    => $item->id,
                            'title' => $lophoc->malophoc . ' - ' . $lophoc->tenlophoc,
                            'start' => $eventStartDateTime->toIso8601String(),
                            'end'   => $eventEndDateTime->toIso8601String(),
                            'url'   => route('staff.lophoc.show', $lophoc->id), // Đảm bảo route 'lophoc.show' tồn tại
                            'extendedProps' => [
                                'classId' => $lophoc->id,
                                'className' => $lophoc->tenlophoc,
                                'classCode' => $lophoc->malophoc,
                                'teacher' => $giaovien->ten ?? 'N/A',
                                'room' => $phonghoc->tenphong ?? 'N/A',
                                'skill' => $kynang->ten ?? 'N/A',
                                'caHoc' => $cahoc->tenca,
                                'thu' => $thu->tenthu,
                            ],
                            'classNames' => [
                                'event-default' // Class mặc định nếu không có logic màu cụ thể
                            ],
                        ];
                    }
                }
            }

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error("Lỗi khi lấy sự kiện lịch: " . $e->getMessage() . " tại dòng " . $e->getLine() . " trong file " . $e->getFile());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi tải sự kiện lịch.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // public function index()
    // {
    //     return view('staff.tkb.index');
    // }
    public function index()
    {
        $lophocs = LopHoc::all();
        $giaoviens = GiaoVien::all();
        $phongs = PhongHoc::all();
        $allthu = Thu::all(); // Giả định bạn có bảng 'thu' với cột 'ten_thu'
        $cas = CaHoc::all();
        $kynangs = KyNang::all();   // Giả định bạn có bảng 'ca' với cột 'ten_ca', 'gio_bat_dau', 'gio_ket_thuc'

        return view('staff.tkb.index', compact('lophocs', 'giaoviens', 'phongs', 'allthu', 'cas', 'kynangs'));
    }
    // app/Http/Controllers/StaffLopHocController.php
    public function getKyNangByLopHoc($lophocId)
    {
        $lophoc = LopHoc::with('trinhdo.kynangs')->find($lophocId);

        if (!$lophoc || !$lophoc->trinhdo) {
            return response()->json(['kynangs' => []]);
        }

        $kynangs = $lophoc->trinhdo->kynangs->map(function ($kn) {
            return [
                'id' => $kn->id,
                'ten' => $kn->ten
            ];
        });

        return response()->json(['kynangs' => $kynangs]);
    }
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'lophoc_id' => 'required|exists:lophoc,id',
            'giaovien_id' => 'required|exists:giaovien,id',
            'phong_id' => 'required|exists:phonghoc,id',
            'ca_id' => 'required|exists:cahoc,id',
            'thu_ids' => 'required|array|min:1', // Vẫn nhận mảng từ form
            'thu_ids.*' => 'exists:thu,id',
            'kynang_id' => 'required|array|min:1', // Vẫn nhận mảng từ form
            'kynang_id.*' => 'exists:kynang,id',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        $selectedThuIds = $request->input('thu_ids');
        $selectedKynangIds = $request->input('kynang_id');
        $lophocId = $request->lophoc_id;
        $giaovienId = $request->giaovien_id;
        $phongId = $request->phong_id;
        $caId = $request->ca_id;
        $ghiChu = $request->ghi_chu;

        $errors = [];
        $successCount = 0;

        foreach ($selectedThuIds as $thuId) {
            foreach ($selectedKynangIds as $kynangId) {
                // Kiểm tra trùng lịch cho từng cặp (thu_id, kynang_id)

                // 1) Kiểm tra trùng lịch phòng học
                $existingRoomSchedule = ThoiKhoaBieu::where('phonghoc_id', $phongId)
                    ->where('thu_id', $thuId)
                    ->where('cahoc_id', $caId)
                    ->first();

                if ($existingRoomSchedule) {
                    $conflictingClass = $existingRoomSchedule->lophoc;
                    $conflictingClassName = $conflictingClass ? $conflictingClass->tenlophoc : 'một lớp khác';
                    $conflictingClassCode = $conflictingClass ? $conflictingClass->malophoc : 'N/A';
                    $conflictingRoomName = $existingRoomSchedule->phonghoc; // Dùng mối quan hệ phòng
                    $conflictingRoomName = $conflictingRoomName ? $conflictingRoomName->tenphong : 'N/A';

                    $thuName = Thu::find($thuId)->tenthu ?? 'N/A';
                    $errors[] = "Phòng học '{$conflictingRoomName}' đã được dùng bởi lớp '{$conflictingClassCode} - {$conflictingClassName}' vào {$thuName} và ca này.";
                    continue; // Bỏ qua cặp này và tiếp tục vòng lặp
                }

                // 2) Kiểm tra sức chứa phòng học
                $lophoc = LopHoc::find($lophocId);
                if ($lophoc) {
                    $hocvienCount = $lophoc->hocViens()->count();
                    $phonghoc = PhongHoc::find($phongId); // Tìm phòng học
                    if ($phonghoc && $hocvienCount > $phonghoc->succhua) {
                        $errors[] = "Sĩ số {$hocvienCount} của lớp '{$lophoc->tenlophoc}' vượt quá sức chứa {$phonghoc->succhua} của phòng '{$phonghoc->tenphong}'.";
                        continue;
                    }
                }


                // 3) Kiểm tra trùng lịch giáo viên
                $existingTeacherSchedule = ThoiKhoaBieu::where('giaovien_id', $giaovienId)
                    ->where('thu_id', $thuId)
                    ->where('cahoc_id', $caId)
                    ->first();

                if ($existingTeacherSchedule) {
                    $conflictingTeacher = $existingTeacherSchedule->giaovien;
                    $conflictingTeacherName = $conflictingTeacher ? $conflictingTeacher->ten : 'Giáo viên khác';
                    $conflictingClassForTeacher = $existingTeacherSchedule->lophoc;
                    $conflictingClassForTeacherName = $conflictingClassForTeacher ? $conflictingClassForTeacher->tenlophoc : 'một lớp khác';
                    $conflictingClassForTeacherCode = $conflictingClassForTeacher ? $conflictingClassForTeacher->malophoc : 'N/A';

                    $thuName = Thu::find($thuId)->tenthu ?? 'N/A';
                    $errors[] = "Giáo viên '{$conflictingTeacherName}' đã có lịch dạy lớp '{$conflictingClassForTeacherCode} - {$conflictingClassForTeacherName}' vào {$thuName} và ca này.";
                    continue;
                }

                // 4) Kiểm tra lớp học đã có lịch này chưa (thu_id, ca_id, kynang_id)
                $existingClassSchedule = ThoiKhoaBieu::where('lophoc_id', $lophocId)
                    ->where('thu_id', $thuId)
                    ->where('cahoc_id', $caId)
                    ->where('kynang_id', $kynangId)
                    ->first();

                if ($existingClassSchedule) {
                    $thuName = Thu::find($thuId)->tenthu ?? 'N/A';
                    $kynangName = KyNang::find($kynangId)->ten ?? 'N/A';
                    $errors[] = "Lớp học đã có lịch với kỹ năng '{$kynangName}' vào {$thuName} và ca này.";
                    continue;
                }

                // Nếu không có lỗi trùng lịch, tiến hành tạo bản ghi mới
                try {
                    ThoiKhoaBieu::create([
                        'lophoc_id' => $lophocId,
                        'giaovien_id' => $giaovienId,
                        'phonghoc_id' => $phongId,
                        'thu_id' => $thuId,    // Lưu một giá trị số nguyên
                        'cahoc_id' => $caId,
                        'kynang_id' => $kynangId, // Lưu một giá trị số nguyên
                        'ghi_chu' => $ghiChu,
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $thuName = Thu::find($thuId)->tenthu ?? 'N/A';
                    $kynangName = KyNang::find($kynangId)->ten ?? 'N/A';
                    $errors[] = "Không thể lưu lịch cho Thứ {$thuName}, Kỹ năng {$kynangName}: " . $e->getMessage();
                }
            }
        }

        if (empty($errors) && $successCount > 0) {
            return response()->json(['message' => "Đã lưu thành công {$successCount} lịch học!", 'type' => 'success']);
        } elseif (!empty($errors) && $successCount > 0) {
            return response()->json(['message' => "Đã lưu thành công {$successCount} lịch học, nhưng có lỗi: " . implode('<br>', array_unique($errors)), 'type' => 'warning'], 200);
        } else {
            return response()->json(['message' => 'Có lỗi xảy ra, không có lịch học nào được lưu: ' . implode('<br>', array_unique($errors)), 'type' => 'error'], 409);
        }
    }
}
