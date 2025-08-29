<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Import Log facade
class CalendarController extends Controller
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
                'kynang',
                'phonghoc.tang.nhahoc',
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
                $tang = $phonghoc->tang ?? null;
                $nhahoc = $tang ? $tang->nhahoc : null;
                $nhahocTen = $nhahoc ? $nhahoc->ma : 'N/A';


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
                            'url'   => route('lophoc.show', $lophoc->id), // Đảm bảo route 'lophoc.show' tồn tại
                            'extendedProps' => [
                                'classId' => $lophoc->id,
                                'className' => $lophoc->tenlophoc,
                                'classCode' => $lophoc->malophoc,
                                'teacher' => $giaovien->ten ?? 'N/A',
                                'room' => $phonghoc->tenphong ?? 'N/A',
                                'skill' => $kynang->ten ?? 'N/A',
                                'caHoc' => $cahoc->tenca,
                                'thu' => $thu->tenthu,
                                'nhaHoc' => $nhahocTen
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
    public function index()
    {
        return view('admin.calendar.index');
    }
}
