<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TinTucController extends Controller
{
    public function index(Request $request)
    {
        // Eager load sâu: TinTuc -> NhanVien -> User
        $query = TinTuc::with(['tacgia.nhanvien']);

        // Tìm kiếm theo tiêu đề hoặc nội dung
        if ($request->filled('tu_khoa')) {
            $query->where(function ($q) use ($request) {
                $q->where('tieude', 'like', '%' . $request->tu_khoa . '%')
                    ->orWhere('noidung', 'like', '%' . $request->tu_khoa . '%');
            });
        }

        $perPage = $request->input('per_page', 10);
        $newsArticles = $query->orderBy('ngaydang', 'desc')->paginate($perPage);

        return view('staff.tintuc.index', compact('newsArticles'));
    }


    public function store(Request $request)
    {
        // Validate các trường (bỏ kiểm tra tác giả vì ta gán thủ công)
        // $request->validate([
        //     'tieude' => 'required|string|max:255',
        //     'noidung' => 'required|string',
        //     'ngaydang' => 'required|date',
        //     'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'trang_thai' => 'required|in:draft,publish',
        // ]);

        $data = $request->except('hinhanh');

        // Tạo slug duy nhất từ tiêu đề
        $data['slug'] = Str::slug($request->tieude);
        $originalSlug = $data['slug'];
        $count = 1;
        while (TinTuc::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count++;
        }

        // Lấy nhân viên từ user hiện tại
        $user = Auth::user();
        $nhanvien = $user->nhanvien;

        if (!$nhanvien) {
            return redirect()->back()->with('error', 'Tài khoản chưa được gán với nhân viên.');
        }

        // Gán tacgia_id là user_id (vì khóa ngoại trỏ đến bảng users)
        $data['tacgia_id'] = $nhanvien->user_id;
        // $trangthai = 'da_dang';
        $data['trang_thai'] = 'da_dang';
        $data['ngaydang'] = now();
        // Xử lý ảnh
        if ($request->hasFile('hinhanh')) {
            $imagePath = $request->file('hinhanh')->store('news_images', 'public');
            $data['hinhanh'] = Storage::url($imagePath);
        }

        TinTuc::create($data);

        return redirect()->route('tintuc')->with('success', 'Tin tức đã được thêm thành công!');
    }
    public function update(Request $request, $id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        // Lấy dữ liệu bỏ qua ảnh
        $data = $request->except('hinhanh');

        // Nếu tiêu đề thay đổi thì tạo lại slug
        if ($tinTuc->tieude !== $request->tieude) {
            $data['slug'] = Str::slug($request->tieude);
            $originalSlug = $data['slug'];
            $count = 1;
            while (TinTuc::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        } else {
            $data['slug'] = $tinTuc->slug; // giữ nguyên slug cũ
        }

        // Ảnh mới (nếu có)
        if ($request->hasFile('hinhanh')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($tinTuc->hinhanh && Storage::exists(str_replace('/storage/', 'public/', $tinTuc->hinhanh))) {
                Storage::delete(str_replace('/storage/', 'public/', $tinTuc->hinhanh));
            }

            $imagePath = $request->file('hinhanh')->store('news_images', 'public');
            $data['hinhanh'] = Storage::url($imagePath);
        }
        $data['ngaydang'] = now();
        $tinTuc->update($data);

        return redirect()->route('tintuc')->with('success', 'Tin tức đã được cập nhật thành công!');
    }
    public function destroy($id)
    {
        $tinTuc = TinTuc::findOrFail($id);

        // Xóa ảnh nếu có
        if ($tinTuc->hinhanh && Storage::exists(str_replace('/storage/', 'public/', $tinTuc->hinhanh))) {
            Storage::delete(str_replace('/storage/', 'public/', $tinTuc->hinhanh));
        }

        $tinTuc->delete();

        return redirect()->route('tintuc')->with('success', 'Tin tức đã được xóa thành công!');
    }
}
