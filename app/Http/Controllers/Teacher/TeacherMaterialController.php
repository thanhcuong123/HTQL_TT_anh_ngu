<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TaiLieuHocTap;
use App\Models\LopHoc;
use App\Models\ThoiKhoaBieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TeacherMaterialController extends Controller
{

    public function index()
    {

        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->route('home')->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }


        $materials = TaiLieuHocTap::where('giaovien_id', $giaoVien->id)
            ->with('lopHoc')
            ->orderBy('created_at', 'desc')
            ->get();


        $teacherClassIds = ThoiKhoaBieu::where('giaovien_id', $giaoVien->id)
            ->distinct('lophoc_id') // Lấy các lophoc_id duy nhất
            ->pluck('lophoc_id'); // Lấy ra chỉ các ID lớp học

        $teacherClasses = LopHoc::whereIn('id', $teacherClassIds)
            ->orderBy('tenlophoc', 'asc')
            ->get();


        return view('teacher.tailieu.index', compact('materials', 'teacherClasses'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'tentailieu' => 'required|string|max:255',
            'file_tai_lieu' => 'required|file|max:20480',
            'lophoc_id' => 'nullable|exists:lophoc,id',
            'mota' => 'nullable|string|max:1000',
        ], [
            'tentailieu.required' => 'Tên tài liệu không được để trống.',
            'file_tai_lieu.required' => 'Vui lòng chọn một file để tải lên.',
            'file_tai_lieu.file' => 'File tải lên không hợp lệ.',
            'file_tai_lieu.max' => 'Kích thước file không được vượt quá 20MB.',
            'lophoc_id.exists' => 'Lớp học được chọn không hợp lệ.',
        ]);


        $user = Auth::user();
        $giaoVien = $user->giaovien;

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Tài khoản của bạn không liên kết với hồ sơ giáo viên.');
        }

        try {

            $file = $request->file('file_tai_lieu');

            if (!$file) {
                return redirect()->back()->withInput()->with('error', 'Không tìm thấy file tải lên. Vui lòng thử lại.');
            }


            $fileName = time() . '_' . Str::slug($request->input('tentailieu')) . '.' . $file->getClientOriginalExtension();

            $filePath = $file->storeAs('public/materials', $fileName);


            TaiLieuHocTap::create([
                'giaovien_id' => $giaoVien->id,
                'lophoc_id' => $request->lophoc_id,
                'tentailieu' => $request->tentailieu,
                'duongdanfile' => Storage::url($filePath),
                'loaifile' => $file->getClientMimeType(),
                'kichthuocfile' => $file->getSize(),
                'mota' => $request->mota,
            ]);

            return redirect()->route('teacher.materials.index')->with('success', 'Tài liệu đã được tải lên thành công!');
        } catch (\Exception $e) {
            Log::error("Lỗi khi tải lên tài liệu: " . $e->getMessage() . " tại dòng " . $e->getLine() . " trong file " . $e->getFile());
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi tải lên tài liệu: ' . $e->getMessage());
        }
    }


    public function destroy(TaiLieuHocTap $material)
    {
        $user = Auth::user();
        $giaoVien = $user->giaovien;

        // Đảm bảo chỉ giáo viên sở hữu tài liệu mới được xóa
        if (!$giaoVien || $material->giaovien_id !== $giaoVien->id) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa tài liệu này.');
        }

        try {
            // Xóa file khỏi storage
            $filePath = str_replace(Storage::url(''), 'public/', $material->duongdanfile);
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            // Xóa bản ghi khỏi database
            $material->delete();

            return redirect()->route('teacher.materials.index')->with('success', 'Tài liệu đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa tài liệu: " . $e->getMessage() . " tại dòng " . $e->getLine() . " trong file " . $e->getFile());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa tài liệu: ' . $e->getMessage());
        }
    }
}
