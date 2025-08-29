<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DonGia;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\NamHoc;
use App\Models\PhieuThu;
use App\Models\ThoiKhoaBieu;
use App\Models\Thu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dangkycontroller extends Controller
{

    public function checkThoiKhoaBieu($lopHocId)
    {
        $hasSchedule = ThoiKhoaBieu::where('lophoc_id', $lopHocId)->exists();
        return response()->json(['hasSchedule' => $hasSchedule]);
    }

    public function create(Request $request) // Thêm Request để đọc query parameter
    {
        $khoahocs = KhoaHoc::orderBy('ten', 'asc')->get();
        $hocviens = HocVien::orderBy('ten', 'asc')->get();
        $lophocs = LopHoc::with(['khoahoc', 'trinhdo'])->orderBy('tenlophoc', 'asc')->get();

        // Lấy hocvien_id từ query parameter nếu có
        $initialHocVienId = $request->query('hocvien_id');
        $initialSelectedStudent = null;

        if ($initialHocVienId) {
            $initialSelectedStudent = HocVien::find($initialHocVienId);
        }

        return view('staff.dangky.index', compact('khoahocs', 'hocviens', 'lophocs', 'initialSelectedStudent'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'hocvien_id' => 'required|exists:hocvien,id',
            'lophoc_id' => 'required|exists:lophoc,id',
        ], [
            'hocvien_id.required' => 'Vui lòng chọn học viên.',
            'hocvien_id.exists' => 'Học viên không tồn tại.',
            'lophoc_id.required' => 'Vui lòng chọn lớp học.',
            'lophoc_id.exists' => 'Lớp học không tồn tại.',
        ]);

        $hocvienId = $request->input('hocvien_id');
        $lophocId = $request->input('lophoc_id');

        try {
            $targetLopHoc = LopHoc::with('thoiKhoaBieus.thu', 'thoiKhoaBieus.cahoc')
                ->findOrFail($lophocId);

            // Kiểm tra học viên đã đăng ký lớp này chưa
            $existingRegistration = DB::table('lophoc_hocvien')
                ->where('hocvien_id', $hocvienId)
                ->where('lophoc_id', $lophocId)
                ->exists();

            if ($existingRegistration) {
                return back()->with('error', 'Học viên này đã được đăng ký vào lớp học này rồi.');
            }

            // Kiểm tra lớp đã đầy chưa
            if ($targetLopHoc->soluonghocvienhientai >= $targetLopHoc->soluonghocvientoida) {
                return back()->with('error', 'Lớp học đã đạt sức chứa tối đa.');
            }

            $hocvien = HocVien::with('lopHocs.thoiKhoaBieus.thu', 'lopHocs.thoiKhoaBieus.cahoc')
                ->find($hocvienId);

            if (!$hocvien) {
                return back()->with('error', 'Không tìm thấy thông tin học viên.');
            }

            // Kiểm tra trùng lịch
            $conflict = $this->checkStudentScheduleConflicts($targetLopHoc, $hocvien);
            if ($conflict) {
                return back()->with('error', $conflict);
            }


            // Đăng ký học viên vào lớp
            DB::table('lophoc_hocvien')->insert([
                'hocvien_id' => $hocvienId,
                'lophoc_id' => $lophocId,
                'ngaydangky' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $targetLopHoc->increment('soluonghocvienhientai');

            return redirect()->route('staff.registrations.create', ['hocvien_id' => $hocvienId])
                ->with('success', 'Đăng ký học viên vào lớp học thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi đăng ký: ' . $e->getMessage());
        }
    }

    private function checkStudentScheduleConflicts(LopHoc $newLopHoc, HocVien $hocvien): ?string
    {
        // Load quan hệ cần thiết (qua lopHocs)
        $newLopHoc->load('thoiKhoaBieus.thu', 'thoiKhoaBieus.cahoc');
        $hocvien->load('lopHocs.thoiKhoaBieus.thu', 'lopHocs.thoiKhoaBieus.cahoc');

        $newStart = Carbon::parse($newLopHoc->ngaybatdau);
        $newEnd   = Carbon::parse($newLopHoc->ngayketthuc);

        foreach ($hocvien->lopHocs as $existingLop) {
            // bỏ qua nếu cùng lớp
            if ($existingLop->id == $newLopHoc->id) continue;

            $existStart = Carbon::parse($existingLop->ngaybatdau);
            $existEnd   = Carbon::parse($existingLop->ngayketthuc);

            // nếu khoảng thời gian không giao nhau thì bỏ qua
            if (!($newStart->lte($existEnd) && $newEnd->gte($existStart))) {
                continue;
            }

            // so sánh từng buổi trong thời khóa biểu
            foreach ($newLopHoc->thoiKhoaBieus as $newItem) {
                foreach ($existingLop->thoiKhoaBieus as $existItem) {
                    if (
                        $newItem->thu_id && $existItem->thu_id &&
                        $newItem->cahoc_id && $existItem->cahoc_id &&
                        $newItem->thu_id == $existItem->thu_id &&
                        $newItem->cahoc_id == $existItem->cahoc_id
                    ) {
                        // tạo message rõ ràng
                        $thuName = $newItem->thu->tenthu ?? $newItem->thu_id;
                        $caName  = $newItem->cahoc->tenca ?? $newItem->cahoc_id;
                        return "Không thể đăng kí học viên này vào lớp học, lí do trùng lịch với lớp '{$existingLop->tenlophoc}' — Thứ {$thuName}, Ca {$caName}";
                    }
                }
            }
        }

        return null; // không trùng
    }

    /**
     * AJAX: Tìm kiếm học viên theo tên hoặc mã.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchStudents(Request $request)
    {
        $keyword = $request->input('query');
        $students = HocVien::where('ten', 'like', '%' . $keyword . '%')
            ->orWhere('mahocvien', 'like', '%' . $keyword . '%')
            ->limit(30)
            ->get(['id', 'mahocvien', 'ten', 'email_hv', 'sdt', 'diachi']);

        return response()->json($students);
    }

    /**
     * AJAX: Tìm kiếm lớp học theo tên hoặc mã, có thể lọc theo khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchClasses(Request $request)
    {
        $keyword = $request->input('query');
        $khoahocId = $request->input('khoahoc_id');

        $query = LopHoc::with(['khoaHoc', 'trinhdo'])
            ->where(function ($q) use ($keyword) {
                $q->where('tenlophoc', 'like', '%' . $keyword . '%')
                    ->orWhere('malophoc', 'like', '%' . $keyword . '%');
            });

        if ($khoahocId) {
            $query->where('khoahoc_id', $khoahocId);
        }

        $classes = $query->limit(10)->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'malophoc' => $class->malophoc,
                'tenlophoc' => $class->tenlophoc,
                'soluonghocvientoida' => $class->soluonghocvientoida,
                'khoahoc_ten' => $class->khoaHoc->ma ?? 'N/A',
                'trinhdo_ten' => $class->trinhdo->ten ?? 'N/A',
                'khoahoc_id' => $class->khoahoc_id,
            ];
        });

        return response()->json($formattedClasses);
    }

    // public function getStudentHistory(Request $request)
    // {
    //     $request->validate([
    //         'hocvien_id' => 'required|exists:hocvien,id',
    //     ]);

    //     $hocvienId = $request->input('hocvien_id');

    //     // Lấy lịch sử đăng ký của học viên
    //     $registrations = DB::table('lophoc_hocvien')
    //         ->where('lophoc_hocvien.hocvien_id', $hocvienId)
    //         ->join('lophoc', 'lophoc_hocvien.lophoc_id', '=', 'lophoc.id')
    //         ->leftJoin('khoahoc', 'lophoc.khoahoc_id', '=', 'khoahoc.id')
    //         ->leftJoin('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
    //         // JOIN dongia để lấy học phí, dựa vào trinhdo_id và namhoc_id của lớp học
    //         ->leftJoin('dongia', function ($join) {
    //             $join->on('trinhdo.id', '=', 'dongia.trinhdo_id')
    //                 ->on('lophoc.namhoc_id', '=', 'dongia.namhoc_id'); // Lấy namhoc_id từ bảng lophoc
    //         })
    //         // LEFT JOIN phieu_thu để lấy thông tin thanh toán
    //         ->leftJoin('phieu_thu', function ($join) {
    //             $join->on('lophoc_hocvien.hocvien_id', '=', 'phieu_thu.hocvien_id')
    //                 ->on('lophoc_hocvien.lophoc_id', '=', 'phieu_thu.lophoc_id');
    //         })
    //         ->select(
    //             'lophoc_hocvien.lophoc_id',
    //             'lophoc_hocvien.ngaydangky', // Đảm bảo tên cột này đúng trong bảng pivot
    //             'lophoc.malophoc as lophoc_ma',
    //             'lophoc.tenlophoc as lophoc_ten',
    //             'khoahoc.ten as khoahoc_ma',
    //             'trinhdo.ten as trinhdo_ten',
    //             'dongia.hocphi', // Học phí từ bảng dongia (tổng học phí của lớp)
    //             DB::raw('SUM(phieuthu.sotien) as total_paid') // Tổng số tiền đã thu cho lần đăng ký này
    //         )
    //         // Group by tất cả các cột không phải là hàm aggregate
    //         ->groupBy(
    //             'lophoc_hocvien.lophoc_id',
    //             'lophoc_hocvien.ngaydangky',
    //             'lophoc.malophoc',
    //             'lophoc.tenlophoc',
    //             'khoahoc.ten',
    //             'trinhdo.ten',
    //             'dongia.hocphi'
    //         )
    //         ->orderBy('lophoc_hocvien.ngaydangky', 'desc')
    //         ->get();

    //     // Xử lý và định dạng dữ liệu, đặc biệt là payment_status
    //     $formattedRegistrations = $registrations->map(function ($reg) {
    //         $paymentStatus = 'Chưa thanh toán';
    //         if (!is_null($reg->hocphi) && $reg->hocphi > 0) {
    //             if ($reg->total_paid >= $reg->hocphi) {
    //                 $paymentStatus = 'Đã thanh toán';
    //             } elseif ($reg->total_paid > 0 && $reg->total_paid < $reg->hocphi) {
    //                 $paymentStatus = 'Một phần';
    //             }
    //         } else {
    //             $paymentStatus = 'Chưa xác định học phí'; // Trường hợp không tìm thấy đơn giá cho trình độ/năm học này
    //         }

    //         return [
    //             'lophoc_id' => $reg->lophoc_id,
    //             'ngaydangky' => $reg->ngay_dang_ky,
    //             'payment_status' => $paymentStatus,
    //             'lophoc_ma' => $reg->lophoc_ma,
    //             'lophoc_ten' => $reg->lophoc_ten,
    //             'khoahoc_ten' => $reg->khoahoc_ma,
    //             'trinhdo_ten' => $reg->trinhdo_ten,
    //             'hocphi' => $reg->hocphi,
    //             // 'total_paid' => $reg->total_paid, // Có thể bỏ nếu không cần hiển thị
    //         ];
    //     });

    //     return response()->json($formattedRegistrations);
    // }



    public function getStudentHistory(Request $request)
    {
        // 1. Xác thực yêu cầu
        $request->validate([
            'hocvien_id' => 'required|exists:hocvien,id',
        ]);

        $hocvienId = $request->input('hocvien_id');

        // 2. Lấy tất cả các đăng ký của học viên cùng với thông tin lớp học, khóa học, trình độ
        // Chúng ta cần trinhdo_id và namhoc_id của lớp học để tìm đơn giá.
        // namhoc_id có thể nằm trực tiếp trên LopHoc hoặc thông qua KhoaHoc.
        $registrations = DB::table('lophoc_hocvien as lhhv')
            ->where('lhhv.hocvien_id', $hocvienId)
            ->join('lophoc as lh', 'lhhv.lophoc_id', '=', 'lh.id')
            ->leftJoin('khoahoc as kh', 'lh.khoahoc_id', '=', 'kh.id')
            ->leftJoin('trinhdo as td', 'lh.trinhdo_id', '=', 'td.id')
            ->select(
                'lhhv.lophoc_id',
                'lhhv.ngaydangky',
                'lh.malophoc as lophoc_ma',
                'lh.tenlophoc as lophoc_ten',
                'kh.ten as khoahoc_ten',
                'td.ten as trinhdo_ten',
                'lh.trinhdo_id', // Cần trinhdo_id để tìm dongia
                // Lấy namhoc_id cho lớp học này. Ưu tiên lh.namhoc_id, sau đó là kh.namhoc_id.
                DB::raw('COALESCE(lh.namhoc_id, kh.namhoc_id) as class_namhoc_id')
            )
            ->orderBy('lhhv.ngaydangky', 'desc')
            ->get();

        // 3. Xử lý từng bản ghi để tính toán học phí và trạng thái thanh toán
        $processedRegistrations = $registrations->map(function ($reg) use ($hocvienId) {
            $totalTuition = 0; // Học phí lý thuyết của lớp
            $paidAmount = 0;   // Tổng số tiền đã đóng cho lớp này

            // Lấy học phí lý thuyết từ bảng dongia dựa trên trinhdo_id và namhoc_id của lớp
            if ($reg->trinhdo_id && $reg->class_namhoc_id) {
                $dongia = DonGia::where('trinhdo_id', $reg->trinhdo_id)
                    ->where('namhoc_id', $reg->class_namhoc_id)
                    ->first();
                if ($dongia) {
                    $totalTuition = round($dongia->hocphi, 2);
                }
            }

            // Tính tổng số tiền đã đóng cho lớp học và học viên này từ bảng phieuthu
            $paidAmount = PhieuThu::where('hocvien_id', $hocvienId)
                ->where('lophoc_id', $reg->lophoc_id)
                ->where('trangthai', 'da_thanh_toan') // CHỈ TÍNH CÁC PHIẾU ĐÃ THANH TOÁN
                ->sum('sotien');
            $paidAmount = round($paidAmount, 2);

            // Gán các giá trị học phí và số tiền đã đóng vào đối tượng trả về
            $reg->hocphi = $totalTuition; // Tên biến để frontend dễ sử dụng
            $reg->total_paid_amount = $paidAmount;

            // Xác định trạng thái thanh toán
            if ($totalTuition > 0 && $paidAmount >= $totalTuition) {
                $reg->payment_status = 'Đã thanh toán';
            } elseif ($paidAmount > 0 && $paidAmount < $totalTuition) {
                $reg->payment_status = 'Một phần';
            } else {
                $reg->payment_status = 'Chưa thanh toán';
            }

            // Xóa các trường tạm thời không cần thiết gửi về frontend
            unset($reg->trinhdo_id);
            unset($reg->class_namhoc_id);

            return $reg;
        });

        return response()->json($processedRegistrations);
    }
    public function getTuitionInfo($classId, $studentId)
    {
        // 1️⃣ Tải lớp học cùng quan hệ khoahoc & trinhdo
        $class = LopHoc::with(['khoahoc', 'trinhdo'])->find($classId);
        $student = HocVien::find($studentId);

        if (!$class || !$student) {
            return response()->json(['message' => 'Lớp học hoặc học viên không tìm thấy.'], 404);
        }

        // 2️⃣ Lấy trình độ từ lớp học
        $trinhdoId = $class->trinhdo_id ?? optional($class->trinhdo)->id;
        if (!$trinhdoId) {
            return response()->json(['message' => 'Không tìm thấy trình độ của lớp học.'], 422);
        }

        // 3️⃣ Ưu tiên lấy namhoc_id từ LopHoc, fallback sang KhoaHoc (nếu LopHoc có cột namhoc_id)
        $namhocId = $class->namhoc_id ?? optional($class->khoahoc)->namhoc_id;

        if (!$namhocId) {
            return response()->json(['message' => 'Không tìm thấy năm học. Vui lòng kiểm tra dữ liệu.'], 422);
        }

        // 4️⃣ Tìm đơn giá đúng trình độ + năm học
        $dongia = DonGia::where('trinhdo_id', $trinhdoId)
            ->where('namhoc_id', $namhocId)
            ->first();

        if (!$dongia) {
            return response()->json(['message' => 'Không tìm thấy đơn giá cho trình độ & năm học này.'], 404);
        }

        $totalTuition = round($dongia->hocphi, 2);

        // 5️⃣ Tính tổng số tiền đã đóng (chỉ những phiếu thu có trạng thái 'da_thanh_toan')
        $paidAmount = PhieuThu::where('hocvien_id', $studentId)
            ->where('lophoc_id', $classId)
            ->where('trangthai', 'da_thanh_toan') // Chỉ tính các phiếu đã thanh toán
            ->sum('sotien');
        $paidAmount = round($paidAmount, 2);

        // 6️⃣ Tính số tiền còn lại
        $remainingAmount = max(0, round($totalTuition - $paidAmount, 2));

        // 7️⃣ Xác định trạng thái thanh toán
        $paymentStatus = 'chua_thanh_toan';
        if ($paidAmount >= $totalTuition) {
            $paymentStatus = 'da_thanh_toan';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'Một phần';
        }

        return response()->json([
            'total_tuition'    => $totalTuition,
            'paid_amount'      => $paidAmount,
            'remaining_amount' => $remainingAmount,
            'payment_status'   => $paymentStatus, // Thêm trường trạng thái thanh toán
            'message'          => 'Thông tin học phí đã được tải.'
        ]);
    }

    /**
     * Xử lý việc thu học phí và lưu vào bảng phieu_thu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'hocvien_id' => 'required|exists:hocvien,id',
            'lophoc_id' => 'required|exists:lophoc,id',
            'sotien' => 'required|numeric|min:1',
            'phuongthuc' => 'required|string|in:tien_mat,chuyen_khoan',
            // 'ngaythanhtoan' => 'required|date',
            'ghichu' => 'nullable|string|max:500',
        ]);

        $studentId = $request->input('hocvien_id');
        $classId = $request->input('lophoc_id');
        $amountToPay = $request->input('sotien');
        $paymentMethod = $request->input('phuongthuc');
        // $paymentDate = $request->input('ngaythanhtoan');
        $note = $request->input('ghichu');

        // 👉 Load lớp + khóa học + trình độ + năm học
        $class = LopHoc::with(['khoahoc', 'trinhdo'])->find($classId);
        if (!$class) {
            return response()->json(['message' => 'Lớp học không tìm thấy.'], 404);
        }

        // Lấy trình độ & năm học từ quan hệ đúng
        $trinhdoId = $class->trinhdo_id;
        $namhocId = $class->namhoc_id; // Lấy namhoc_id trực tiếp từ LopHoc

        if (!$trinhdoId || !$namhocId) {
            return response()->json(['message' => 'Thiếu trình độ hoặc năm học của lớp.'], 422);
        }

        // Tìm đơn giá CHUẨN cho trình độ + năm học
        $dongia = DonGia::where('trinhdo_id', $trinhdoId)
            ->where('namhoc_id', $namhocId)
            ->first();

        if (!$dongia) {
            return response()->json(['message' => 'Không tìm thấy đơn giá học phí.'], 404);
        }

        $totalTuition = round($dongia->hocphi, 2);

        // Tính số tiền đã đóng trước đó (chỉ những phiếu thu có trạng thái 'da_thanh_toan')
        $paidAmountBefore = PhieuThu::where('hocvien_id', $studentId)
            ->where('lophoc_id', $classId)
            ->where('trangthai', 'da_thanh_toan')
            ->sum('sotien');

        $remainingBefore = $totalTuition - $paidAmountBefore;

        $epsilon = 0.01; // Sai số nhỏ để xử lý số thập phân

        if ($amountToPay > $remainingBefore + $epsilon) {
            return response()->json(['message' => 'Số tiền đóng vượt quá số tiền còn lại.'], 400);
        }
        $user = auth()->user();
        $nhanvienId = $user->nhanvien ? $user->nhanvien->id : null;
        $userId = auth()->id(); // hoặc auth('web')->id();

        if (!$nhanvienId) {
            return response()->json(['message' => 'Người dùng chưa được liên kết với nhân viên.'], 400);
        }
        DB::beginTransaction();
        try {
            // Nếu số tiền này đủ thì trạng thái là 'da_thanh_toan', nếu chưa thì 'chua_du'
            $status = ($amountToPay >= $remainingBefore - $epsilon) ? 'da_thanh_toan' : 'chua_du';

            $phieuThu = PhieuThu::create([
                'hocvien_id' => $studentId,
                'lophoc_id' => $classId,
                'sotien' => $amountToPay,
                'phuongthuc' => $paymentMethod,
                'ngaythanhtoan' => now(),
                'ghichu' => $note,
                'trangthai' => $status,
                'nhanvien_id' => $nhanvienId,

                // Bỏ comment nếu bạn có cột này và muốn lưu ID nhân viên
            ]);

            DB::commit();

            // Tính lại sau khi thu
            $paidAmountAfter = PhieuThu::where('hocvien_id', $studentId)
                ->where('lophoc_id', $classId)
                ->where('trangthai', 'da_thanh_toan')
                ->sum('sotien');

            $remainingAfter = $totalTuition - $paidAmountAfter;

            // Trạng thái tổng thể
            $newStatus = 'Chưa xác định';
            $newBadge = 'badge bg-secondary';

            if ($totalTuition <= $epsilon) {
                $newStatus = 'Chưa có học phí';
                $newBadge = 'badge bg-info';
            } elseif ($remainingAfter <= $epsilon) {
                $newStatus = 'Đã đóng đủ';
                $newBadge = 'badge bg-success';
            } elseif ($paidAmountAfter > $epsilon) {
                $newStatus = 'Còn nợ ' . number_format($remainingAfter, 0, ',', '.') . ' VNĐ';
                $newBadge = 'badge bg-warning text-dark';
            } else {
                $newStatus = 'Chưa đóng';
                $newBadge = 'badge bg-danger';
            }

            return response()->json([
                'message' => 'Thu học phí thành công!',
                'phieu_thu_id' => $phieuThu->id,
                'updated_tuition_info' => [
                    'student_id' => $studentId,
                    'total_tuition' => $totalTuition,
                    'paid_amount' => round($paidAmountAfter, 2),
                    'remaining_amount' => max(0, round($remainingAfter, 2)),
                    'hocphi_status' => $newStatus,
                    'hocphi_badge_class' => $newBadge,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Có lỗi khi xử lý thanh toán: ' . $e->getMessage()], 500);
        }
    }
}
