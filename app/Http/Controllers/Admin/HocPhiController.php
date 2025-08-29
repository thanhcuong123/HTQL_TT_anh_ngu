<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Models\HocVi; // Cái này có vẻ không dùng, có thể xóa
use App\Models\HocVien;
use App\Models\LopHoc;
use App\Models\PhieuThu; // <<<< THÊM DÒNG NÀY
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TuitionReminderMail;
use App\Models\DonGia;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HocPhiController extends Controller
{
    public function index()
    {
        $classes = LopHoc::all();
        $khoahocs = KhoaHoc::with('lopHocs.trinhDo')->get();

        return view('admin.hocphi.index', compact('classes', 'khoahocs'));
    }







    public function getStudentsByClass($classId)
    {
        // 1️⃣ Lấy lớp học + học viên + trình độ + TẤT CẢ đơn giá
        $class = LopHoc::with([
            'hocviens' => function ($query) {
                $query->with('user')->withPivot('ngaydangky');
            },
            'trinhdo.dongias',
            'khoahoc'
        ])->find($classId);

        if (!$class) {
            return response()->json(['message' => 'Không tìm thấy lớp học.'], 404);
        }

        $trinhdo = $class->trinhdo;

        if (!$trinhdo) {
            return response()->json(['message' => 'Không tìm thấy trình độ của lớp học.'], 422);
        }

        // 2️⃣ Xác định năm học
        $namhocId = $class->namhoc_id ?? optional($class->khoahoc)->namhoc_id;

        if (!$namhocId) {
            return response()->json(['message' => 'Không tìm thấy năm học. Vui lòng kiểm tra dữ liệu.'], 422);
        }

        // 3️⃣ Lấy đơn giá theo năm học
        $dongia = $trinhdo->dongias->where('namhoc_id', $namhocId)->first();

        $totalTuitionForClass = 0;
        if ($dongia) {
            $totalTuitionForClass = round($dongia->hocphi, 2);
        }

        // 4️⃣ Map thông tin học viên
        $studentsData = $class->hocviens->map(function ($hocvien) use ($classId, $totalTuitionForClass) {
            $paidAmount = PhieuThu::where('hocvien_id', $hocvien->id)
                ->where('lophoc_id', $classId)
                ->where('trangthai', 'da_thanh_toan')
                ->sum('sotien');

            $paidAmount = round($paidAmount, 2);
            $remainingAmount = max(0, round($totalTuitionForClass - $paidAmount, 2));

            // Trạng thái
            $tuitionStatus = 'Chưa xác định';
            $badgeClass = 'badge bg-secondary';

            if ($totalTuitionForClass == 0) {
                $tuitionStatus = 'Chưa có học phí lớp này';
                $badgeClass = 'badge bg-info';
            } elseif ($remainingAmount <= 0) {
                $tuitionStatus = 'Đã đóng đủ';
                $badgeClass = 'badge bg-success';
            } elseif ($paidAmount > 0 && $remainingAmount > 0) {
                $tuitionStatus = 'Còn nợ (' . number_format($remainingAmount, 0, ',', '.') . ' VNĐ)';
                $badgeClass = 'badge bg-warning text-dark';
            } else {
                $tuitionStatus = 'Chưa đóng';
                $badgeClass = 'badge bg-danger';
            }

            return [
                'id' => $hocvien->id,
                'mahocvien' => $hocvien->mahocvien,
                'ten' => $hocvien->ten,
                'sdt' => $hocvien->sdt ?? 'N/A',
                'ngaydangky' => $hocvien->pivot->ngaydangky
                    ? date('d/m/Y', strtotime($hocvien->pivot->ngaydangky))
                    : 'N/A',
                'hocphi_status' => $tuitionStatus,
                'hocphi_badge_class' => $badgeClass,
                'email' => $hocvien->user->email ?? 'N/A',
                'total_tuition' => $totalTuitionForClass,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
            ];
        });

        return response()->json(['students' => $studentsData]);
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

        // 3️⃣ Ưu tiên lấy namhoc_id từ LopHoc, fallback sang KhoaHoc
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

        // 5️⃣ Tính tổng số tiền đã đóng
        $paidAmount = PhieuThu::where('hocvien_id', $studentId)
            ->where('lophoc_id', $classId)
            ->where('trangthai', 'da_thanh_toan')
            ->sum('sotien');
        $paidAmount = round($paidAmount, 2);

        // 6️⃣ Tính số tiền còn lại
        $remainingAmount = max(0, round($totalTuition - $paidAmount, 2));

        return response()->json([
            'total_tuition'   => $totalTuition,
            'paid_amount'     => $paidAmount,
            'remaining_amount' => $remainingAmount,
            'message'         => 'Thông tin học phí đã được tải.'
        ]);
    }


    //     // Eager load relationships: class -> trinhdo -> dongia
    //     $class = LopHoc::with('trinhdo.dongia')->find($classId);
    //     $student = HocVien::find($studentId);

    //     if (!$class || !$student) {
    //         return response()->json(['message' => 'Lớp học hoặc học viên không tìm thấy.'], 404);
    //     }

    //     $totalTuition = 0;
    //     // Lấy tổng học phí từ bảng 'dongia' thông qua mối quan hệ 'trinhdo' của 'lophoc'
    //     if ($class->trinhdo && $class->trinhdo->dongia) {
    //         $totalTuition = $class->trinhdo->dongia->hocphi;
    //     }

    //     // Tính tổng số tiền đã đóng của học viên này cho lớp học này
    //     // Chỉ tổng hợp các phiếu thu có trạng thái 'da_thanh_toan'
    //     $paidAmount = PhieuThu::where('hocvien_id', $studentId)
    //         ->where('lophoc_id', $classId)
    //         ->where('trangthai', 'da_thanh_toan')
    //         ->sum('sotien');

    //     $remainingAmount = $totalTuition - $paidAmount;

    //     // Đảm bảo số tiền còn lại không bao giờ âm và làm tròn để tránh sai số dấu phẩy động
    //     $remainingAmount = max(0, round($remainingAmount, 2));
    //     $totalTuition = round($totalTuition, 2);
    //     $paidAmount = round($paidAmount, 2);


    //     return response()->json([
    //         'total_tuition' => $totalTuition,
    //         'paid_amount' => $paidAmount,
    //         'remaining_amount' => $remainingAmount,
    //         'message' => 'Thông tin học phí đã được tải.'
    //     ]);
    // }


    // public function processPayment(Request $request)
    // {
    //     $request->validate([
    //         'student_id' => 'required|exists:hocvien,id',
    //         'class_id' => 'required|exists:lophoc,id',
    //         'amount' => 'required|numeric|min:1',
    //         'payment_method' => 'required|string',
    //         'payment_date' => 'required|date',
    //         'note' => 'nullable|string|max:500',
    //     ]);

    //     $studentId = $request->input('student_id');
    //     $classId = $request->input('class_id');
    //     $amountToPay = $request->input('amount');
    //     $paymentMethod = $request->input('payment_method');
    //     $paymentDate = $request->input('payment_date');
    //     $note = $request->input('note');

    //     // Lấy thông tin lớp và trình độ/đơn giá
    //     $class = LopHoc::with('trinhdo.dongia')->find($classId);
    //     if (!$class) {
    //         return response()->json(['message' => 'Lớp học không tìm thấy.'], 404);
    //     }

    //     $totalTuition = 0;
    //     if ($class->trinhdo && $class->trinhdo->dongia) {
    //         $totalTuition = $class->trinhdo->dongia->hocphi;
    //     }

    //     // Tính số tiền đã đóng HIỆN TẠI trước khi thêm phiếu mới
    //     $paidAmountBeforeNewPayment = PhieuThu::where('hocvien_id', $studentId)
    //         ->where('lophoc_id', $classId)
    //         ->where('trangthai', 'da_thanh_toan')
    //         ->sum('sotien'); // <== Đảm bảo tên cột là 'sotien'

    //     $remainingAmountBeforeNewPayment = $totalTuition - $paidAmountBeforeNewPayment;

    //     if ($amountToPay > $remainingAmountBeforeNewPayment) {
    //         return response()->json(['message' => 'Số tiền đóng vượt quá số tiền còn lại.'], 400);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         // Tạo một phiếu thu mới
    //         $phieuThu = PhieuThu::create([
    //             'hocvien_id' => $studentId,
    //             'lophoc_id' => $classId,
    //             'sotien' => $amountToPay, // <== ĐẢM BẢO TÊN CỘT LÀ 'sotien' ở đây và trong DB
    //             'phuongthuc' => $paymentMethod,
    //             'ngaythanhtoan' => $paymentDate,
    //             'ghichu' => $note,
    //             'trangthai' => ($amountToPay == $remainingAmountBeforeNewPayment) ? 'da_thanh_toan' : 'cho_thanh_toan',
    //         ]);

    //         DB::commit();

    //         // === TÍNH TOÁN LẠI HỌC PHÍ SAU KHI CÓ PHIẾU THU MỚI ===
    //         $paidAmountAfterNewPayment = PhieuThu::where('hocvien_id', $studentId)
    //             ->where('lophoc_id', $classId)
    //             ->where('trangthai', 'da_thanh_toan')
    //             ->sum('sotien');

    //         $remainingAmountAfterNewPayment = $totalTuition - $paidAmountAfterNewPayment;

    //         // Xác định trạng thái mới để gửi về frontend
    //         $newTuitionStatus = 'Chưa xác định';
    //         $newBadgeClass = 'badge bg-secondary';

    //         if ($totalTuition == 0) {
    //             $newTuitionStatus = 'Chưa có học phí lớp';
    //             $newBadgeClass = 'badge bg-info';
    //         } elseif ($remainingAmountAfterNewPayment <= 0) {
    //             $newTuitionStatus = 'Đã đóng đủ';
    //             $newBadgeClass = 'badge bg-success';
    //         } elseif ($paidAmountAfterNewPayment > 0 && $remainingAmountAfterNewPayment > 0) {
    //             $newTuitionStatus = 'Còn nợ (' . number_format($remainingAmountAfterNewPayment, 0, ',', '.') . ' VNĐ)';
    //             $newBadgeClass = 'badge bg-warning text-dark';
    //         } else { // paidAmountAfterNewPayment == 0 && remainingAmountAfterNewPayment > 0
    //             $newTuitionStatus = 'Chưa đóng';
    //             $newBadgeClass = 'badge bg-danger';
    //         }

    //         return response()->json([
    //             'message' => 'Thu học phí thành công!',
    //             'phieu_thu_id' => $phieuThu->id,
    //             'updated_tuition_info' => [
    //                 'student_id' => $studentId,
    //                 'total_tuition' => $totalTuition,
    //                 'paid_amount' => $paidAmountAfterNewPayment,
    //                 'remaining_amount' => $remainingAmountAfterNewPayment,
    //                 'hocphi_status' => $newTuitionStatus,
    //                 'hocphi_badge_class' => $newBadgeClass,
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         // \Log::error('Lỗi khi xử lý thu học phí: ' . $e->getMessage(), ['exception' => $e]);
    //         return response()->json(['message' => 'Lỗi server khi xử lý thanh toán: ' . $e->getMessage()], 500);
    //     }
    // }

    public function processPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:hocvien,id',
            'class_id' => 'required|exists:lophoc,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        $studentId = $request->input('student_id');
        $classId = $request->input('class_id');
        $amountToPay = $request->input('amount');
        $paymentMethod = $request->input('payment_method');
        $paymentDate = $request->input('payment_date');
        $note = $request->input('note');

        // 👉 Load lớp + khóa học + trình độ + năm học
        $class = LopHoc::with(['khoahoc', 'trinhdo'])->find($classId);
        if (!$class) {
            return response()->json(['message' => 'Lớp học không tìm thấy.'], 404);
        }

        // Lấy trình độ & năm học từ quan hệ đúng
        $trinhdoId = $class->trinhdo_id;
        // $namhocId = optional($class->khoahoc)->namhoc_id;
        $namhocId = $class->namhoc_id;

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

        // Tính số tiền đã đóng trước đó
        $paidAmountBefore = PhieuThu::where('hocvien_id', $studentId)
            ->where('lophoc_id', $classId)
            ->where('trangthai', 'da_thanh_toan')
            ->sum('sotien');

        $remainingBefore = $totalTuition - $paidAmountBefore;

        $epsilon = 0.01;

        if ($amountToPay > $remainingBefore + $epsilon) {
            return response()->json(['message' => 'Số tiền đóng vượt quá số tiền còn lại.'], 400);
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
                'ngaythanhtoan' => $paymentDate,
                'ghichu' => $note,
                'trangthai' => $status,
                // 'nhanvien_id' => auth()->id(),
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

            // if ($totalTuition <= $epsilon) {
            //     $newStatus = 'Chưa có học phí';
            //     $newBadge = 'badge bg-info';
            // } elseif ($remainingAfter <= $epsilon) {
            //     $newStatus = 'Đã đóng đủ';
            //     $newBadge = 'badge bg-success';
            // } elseif ($paidAmountAfter > $epsilon) {
            //     $newStatus = 'Còn nợ';
            //     $newBadge = 'badge bg-warning text-dark';
            // } else {
            //     $newStatus = 'Chưa đóng';
            //     $newBadge = 'badge bg-danger';
            // }
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

    public function printReceipt($studentId, $classId)
    {
        // Tìm kiếm phiếu thu đã thanh toán gần nhất cho học viên và lớp này
        // Hoặc có thể tìm tất cả phiếu thu và tổng hợp
        $phieuThu = PhieuThu::where('hocvien_id', $studentId)
            ->where('lophoc_id', $classId)
            ->where('trangthai', 'da_thanh_toan')
            ->orderBy('created_at', 'desc') // Lấy phiếu mới nhất hoặc bạn có thể muốn tổng hợp
            ->first(); // Nếu bạn muốn in biên lai cho mỗi lần đóng, bạn cần truyền ID phiếu thu cụ thể

        if (!$phieuThu) {
            return back()->with('error', 'Không tìm thấy phiếu thu đã đóng đủ cho học viên này trong lớp học này.');
        }

        // Tải thông tin liên quan
        $phieuThu->load('hocvien.user', 'lophoc.trinhdo.dongias');

        // Logic để tạo biên lai
        // Cách 1: Trả về một view HTML có CSS thân thiện để in
        return view('admin.hocphi.printbienlai', compact('phieuThu'));

        // Cách 2: Sử dụng thư viện như DomPDF để tạo PDF (cần cài đặt: composer require barryvdh/laravel-dompdf)
        /*
       $pdf = Pdf::loadView('admin.hocphi.printbienlai', compact('phieuThu'));
return $pdf->download('bienlai_'.$phieuThu->id.'_'.now()->format('Ymd_His').'.pdf');

        */
    }



    public function sendTuitionReminders(Request $request)
    {
        // 1. Xác thực dữ liệu đầu vào
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:hocvien,id',
            'class_id' => 'required|exists:lophoc,id',
        ]);

        $studentIds = $request->input('student_ids');
        $classId = $request->input('class_id');
        $sentCount = 0;
        $failedAttempts = [];

        // 2. Lấy thông tin lớp học, tải Eager Loading chuỗi quan hệ: LopHoc -> TrinhDo -> DonGia
        $class = LopHoc::with(['trinhDo.donGias'])->find($classId);
        if (!$class) {
            return redirect()->back()->with('error', 'Lớp học không tồn tại.');
        }

        // Lấy học phí từ đơn giá
        $totalTuition = 0;
        if ($class->trinhDo && $class->trinhDo->donGia && isset($class->trinhDo->donGia->hocphi)) {
            $totalTuition = $class->trinhDo->donGia->hocphi;
        } else {
            Log::warning("Lớp học ID: {$classId} không có đơn giá hợp lệ.");
        }

        // 3. Lấy danh sách học viên KHÔNG cần load user
        $students = HocVien::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            $email = $student->email_hv; // ✅ Dùng trực tiếp từ cột email_hv

            // 4. Tính học phí còn nợ
            $paidAmount = $student->phieuthu()->where('lophoc_id', $classId)->sum('sotien');
            $remainingAmount = $totalTuition - $paidAmount;

            // 5. Gửi mail nếu có email
            try {
                if ($email) {
                    Mail::to($email)->send(new TuitionReminderMail($student, $class, $remainingAmount));
                    $sentCount++;
                } else {
                    Log::warning("Không gửi được cho học viên {$student->ten} (ID: {$student->id}) vì thiếu email.");
                    $failedAttempts[] = $student->ten . " (thiếu email)";
                }
            } catch (\Exception $e) {
                Log::error("Lỗi gửi email đến '{$email}' - Học viên '{$student->ten} (ID: {$student->id})': " . $e->getMessage());
                $failedAttempts[] = $student->ten . " (lỗi: " . $e->getMessage() . ")";
            }
        }

        // 6. Trả kết quả
        if ($sentCount > 0) {
            $message = "Đã gửi email nhắc học phí cho {$sentCount} học viên.";
            if (!empty($failedAttempts)) {
                $message .= " Không gửi được cho: " . implode('; ', array_unique($failedAttempts));
                return response()->json(['message' => $message, 'type' => 'warning']);
            }
            return response()->json(['message' => $message, 'type' => 'success']);
        } else {
            $message = "Không gửi được email nào.";
            if (!empty($failedAttempts)) {
                $message .= " Lý do: " . implode('; ', array_unique($failedAttempts));
            } else {
                $message .= " Vui lòng kiểm tra lại danh sách học viên.";
            }
            return response()->json(['message' => $message, 'type' => 'error'], 400);
        }
    }


    // Ví dụ trong Controller của bạn

}
