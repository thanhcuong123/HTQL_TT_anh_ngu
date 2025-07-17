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

use Illuminate\Http\Request;

class HocPhiController extends Controller
{
    public function index()
    {
        $classes = LopHoc::all();
        return view('admin.hocphi.index', compact('classes'));
    }

    // public function getStudentsByClass($classId)
    // {
    //     // 1. Tìm lớp học và eager load các học viên liên quan.
    //     // Quan trọng: Eager load cả trinhdo.dongia để lấy tổng học phí của lớp.
    //     // Eager load phieuthus của hocvien để tính tổng số tiền đã đóng.
    //     $class = LopHoc::with([
    //         'hocviens' => function ($query) {
    //             $query->with('user')->withPivot('ngaydangky');
    //         },
    //         'trinhdo.dongia' // <-- THÊM DÒNG NÀY để tải thông tin đơn giá của lớp
    //     ])->find($classId);

    //     if (!$class) {
    //         return response()->json(['message' => 'Lớp học không tìm thấy.'], 404);
    //     }

    //     // Lấy tổng học phí của lớp từ DonGia
    //     $totalTuitionForClass = 0;
    //     if ($class->trinhdo && $class->trinhdo->dongia) {
    //         $totalTuitionForClass = $class->trinhdo->dongia->hocphi;
    //     }

    //     // 2. Chuyển đổi dữ liệu học viên để bao gồm các trường cần thiết cho frontend
    //     $studentsData = $class->hocviens->map(function ($hocvien) use ($classId, $totalTuitionForClass) {

    //         // Tính tổng số tiền đã đóng của học viên này cho LỚP NÀY
    //         $paidAmount = PhieuThu::where('hocvien_id', $hocvien->id)
    //             ->where('lophoc_id', $classId)
    //             ->where('trangthai', 'da_thanh_toan')
    //             ->sum('sotien'); // <== Đảm bảo tên cột là 'sotien'

    //         $remainingAmount = $totalTuitionForClass - $paidAmount;

    //         $tuitionStatus = 'Chưa xác định';
    //         $badgeClass = 'badge bg-secondary';

    //         if ($totalTuitionForClass == 0) {
    //             $tuitionStatus = 'Chưa có học phí lớp này';
    //             $badgeClass = 'badge bg-danger';
    //         } elseif ($remainingAmount <= 0) {
    //             $tuitionStatus = 'Đã đóng đủ';
    //             $badgeClass = 'badge bg-success';
    //         } elseif ($paidAmount > 0 && $remainingAmount > 0) {
    //             // Hiển thị số tiền còn nợ
    //             $tuitionStatus = 'Còn nợ (' . number_format($remainingAmount, 0, ',', '.') . ' VNĐ)';
    //             $badgeClass = 'badge bg-warning text-dark';
    //         } else { // paidAmount == 0 && remainingAmount > 0 (hoặc totalTuition > 0)
    //             $tuitionStatus = 'Chưa đóng';
    //             $badgeClass = 'badge bg-danger';
    //         }

    //         return [
    //             'id' => $hocvien->id,
    //             'mahocvien' => $hocvien->mahocvien,
    //             'ten' => $hocvien->ten,
    //             'sdt' => $hocvien->sdt ?? 'N/A',
    //             'ngaydangky' => $hocvien->pivot->ngaydangky ? date('d/m/Y', strtotime($hocvien->pivot->ngaydangky)) : 'N/A',
    //             'hocphi_status' => $tuitionStatus,
    //             'hocphi_badge_class' => $badgeClass,
    //             'email' => $hocvien->user->email ?? 'N/A',
    //             // THÊM CÁC THÔNG TIN NÀY ĐỂ KHI CẦN HIỂN THỊ TRỰC TIẾP TRÊN BẢNG (nếu muốn)
    //             'total_tuition_amount' => $totalTuitionForClass,
    //             'paid_amount_student' => $paidAmount,
    //             'remaining_amount_student' => $remainingAmount,
    //         ];
    //     });

    //     // dd($studentsData); // Bỏ comment dòng này để kiểm tra dữ liệu trước khi gửi về frontend

    //     return response()->json(['students' => $studentsData]);
    // }



    public function getStudentsByClass($classId)
    {
        // 1. Tìm lớp học và eager load các học viên liên quan.
        // Quan trọng: Eager load cả trinhdo.dongia để lấy tổng học phí của lớp.
        $class = LopHoc::with([
            'hocviens' => function ($query) {
                // Eager load mối quan hệ 'user' của học viên để lấy email (nếu cần)
                // và lấy các trường từ bảng pivot 'ngaydangky'
                $query->with('user')->withPivot('ngaydangky');
            },
            'trinhdo.dongia' // Eager load thông tin đơn giá qua trình độ của lớp
        ])->find($classId);

        if (!$class) {
            return response()->json(['message' => 'Lớp học không tìm thấy.'], 404);
        }

        // Lấy tổng học phí của lớp từ DonGia
        $totalTuitionForClass = 0;
        if ($class->trinhdo && $class->trinhdo->dongia) {
            $totalTuitionForClass = $class->trinhdo->dongia->hocphi;
        }

        // 2. Chuyển đổi dữ liệu học viên để bao gồm các trường cần thiết cho frontend
        $studentsData = $class->hocviens->map(function ($hocvien) use ($classId, $totalTuitionForClass) {

            // Tính tổng số tiền đã đóng của học viên này cho LỚP NÀY
            // Chỉ tổng hợp các phiếu thu có trạng thái 'da_thanh_toan' (đã xác nhận đóng)
            $paidAmount = PhieuThu::where('hocvien_id', $hocvien->id)
                ->where('lophoc_id', $classId)
                ->where('trangthai', 'da_thanh_toan')
                ->sum('sotien'); // Đảm bảo tên cột là 'sotien' trong bảng phieuthu

            // Tính số tiền còn lại phải đóng
            $remainingAmount = $totalTuitionForClass - $paidAmount;

            // Xác định trạng thái học phí và class CSS tương ứng để hiển thị trên giao diện
            $tuitionStatus = 'Chưa xác định';
            $badgeClass = 'badge bg-secondary';

            if ($totalTuitionForClass == 0) {
                $tuitionStatus = 'Chưa có học phí lớp này';
                $badgeClass = 'badge bg-info'; // Hoặc bg-danger tùy ý bạn
            } elseif ($remainingAmount <= 0) {
                $tuitionStatus = 'Đã đóng đủ';
                $badgeClass = 'badge bg-success';
            } elseif ($paidAmount > 0 && $remainingAmount > 0) {
                // Hiển thị số tiền còn nợ kèm theo định dạng tiền tệ
                $tuitionStatus = 'Còn nợ (' . number_format($remainingAmount, 0, ',', '.') . ' VNĐ)';
                $badgeClass = 'badge bg-warning text-dark';
            } else { // paidAmount == 0 && remainingAmount > 0 (hoặc totalTuitionForClass > 0)
                $tuitionStatus = 'Chưa đóng';
                $badgeClass = 'badge bg-danger';
            }

            return [
                'id' => $hocvien->id,
                'mahocvien' => $hocvien->mahocvien,
                'ten' => $hocvien->ten,
                'sdt' => $hocvien->sdt ?? 'N/A',
                // Lấy ngày đăng ký từ bảng pivot (hocvien_lophoc)
                'ngaydangky' => $hocvien->pivot->ngaydangky ? date('d/m/Y', strtotime($hocvien->pivot->ngaydangky)) : 'N/A',
                'hocphi_status' => $tuitionStatus,
                'hocphi_badge_class' => $badgeClass,
                'email' => $hocvien->user->email ?? 'N/A',
                // Thêm các thông tin số tiền để frontend có thể sử dụng trực tiếp trong modal
                'total_tuition' => $totalTuitionForClass,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
            ];
        });

        // Trả về dữ liệu dưới dạng JSON
        return response()->json(['students' => $studentsData]);
    }
    // public function getTuitionInfo($classId, $studentId)
    // {
    //     // Eager load relationships: class -> trinhdo -> dongia
    //     $class = LopHoc::with('trinhdo.dongia')->find($classId); // <-- Sửa eager load thành 'dongia'
    //     $student = HocVien::find($studentId);

    //     if (!$class || !$student) {
    //         return response()->json(['message' => 'Lớp học hoặc học viên không tìm thấy.'], 404);
    //     }

    //     $totalTuition = 0;
    //     // Lấy tổng học phí từ bảng 'dongia' thông qua mối quan hệ 'trinhdo' của 'lophoc'
    //     if ($class->trinhdo && $class->trinhdo->dongia) {
    //         $totalTuition = $class->trinhdo->dongia->hocphi; // <-- Lấy từ cột 'hocphi' trong bảng 'dongia'
    //     }
    //     // Nếu bạn dùng `public function dongias()` (số nhiều) trong TrinhDo model,
    //     // thì bạn cần logic để chọn mức học phí phù hợp (ví dụ: mức mới nhất, mức mặc định)
    //     // Ví dụ:
    //     // if ($class->trinhdo && $class->trinhdo->dongias->isNotEmpty()) {
    //     //     $totalTuition = $class->trinhdo->dongias->first()->hocphi; // Hoặc sắp xếp và lấy cái bạn cần
    //     // }


    //     // Tính tổng số tiền đã đóng của học viên này cho lớp học này
    //     $paidAmount = PhieuThu::where('hocvien_id', $studentId)
    //         ->where('lophoc_id', $classId)
    //         ->where('trangthai', 'da_thanh_toan')
    //         ->sum('sotien'); // Đảm bảo cột trong bảng phieuthu là 'sotien'

    //     $remainingAmount = $totalTuition - $paidAmount;

    //     return response()->json([
    //         'total_tuition' => $totalTuition,
    //         'paid_amount' => $paidAmount,
    //         'remaining_amount' => $remainingAmount > 0 ? $remainingAmount : 0,
    //         'message' => 'Thông tin học phí đã được tải.'
    //     ]);
    // }


    /**
     * Xử lý việc thu học phí và tạo phiếu thu mới.
     */

    public function getTuitionInfo($classId, $studentId)
    {
        // Eager load relationships: class -> trinhdo -> dongia
        $class = LopHoc::with('trinhdo.dongia')->find($classId);
        $student = HocVien::find($studentId);

        if (!$class || !$student) {
            return response()->json(['message' => 'Lớp học hoặc học viên không tìm thấy.'], 404);
        }

        $totalTuition = 0;
        // Lấy tổng học phí từ bảng 'dongia' thông qua mối quan hệ 'trinhdo' của 'lophoc'
        if ($class->trinhdo && $class->trinhdo->dongia) {
            $totalTuition = $class->trinhdo->dongia->hocphi;
        }

        // Tính tổng số tiền đã đóng của học viên này cho lớp học này
        // Chỉ tổng hợp các phiếu thu có trạng thái 'da_thanh_toan'
        $paidAmount = PhieuThu::where('hocvien_id', $studentId)
            ->where('lophoc_id', $classId)
            ->where('trangthai', 'da_thanh_toan')
            ->sum('sotien');

        $remainingAmount = $totalTuition - $paidAmount;

        // Đảm bảo số tiền còn lại không bao giờ âm và làm tròn để tránh sai số dấu phẩy động
        $remainingAmount = max(0, round($remainingAmount, 2));
        $totalTuition = round($totalTuition, 2);
        $paidAmount = round($paidAmount, 2);


        return response()->json([
            'total_tuition' => $totalTuition,
            'paid_amount' => $paidAmount,
            'remaining_amount' => $remainingAmount,
            'message' => 'Thông tin học phí đã được tải.'
        ]);
    }




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

        $class = LopHoc::with('trinhdo.dongia')->find($classId);
        if (!$class) {
            return response()->json(['message' => 'Lớp học không tìm thấy.'], 404);
        }

        $totalTuition = 0;
        if ($class->trinhdo && $class->trinhdo->dongia) {
            $totalTuition = $class->trinhdo->dongia->hocphi;
        }

        // Tính số tiền đã đóng HIỆN TẠI trước khi thêm phiếu mới
        $paidAmountBeforeNewPayment = PhieuThu::where('hocvien_id', $studentId)
            ->where('lophoc_id', $classId)
            ->where('trangthai', 'da_thanh_toan')
            ->sum('sotien');

        $remainingAmountBeforeNewPayment = $totalTuition - $paidAmountBeforeNewPayment;

        // Định nghĩa một ngưỡng sai số nhỏ để tránh lỗi dấu phẩy động
        $epsilon = 0.01;

        // Kiểm tra số tiền đóng có vượt quá số tiền còn lại không
        if ($amountToPay > $remainingAmountBeforeNewPayment + $epsilon) {
            return response()->json(['message' => 'Số tiền đóng vượt quá số tiền còn lại.'], 400);
        }

        DB::beginTransaction();

        try {
            // Xác định trạng thái của phiếu thu mới
            // Nếu số tiền đóng đủ hoặc vượt quá số tiền còn lại (do làm tròn), thì là 'da_thanh_toan'
            // Ngược lại, là 'chua_du'
            $phieuThuStatus = (abs($amountToPay - $remainingAmountBeforeNewPayment) < $epsilon || $amountToPay > $remainingAmountBeforeNewPayment)
                ? 'da_thanh_toan'
                : 'chua_du'; // Đã thay đổi từ 'cho_thanh_toan' sang 'chua_du'

            // Tạo một phiếu thu mới
            $phieuThu = PhieuThu::create([
                'hocvien_id' => $studentId,
                'lophoc_id' => $classId,
                'sotien' => $amountToPay,
                'phuongthuc' => $paymentMethod,
                'ngaythanhtoan' => $paymentDate,
                'ghichu' => $note,
                'trangthai' => $phieuThuStatus, // Gán trạng thái đã xác định
                // Thêm nhanvien_id nếu bạn muốn ghi nhận người thu tiền
                // 'nhanvien_id' => auth()->id(), // Giả sử bạn có auth user là nhân viên
            ]);

            DB::commit();

            // === TÍNH TOÁN LẠI HỌC PHÍ SAU KHI CÓ PHIẾU THU MỚI ===
            // Logic này vẫn chỉ tính các phiếu 'da_thanh_toan' vào tổng số tiền đã đóng
            $paidAmountAfterNewPayment = PhieuThu::where('hocvien_id', $studentId)
                ->where('lophoc_id', $classId)
                ->where('trangthai', 'da_thanh_toan')
                ->sum('sotien');

            $remainingAmountAfterNewPayment = $totalTuition - $paidAmountAfterNewPayment;

            // Xác định trạng thái tổng thể của học viên để gửi về frontend
            $newTuitionStatus = 'Chưa xác định';
            $newBadgeClass = 'badge bg-secondary';

            if ($totalTuition <= $epsilon) { // Nếu tổng học phí gần bằng 0
                $newTuitionStatus = 'Chưa có học phí lớp';
                $newBadgeClass = 'badge bg-info';
            } elseif ($remainingAmountAfterNewPayment <= $epsilon) { // Nếu số tiền còn lại rất nhỏ hoặc âm (coi như đã đóng đủ)
                $newTuitionStatus = 'Đã đóng đủ';
                $newBadgeClass = 'badge bg-success';
            } elseif ($paidAmountAfterNewPayment > $epsilon && $remainingAmountAfterNewPayment > $epsilon) { // Đã đóng một phần và vẫn còn nợ đáng kể
                $newTuitionStatus = 'Còn nợ'; // Sẽ hiển thị số tiền nợ ở frontend
                $newBadgeClass = 'badge bg-warning text-dark';
            } else { // paidAmountAfterNewPayment <= $epsilon (chưa đóng gì) và remainingAmountAfterNewPayment > $epsilon (vẫn còn nợ)
                $newTuitionStatus = 'Chưa đóng';
                $newBadgeClass = 'badge bg-danger';
            }

            return response()->json([
                'message' => 'Thu học phí thành công!',
                'phieu_thu_id' => $phieuThu->id,
                'updated_tuition_info' => [
                    'student_id' => $studentId,
                    'total_tuition' => $totalTuition,
                    'paid_amount' => $paidAmountAfterNewPayment,
                    'remaining_amount' => $remainingAmountAfterNewPayment,
                    'hocphi_status' => $newTuitionStatus,
                    'hocphi_badge_class' => $newBadgeClass,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage()], 500);
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
        $phieuThu->load('hocvien.user', 'lophoc.trinhdo.dongia');

        // Logic để tạo biên lai
        // Cách 1: Trả về một view HTML có CSS thân thiện để in
        return view('admin.hocphi.printbienlai', compact('phieuThu'));

        // Cách 2: Sử dụng thư viện như DomPDF để tạo PDF (cần cài đặt: composer require barryvdh/laravel-dompdf)
        /*
        $pdf = Pdf::loadView('admin.phieuthu.print_receipt_pdf', compact('phieuThu'));
        return $pdf->stream('bien_lai_hoc_phi_' . $phieuThu->id . '.pdf');
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
        $class = LopHoc::with(['trinhDo.donGia'])->find($classId);
        if (!$class) {
            // Nếu không tìm thấy lớp học, chuyển hướng và gửi thông báo lỗi
            return redirect()->back()->with('error', 'Lớp học không tồn tại.');
        }

        // Lấy tổng học phí từ bảng dongia thông qua chuỗi quan hệ
        $totalTuition = 0;
        if ($class->trinhDo && $class->trinhDo->donGia && isset($class->trinhDo->donGia->hocphi)) {
            $totalTuition = $class->trinhDo->donGia->hocphi;
        } else {
            Log::warning("Lớp học ID: {$classId} không có thông tin trình độ, đơn giá, hoặc thiếu cột 'hocphi' trong bảng dongia. Sử dụng 0 làm học phí.");
            // Bạn có thể chọn trả về lỗi hoặc cảnh báo người dùng nếu cần
            // return redirect()->back()->with('warning', 'Không thể xác định học phí cho lớp học này. Vui lòng kiểm tra lại cấu hình.');
        }

        // 3. Lấy danh sách học viên kèm theo thông tin user
        $students = HocVien::with('user')->whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            $email = optional($student->user)->email;

            // 4. Tính học phí còn nợ
            $paidAmount = $student->phieuthu()->where('lophoc_id', $classId)->sum('sotien');
            $remainingAmount = $totalTuition - $paidAmount;

            // 5. Gửi mail mà KHÔNG KIỂM TRA ĐIỀU KIỆN (chỉ kiểm tra email có tồn tại)
            try {
                if ($email) {
                    Mail::to($email)->send(new TuitionReminderMail($student, $class, $remainingAmount));
                    $sentCount++;
                } else {
                    Log::warning("Bỏ qua gửi email cho học viên {$student->ten} (ID: {$student->id}) vì email bị thiếu hoặc rỗng.");
                    $failedAttempts[] = $student->ten . " (thiếu email)";
                }
            } catch (\Exception $e) {
                Log::error("Failed to send email to '{$email}' for student '{$student->ten} (ID: {$student->id})': " . $e->getMessage());
                $failedAttempts[] = $student->ten . " (lỗi hệ thống: " . $e->getMessage() . ")"; // Thêm chi tiết lỗi vào thông báo
            }
        }

        // 6. Trả về phản hồi bằng cách chuyển hướng và gửi thông báo
        if ($sentCount > 0) {
            $message = "Đã gửi email nhắc nhở học phí thành công cho {$sentCount} học viên.";
            if (!empty($failedAttempts)) {
                $message .= " Gửi thất bại cho: " . implode('; ', array_unique($failedAttempts));
                return response()->json(['message' => $message, 'type' => 'warning']); // Trả về type warning
            }
            return response()->json(['message' => $message, 'type' => 'success']); // Trả về type success
        } else {
            $message = "Không có email nào được gửi.";
            if (!empty($failedAttempts)) {
                $message .= " Các vấn đề: " . implode('; ', array_unique($failedAttempts));
            } else {
                $message .= " Vui lòng kiểm tra lại danh sách học viên và email của họ.";
            }
            return response()->json(['message' => $message, 'type' => 'error'], 400); // Trả về type error và status 400
        }
    }

    // Ví dụ trong Controller của bạn

}
