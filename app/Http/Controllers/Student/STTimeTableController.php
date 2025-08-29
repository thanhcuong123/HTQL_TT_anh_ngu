<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class STTimeTableController extends Controller
{
    /**
     * Hiển thị trang thời khóa biểu dạng lịch cho học viên.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // Lấy thông tin người dùng đang đăng nhập
        $user = Auth::user();

        // Tìm HocVien liên kết với User và eager load lophocs trên HocVien
        // Điều này giúp tránh lỗi "Undefined method 'load'" và đảm bảo mối quan hệ được tải
        $hocvien = \App\Models\HocVien::where('user_id', $user->id)
            ->with('lophocs') // Eager load mối quan hệ 'lophocs' của học viên
            ->first();

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // Không cần lấy dữ liệu lịch ở đây, FullCalendar sẽ gọi API để lấy
        return view('student.timetable.index', compact('hocvien'));
    }

    /**
     * Cung cấp dữ liệu sự kiện lịch cho FullCalendar (API endpoint).
     * FullCalendar sẽ gửi các tham số 'start' và 'end' để giới hạn phạm vi ngày.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarEvents(Request $request)
    {
        try {
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if (!Auth::check()) {
                Log::warning('Unauthorized access to student calendar events API.');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Lấy thông tin học viên của người dùng đang đăng nhập
            $user = Auth::user();
            $hocvien = $user->hocvien; // Lấy mối quan hệ hocvien từ đối tượng User

            if (!$hocvien) {
                Log::warning('Authenticated user has no associated HocVien profile for calendar events API.');
                return response()->json(['error' => 'Tài khoản của bạn không liên kết với hồ sơ học viên.'], 403);
            }

            // Phân tích ngày bắt đầu và kết thúc từ request của FullCalendar
            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'));

            Log::debug("FullCalendar API Request for student ID {$hocvien->id}: Start={$start->toDateString()}, End={$end->toDateString()}");

            $events = []; // Mảng để chứa các sự kiện sẽ trả về cho FullCalendar

            // Lấy danh sách ID các lớp học mà học viên này đang tham gia
            $enrolledClassIds = $hocvien->lophocs->pluck('id')->toArray();
            Log::debug('Enrolled Class IDs for student: ' . implode(', ', $enrolledClassIds));


            if (empty($enrolledClassIds)) {
                Log::info("Học viên ID {$hocvien->id} không có lớp học nào được đăng ký. Trả về mảng rỗng.");
                return response()->json([]); // Trả về mảng rỗng nếu học viên không có lớp nào
            }

            // Lấy ánh xạ giữa ID của Thứ và số thứ tự ngày trong tuần của Carbon từ database
            // Dựa trên 'thu.sql', cột 'thutu' đã tồn tại và có dữ liệu chính xác (0=CN, 1=T2,...)
            $thuMap = Thu::pluck('thutu', 'id')->toArray();
            Log::debug('Thu Map: ' . json_encode($thuMap));

            // Lấy tất cả các thời khóa biểu cho các lớp học viên đang tham gia
            $thoikhoabieuItems = ThoiKhoaBieu::with([
                'lophoc',
                'giaovien',
                'phonghoc',
                'thu',
                'cahoc',
                'kynang'
            ])
                ->whereIn('lophoc_id', $enrolledClassIds) // Lọc theo lớp của học viên
                ->whereHas('lophoc', function ($query) use ($start, $end) {
                    // Lọc các lớp học có ngày bắt đầu trước hoặc bằng ngày kết thúc của khoảng thời gian
                    // và ngày kết thúc sau hoặc bằng ngày bắt đầu của khoảng thời gian
                    $query->where('ngaybatdau', '<=', $end)
                        ->where('ngayketthuc', '>=', $start)
                        ->whereIn('trangthai', ['dang_hoat_dong', 'sap_khai_giang']); // Chỉ lấy lớp đang hoạt động/sắp khai giảng
                })
                ->get();

            Log::debug('Number of ThoiKhoaBieu items fetched: ' . $thoikhoabieuItems->count());
            // Nếu bạn muốn xem dữ liệu thô từ DB, uncomment dòng dưới đây (chỉ khi debug)
            // dd($thoikhoabieuItems->toArray());


            // Duyệt qua từng mục thời khóa biểu để tạo sự kiện cho FullCalendar
            foreach ($thoikhoabieuItems as $item) {
                // Kiểm tra xem các mối quan hệ có tồn tại không trước khi truy cập
                if (!$item->lophoc || !$item->giaovien || !$item->phonghoc || !$item->thu || !$item->cahoc || !$item->kynang) {
                    Log::warning("Mục thời khóa biểu ID " . ($item->id ?? 'N/A') . " thiếu dữ liệu mối quan hệ. Bỏ qua.");
                    continue;
                }

                $lophoc = $item->lophoc;
                $giaovien = $item->giaovien;
                $phonghoc = $item->phonghoc;
                $thu = $item->thu;
                $cahoc = $item->cahoc;
                $kynang = $item->kynang;

                // Lấy số thứ tự ngày trong tuần của Carbon từ ánh xạ ID của Thứ
                // Carbon::dayOfWeek: 0 = Chủ Nhật, 1 = Thứ Hai, ..., 6 = Thứ Bảy
                $targetDayOfWeek = $thuMap[$thu->id] ?? null;

                if (is_null($targetDayOfWeek)) {
                    Log::warning("Không tìm thấy ánh xạ 'thutu' cho Thu ID: {$thu->id} (Tên: {$thu->tenthu}). Bỏ qua.");
                    continue;
                }

                // Xác định khoảng thời gian hiệu lực của lịch học này trong phạm vi yêu cầu của FullCalendar
                // Lấy ngày bắt đầu thực tế của lớp học, không sớm hơn ngày bắt đầu của FullCalendar
                $actualLopHocStartDate = Carbon::parse($lophoc->ngaybatdau)->max($start);
                // Lấy ngày kết thúc thực tế của lớp học, không muộn hơn ngày kết thúc của FullCalendar
                $actualLopHocEndDate = Carbon::parse($lophoc->ngayketthuc)->min($end);

                // Lặp qua từng ngày trong khoảng thời gian hiệu lực
                for ($date = clone $actualLopHocStartDate; $date->lte($actualLopHocEndDate); $date->addDay()) {
                    // Nếu ngày hiện tại trong vòng lặp trùng với thứ của lịch học
                    if ($date->dayOfWeek == $targetDayOfWeek) {
                        // Tạo đối tượng Carbon cho thời gian bắt đầu và kết thúc sự kiện
                        $eventStartDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $cahoc->thoigianbatdau);
                        $eventEndDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $cahoc->thoigianketthuc);

                        // Thêm sự kiện vào mảng events
                        $events[] = [
                            'id'    => $item->id,
                            'title' => $lophoc->malophoc . ' - ' . $lophoc->tenlophoc,
                            'start' => $eventStartDateTime->toIso8601String(),
                            'end'   => $eventEndDateTime->toIso8601String(),
                            'url'   => route('student.lophoc.show', $lophoc->id), // Đảm bảo route này khớp với routes/web.php của bạn
                            'extendedProps' => [
                                'classId'   => $lophoc->id,
                                'className' => $lophoc->tenlophoc,
                                'classCode' => $lophoc->malophoc,
                                'teacher'   => $giaovien->ten ?? 'N/A',
                                'room'      => $phonghoc->tenphong ?? 'N/A',
                                'skill'     => $kynang->ten ?? 'N/A',
                                'caHoc'     => $cahoc->tenca,
                                'thu'       => $thu->tenthu,
                            ],
                            'classNames' => [
                                'event-default'
                            ],
                        ];
                    }
                }
            }

            Log::debug('Total events generated: ' . count($events));
            // Nếu bạn muốn xem mảng events cuối cùng, uncomment dòng dưới đây (chỉ khi debug)
            // dd($events);

            // Trả về dữ liệu sự kiện dưới dạng JSON
            return response()->json($events);
        } catch (\Exception $e) {
            Log::error("Lỗi khi lấy sự kiện lịch học viên: " . $e->getMessage() . " tại dòng " . $e->getLine() . " trong file " . $e->getFile());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi tải sự kiện lịch.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
