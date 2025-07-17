<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Mail\TuVanXacNhan;
use App\Models\KhoaHoc;
use App\Models\TuVan;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Illuminate\Support\Facades\Session;

class TuVanController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5); // Mặc định 10 mục mỗi trang
        $tuKhoa = $request->input('tu_khoa');
        $trangThaiFilter = $request->input('trangthai_filter'); // Lấy tham số lọc trạng thái

        $query = TuVan::query();

        // Áp dụng tìm kiếm
        if ($tuKhoa) {
            $query->where(function ($q) use ($tuKhoa) {
                $q->where('hoten', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('email', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('sdt', 'like', '%' . $tuKhoa . '%')
                    ->orWhere('loinhan', 'like', '%' . $tuKhoa . '%');
            });
        }

        // Áp dụng lọc theo trạng thái (MỚI THÊM)
        if ($trangThaiFilter) {
            $query->where('trangthai', $trangThaiFilter);
        }

        // Eager load mối quan hệ 'khoaHoc' để tránh N+1 query
        $dsTuVan = $query->with('khoaHoc')->orderBy('created_at', 'desc')->paginate($perPage);

        // Truyền các tham số tìm kiếm và lọc hiện tại để phân trang giữ nguyên trạng thái
        $dsTuVan->appends($request->except('page'));

        // Nếu bạn cần danh sách các khóa học cho dropdown trong modal, bạn có thể lấy ở đây
        // $khoahocs = KhoaHoc::all();

        return view('admin.tuvan.index', compact('dsTuVan')); // Truyền $khoahocs nếu cần
    }

    public function update(Request $request, TuVan $tuvan)
    {
        $request->validate([
            'trangthai' => 'required|in:đang chờ xử lý,đã liên hệ,liên hệ không thành công,đã hủy',
            'ghichu'    => 'nullable|string|max:1000',
        ], [
            'trangthai.required' => 'Trạng thái là bắt buộc.',
            'trangthai.in'       => 'Trạng thái không hợp lệ.',
            'ghichu.max'         => 'Ghi chú không được vượt quá 1000 ký tự.',
        ]);

        try {
            $tuvan->update([
                'trangthai' => $request->trangthai,
                'ghichu'    => $request->ghichu,
            ]);

            Session::flash('success', 'Cập nhật yêu cầu tư vấn thành công!');
            return redirect()->route('tuvan'); // Chuyển hướng về trang danh sách
        } catch (\Exception $e) {
            Session::flash('error', 'Đã có lỗi xảy ra khi cập nhật yêu cầu tư vấn: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy(TuVan $id)
    {
        try {
            $id->delete();
            Session::flash('success', 'Xóa yêu cầu tư vấn thành công!');
            return redirect()->route('tuvan');
        } catch (\Exception $e) {
            Session::flash('error', 'Đã có lỗi xảy ra khi xóa yêu cầu tư vấn: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào từ form
        $validatedData = $request->validate([
            'hoten'      => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'sdt'        => 'required|string|max:20',
            'dotuoi'     => 'nullable|integer|min:1|max:100',
            'khoahoc_id' => 'required|exists:khoahoc,id',
            'loinhan'   => 'nullable|string|max:1000',
        ], [
            // ... (Các thông báo lỗi của bạn) ...
            'hoten.required'      => 'Vui lòng nhập họ và tên của bạn.',
            'hoten.string'        => 'Họ và tên không hợp lệ.',
            'hoten.max'           => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required'      => 'Vui lòng nhập địa chỉ email.',
            'email.email'         => 'Địa chỉ email không đúng định dạng.',
            'email.max'           => 'Email không được vượt quá 255 ký tự.',
            'sdt.required'        => 'Vui lòng nhập số điện thoại.',
            'sdt.string'          => 'Số điện thoại không hợp lệ.',
            'sdt.max'             => 'Số điện thoại không được vượt quá 20 ký tự.',
            'dotuoi.integer'      => 'Độ tuổi phải là một số nguyên.',
            'dotuoi.min'          => 'Độ tuổi phải lớn hơn hoặc bằng 1.',
            'dotuoi.max'          => 'Độ tuổi không được vượt quá 100.',
            'khoahoc_id.required' => 'Vui lòng chọn khóa học bạn quan tâm.',
            'khoahoc_id.exists'   => 'Khóa học được chọn không hợp lệ.',
            'loinhan.string'     => 'Lời nhắn không hợp lệ.',
            'loinhan.max'        => 'Lời nhắn không được vượt quá 1000 ký tự.',
        ]);

        try {
            // Lấy thông tin khóa học để gửi vào email
            $khoaHoc = KhoaHoc::find($validatedData['khoahoc_id']);

            // 2. Tạo một bản ghi mới trong bảng yeu_cau_tu_van
            $yeuCau = TuVan::create([
                'hoten'      => $validatedData['hoten'],
                'email'       => $validatedData['email'],
                'sdt'         => $validatedData['sdt'],
                'dotuoi'     => $validatedData['dotuoi'],
                'khoahoc_id' => $validatedData['khoahoc_id'],
                'loinhan'    => $validatedData['loinhan'],
                'trangthai'  => 'đang chờ xử lý',
            ]);

            // 3. Chuẩn bị dữ liệu để gửi email
            $emailData = [
                'hoten'    => $yeuCau->hoten,
                'email'     => $yeuCau->email,
                'sdt'       => $yeuCau->sdt,
                'dotuoi'   => $yeuCau->dotuoi,
                'khoahoc'  => $khoaHoc ? $khoaHoc->ten : 'Không xác định', // Lấy tên khóa học
                'loinhan'  => $yeuCau->loinhan,
            ];

            // 4. Gửi email xác nhận đến học viên
            Mail::to($yeuCau->email)->queue(new TuVanXacNhan($emailData)); // <-- Thay send() bằng queue()

            // 5. Chuyển hướng người dùng trở lại với thông báo thành công
            Session::flash('success', 'Yêu cầu tư vấn của bạn đã được gửi thành công! Chúng tôi đã gửi email xác nhận đến bạn và sẽ liên hệ lại bạn sớm nhất.');
            return redirect()->back();
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có vấn đề khi lưu vào cơ sở dữ liệu hoặc gửi email
            Session::flash('error', 'Đã có lỗi xảy ra khi gửi yêu cầu tư vấn. Vui lòng thử lại sau.');
            // \Log::error('Lỗi khi gửi yêu cầu tư vấn hoặc email: ' . $e->getMessage() . ' at line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->withInput();
        }
    }
}
