<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\TuVan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffTuvanController extends Controller
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
        $dsTuVan = $query->with('khoaHoc.lopHocs.trinhDo')->orderBy('created_at', 'desc')->paginate($perPage);

        // Truyền các tham số tìm kiếm và lọc hiện tại để phân trang giữ nguyên trạng thái
        $dsTuVan->appends($request->except('page'));

        // Nếu bạn cần danh sách các khóa học cho dropdown trong modal, bạn có thể lấy ở đây
        // $khoahocs = KhoaHoc::all();

        return view('staff.tuvan.index', compact('dsTuVan')); // Truyền $khoahocs nếu cần
    }
    public function update(Request $request, TuVan $tuvan)
    {
        $request->validate([
            'trangthai' => 'required|in:đang chờ xử lý,liên hệ sau,đã liên hệ,liên hệ không thành công,đã hủy',
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
            return redirect()->route('staff.tuvan'); // Chuyển hướng về trang danh sách
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
            return redirect()->route('staff.tuvan');
        } catch (\Exception $e) {
            Session::flash('error', 'Đã có lỗi xảy ra khi xóa yêu cầu tư vấn: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
