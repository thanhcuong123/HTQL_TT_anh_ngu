<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use App\Models\PhieuThu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class STHocphiController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        $hocvien = \App\Models\HocVien::where('user_id', $user->id)
            ->with('lophocs.trinhdo.dongias', 'phieuthu') // Eager load necessary relationships
            ->first();

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // Prepare tuition and payment data for each class
        $classPayments = [];
        foreach ($hocvien->lophocs as $lopHoc) {
            $totalTuition = \App\Models\DonGia::where('trinhdo_id', $lopHoc->trinhdo_id)
                ->where('namhoc_id', $lopHoc->namhoc_id)
                ->value('hocphi') ?? 0;
            $amountPaid = PhieuThu::where('hocvien_id', $hocvien->id)
                ->where('lophoc_id', $lopHoc->id)
                ->where('trangthai', 'da_thanh_toan')
                ->sum('sotien');
            $remainingBalance = $totalTuition - $amountPaid;

            $paymentStatus = 'Chưa thanh toán';
            if ($amountPaid >= $totalTuition && $totalTuition > 0) {
                $paymentStatus = 'Đã thanh toán';
            } elseif ($amountPaid > 0 && $amountPaid < $totalTuition) {
                $paymentStatus = 'Thanh toán một phần';
            } elseif ($totalTuition == 0) {
                $paymentStatus = 'Miễn phí / Không xác định'; // Handle case where tuition is 0
            }


            $classPayments[] = [
                'lophoc' => $lopHoc,
                'total_tuition' => $totalTuition,
                'amount_paid' => $amountPaid,
                'remaining_balance' => $remainingBalance,
                'payment_status' => $paymentStatus,
            ];
        }

        // CONVERT THE ARRAY TO A LARAVEL COLLECTION HERE
        $classPayments = collect($classPayments);

        return view('student.payment.index', compact('hocvien', 'classPayments'));
    }
}
