<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TaiLieuHocTap;
use App\Models\HocVien; // Import HocVien model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Để quản lý file
use Illuminate\Support\Facades\Log;

class STMaterialController extends Controller
{
    /**
     * Hiển thị danh sách tài liệu học tập mà học viên có thể xem.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        // 2. Lấy thông tin học viên liên kết với người dùng hiện tại
        $hocvien = HocVien::where('user_id', $user->id)
            ->with('lophocs') // Eager load mối quan hệ lophocs của học viên
            ->first();

        // 3. Nếu tài khoản không liên kết với hồ sơ học viên, chuyển hướng hoặc báo lỗi
        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // 4. Lấy danh sách ID các lớp học mà học viên này đang tham gia
        $enrolledClassIds = $hocvien->lophocs->pluck('id')->toArray();

        $materials = collect(); // Khởi tạo một collection rỗng cho tài liệu

        if (!empty($enrolledClassIds)) {
            // 5. Lấy tất cả tài liệu học tập liên quan đến các lớp học viên đang tham gia
            // và tài liệu đó phải được tải lên bởi giáo viên (giaovien_id IS NOT NULL)
            $materials = TaiLieuHocTap::whereIn('lophoc_id', $enrolledClassIds)
                ->whereNotNull('giaovien_id') // Chỉ lấy tài liệu do giáo viên tải lên
                ->with('lophoc', 'giaovien') // Eager load thông tin lớp học và giáo viên
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            Log::info("Học viên ID: {$hocvien->id} không đăng ký lớp học nào, không có tài liệu để hiển thị.");
        }

        // 6. Truyền dữ liệu sang view
        return view('student.tailieu.index', compact('materials'));
    }

    /**
     * Cho phép học viên tải xuống tài liệu.
     *
     * @param  \App\Models\TaiLieuHocTap  $material
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function download(TaiLieuHocTap $material)
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để tải xuống tài liệu.');
        }

        $user = Auth::user();
        $hocvien = HocVien::where('user_id', $user->id)->first();

        if (!$hocvien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ học viên.');
        }

        // 2. Kiểm tra xem tài liệu có tồn tại không và có phải là tài liệu của giáo viên không
        if (!$material || is_null($material->giaovien_id)) {
            return redirect()->back()->with('error', 'Tài liệu không hợp lệ hoặc không có sẵn để tải xuống.');
        }

        // 3. Kiểm tra xem học viên có quyền truy cập tài liệu này không
        // (Tức là tài liệu thuộc về một lớp mà học viên đang tham gia)
        $enrolledClassIds = $hocvien->lophocs->pluck('id')->toArray();

        if (!in_array($material->lophoc_id, $enrolledClassIds)) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập tài liệu này.');
        }

        try {
            // 4. Lấy đường dẫn file trong storage
            // Chuyển đổi URL công khai thành đường dẫn nội bộ của storage
            // Ví dụ: từ 'http://localhost/storage/materials/file.pdf' thành 'public/materials/file.pdf'
            $filePath = str_replace(Storage::url(''), 'public/', $material->duongdanfile);

            if (Storage::exists($filePath)) {
                // 5. Tạo tên file tải xuống với đuôi file chính xác
                $originalExtension = pathinfo($material->duongdanfile, PATHINFO_EXTENSION);
                $downloadFileName = $material->tentailieu . '.' . $originalExtension;

                // 6. Trả về file để tải xuống, kèm theo Content-Type header để đảm bảo định dạng
                // Sử dụng $material->loaifile để lấy MIME type đã lưu trong DB
                return Storage::download($filePath, $downloadFileName, [
                    'Content-Type' => $material->loaifile,
                ]);
            } else {
                Log::error("File tài liệu không tồn tại: {$filePath} cho tài liệu ID: {$material->id}");
                return redirect()->back()->with('error', 'File tài liệu không tồn tại.');
            }
        } catch (\Exception $e) {
            Log::error("Lỗi khi tải xuống tài liệu ID: {$material->id}. Lỗi: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải xuống tài liệu: ' . $e->getMessage());
        }
    }
}
