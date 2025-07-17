<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\NamHoc;
use App\Models\TrinhDo;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index()
    {
        // Lấy tất cả các khóa học từ database
        // Bạn có thể thêm điều kiện lọc, sắp xếp, hoặc phân trang nếu cần
        $khoahocss = KhoaHoc::all();

        // Nếu bạn có mối quan hệ với giáo viên (ví dụ: một khóa học có một giáo viên),
        // bạn có thể eager load để tránh N+1 query problem
        // $courses = Course::with('teacher')->get();

        return view('users_layout', compact('khoahocss')); // Truyền biến $courses sang view
        // Giả sử view của bạn là resources/views/frontend/index.blade.php
    }

    public function courses_detail($id)
    {
        // Tìm khóa học theo ID và eager load các mối quan hệ cần thiết
        // Eager load:
        // - lophocs (để biết các lớp học thuộc khóa này)
        // - lophocs.trinhDo (để lấy thông tin trình độ của mỗi lớp)
        // - lophocs.trinhDo.kyNang (để lấy thông tin kỹ năng từ trình độ)
        // - lophocs.giaoVien (để lấy thông tin giảng viên)
        // KHÔNG eager load dongia qua trinhDo ở đây, vì chúng ta sẽ lấy riêng
        $courses = KhoaHoc::all();
        $khoaHoc = KhoaHoc::with([
            'lophocs.giaoVien',
            'lophocs.trinhDo.kyNang',

        ])->find($id);

        if (!$khoaHoc) {
            return redirect()->route('home')->with('error', 'Khóa học không tồn tại.');
        }

        // Lấy danh sách các trình độ duy nhất liên quan đến khóa học này
        // (chỉ những trình độ mà khóa học này thực sự cung cấp thông qua các lớp học của nó)
        $trinhDos = $khoaHoc->lophocs->pluck('trinhDo')->filter()->unique('id');
        $khoaHocs = KhoaHoc::withCount('lophocs')->get();
        // Lấy học phí cho từng trình độ trong năm học hiện tại
        $hocPhiTheoTrinhDo = [];
        $currentYearString = '2024 - 2025'; // Hoặc lấy từ cấu hình, biến toàn cục, hoặc năm hiện tại
        $namHocHienTai = NamHoc::where('nam', $currentYearString)->first();
        $khoahocss = KhoaHoc::all();
        if ($namHocHienTai) {
            foreach ($trinhDos as $trinhDo) {
                // Tìm đơn giá cho trình độ này trong năm học hiện tại
                // Lấy từ mối quan hệ donGias của TrinhDo
                $donGia = $trinhDo->dongia()->where('namhoc_id', $namHocHienTai->id)->first();

                if ($donGia) {
                    $hocPhiTheoTrinhDo[$trinhDo->id] = $donGia->muc_gia; // Sử dụng muc_gia
                } else {
                    $hocPhiTheoTrinhDo[$trinhDo->id] = 'Chưa cập nhật';
                }
            }
        } else {
            // Nếu không tìm thấy năm học hiện tại, tất cả các trình độ sẽ không có giá
            foreach ($trinhDos as $trinhDo) {
                $hocPhiTheoTrinhDo[$trinhDo->id] = 'Không tìm thấy năm học';
            }
        }

        // Lấy thông tin giảng viên cho khóa học chính (lấy từ lớp học đầu tiên nếu có)
        $firstLopHoc = $khoaHoc->lophocs->first();
        $giangVienTen = $firstLopHoc && $firstLopHoc->giaoVien ? $firstLopHoc->giaoVien->ten : 'Đang cập nhật';

        // Lấy các khóa học liên quan (ví dụ: ngẫu nhiên 3 khóa học khác)
        // Bạn có thể thêm logic phức tạp hơn ở đây (ví dụ: cùng danh mục)
        $relatedCourses = KhoaHoc::where('id', '!=', $khoaHoc->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Truyền dữ liệu khóa học, các khóa học liên quan, trình độ, học phí theo trình độ và tên giảng viên tới view
        return view('pages.courses_detail', compact('khoaHoc', 'relatedCourses', 'khoahocss', 'trinhDos', 'hocPhiTheoTrinhDo', 'giangVienTen', 'khoaHocs', 'courses'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $khoahocId = $request->input('khoahoc_id');

        // Always get all courses for the dropdown on the search form
        $khoaHocsForDropdown = KhoaHoc::all();

        // Initialize variables for the view
        $selectedKhoaHoc = null;
        $khoaHocResults = collect();
        $lopHocResults = collect();
        $trinhDoResults = collect();
        $courses = Khoahoc::all();
        // --- Priority Logic for Display ---

        // Scenario 1: Only a specific course is selected from the dropdown (no keyword)
        if ($khoahocId && !$keyword) {
            $selectedKhoaHoc = KhoaHoc::with('lophocs.trinhDo')->find($khoahocId);

            if ($selectedKhoaHoc) {
                return view('pages.search_results', [
                    'selectedKhoaHoc' => $selectedKhoaHoc,
                    'khoahocss' => $khoaHocsForDropdown,
                    'khoahoc_id_selected' => $khoahocId,
                    'keyword_searched' => $keyword,
                    'khoaHocResults' => collect(),
                    'lopHocResults' => collect(),
                    'trinhDoResults' => collect(),
                ], compact('courses'));
            }
        }

        // Scenario 2: There's a keyword provided (either alone or with a course ID), perform general search
        if ($keyword) {
            // Chuẩn hóa từ khóa người dùng nhập để tìm kiếm chính xác
            $normalizedKeyword = trim(mb_strtolower($keyword));

            // Tách từ khóa thành các từ riêng lẻ để tìm kiếm mở rộng (chứa từ)
            $searchTerms = preg_split('/\s+/', $normalizedKeyword, -1, PREG_SPLIT_NO_EMPTY);

            // --- TÌM KIẾM CHO KHOAHOC ---
            $khoaHocQuery = KhoaHoc::query();
            if ($khoahocId) {
                $khoaHocQuery->where('id', $khoahocId);
            }

            // 1. Tìm kiếm khớp chính xác cho KhoaHoc
            $exactKhoaHocResults = (clone $khoaHocQuery)->where(function ($query) use ($normalizedKeyword) {
                $query->whereRaw('LOWER(ten) = ?', [$normalizedKeyword])
                    ->orWhereRaw('LOWER(mota) = ?', [$normalizedKeyword]);
            })->get();

            // 2. Tìm kiếm mở rộng (chứa từ) cho KhoaHoc
            $broadKhoaHocResults = (clone $khoaHocQuery)->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('ten', 'LIKE', '%' . $term . '%')
                        ->orWhere('mota', 'LIKE', '%' . $term . '%');
                }
            })->get();

            // Kết hợp và loại bỏ trùng lặp, ưu tiên kết quả khớp chính xác
            $khoaHocResults = $exactKhoaHocResults->merge($broadKhoaHocResults)->unique('id');


            // --- TÌM KIẾM CHO LOPHOC ---
            $lopHocQuery = LopHoc::query()->with('khoaHoc', 'trinhDo');
            if ($khoahocId) {
                $lopHocQuery->where('khoahoc_id', $khoahocId);
            }

            // 1. Tìm kiếm khớp chính xác cho LopHoc
            $exactLopHocResults = (clone $lopHocQuery)->where(function ($query) use ($normalizedKeyword) {
                $query->whereRaw('LOWER(tenlophoc) = ?', [$normalizedKeyword])
                    ->orWhereRaw('LOWER(malophoc) = ?', [$normalizedKeyword]);
                // Có thể thêm tìm kiếm chính xác theo tên trình độ nếu cần
                $query->orWhereHas('trinhDo', function ($q) use ($normalizedKeyword) {
                    $q->whereRaw('LOWER(ten) = ?', [$normalizedKeyword]);
                });
            })->get();

            // 2. Tìm kiếm mở rộng (chứa từ) cho LopHoc
            $broadLopHocResults = (clone $lopHocQuery)->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('tenlophoc', 'LIKE', '%' . $term . '%')
                        ->orWhere('malophoc', 'LIKE', '%' . $term . '%');
                    $query->orWhereHas('trinhDo', function ($q) use ($term) {
                        $q->where('ten', 'LIKE', '%' . $term . '%');
                    });
                }
            })->get();

            // Kết hợp và loại bỏ trùng lặp, ưu tiên kết quả khớp chính xác
            $lopHocResults = $exactLopHocResults->merge($broadLopHocResults)->unique('id');


            // --- TÌM KIẾM CHO TRINHDO (nếu bạn muốn hiển thị riêng) ---
            $trinhDoQuery = TrinhDo::query();

            // 1. Tìm kiếm khớp chính xác cho TrinhDo
            $exactTrinhDoResults = (clone $trinhDoQuery)->where(function ($query) use ($normalizedKeyword) {
                $query->whereRaw('LOWER(ten) = ?', [$normalizedKeyword])
                    ->orWhereRaw('LOWER(mota) = ?', [$normalizedKeyword]);
            })->get();

            // 2. Tìm kiếm mở rộng (chứa từ) cho TrinhDo
            $broadTrinhDoResults = (clone $trinhDoQuery)->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('ten', 'LIKE', '%' . $term . '%')
                        ->orWhere('mota', 'LIKE', '%' . $term . '%');
                }
            })->get();

            // Kết hợp và loại bỏ trùng lặp, ưu tiên kết quả khớp chính xác
            $trinhDoResults = $exactTrinhDoResults->merge($broadTrinhDoResults)->unique('id');
        }
        $courses = Khoahoc::all();
        // Return the view with all collected search results
        return view('pages.search_results', [
            'selectedKhoaHoc' => $selectedKhoaHoc,
            'khoaHocResults' => $khoaHocResults,
            'lopHocResults' => $lopHocResults,
            'trinhDoResults' => $trinhDoResults,
            'khoahocss' => $khoaHocsForDropdown,
            'keyword_searched' => $keyword,
            'khoahoc_id_selected' => $khoahocId,
        ], compact('courses'));
    }
}
