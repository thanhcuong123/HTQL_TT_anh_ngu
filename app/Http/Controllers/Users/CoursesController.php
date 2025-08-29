<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\HocVien;
use App\Models\KhoaHoc;
use App\Models\LopHoc;
use App\Models\NamHoc;
use App\Models\TrinhDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    public function index()
    {
        // Lấy danh sách khóa học kèm đầy đủ quan hệ

        $trinhdos = TrinhDo::all();
        $totalClasses = LopHoc::count();
        $totalCourses = KhoaHoc::count();
        $totalLevels  = TrinhDo::count();
        $totalStudents = HocVien::count();
        $khoahocss = KhoaHoc::with([
            'lopHocs.trinhdo.dongias'
        ])->get();
        // Xác định năm học hiện tại
        $currentYearString = '2024 - 2025';
        $namHocHienTai = NamHoc::where('nam', $currentYearString)->first();

        // Duyệt và gán học phí tạm vào mỗi KhoaHoc
        foreach ($khoahocss as $khoaHoc) {
            $trinhDos = $khoaHoc->lopHocs->pluck('trinhdo')->filter()->unique('id');
            $hocPhi = null;

            if ($namHocHienTai) {
                foreach ($trinhDos as $trinhDo) {
                    $donGia = $trinhDo->dongias->where('namhoc_id', $namHocHienTai->id)->first();
                    if ($donGia && is_numeric($donGia->hocphi)) {
                        $hocPhi = $donGia->hocphi;
                        break; // chỉ cần 1 học phí đại diện
                    }
                }
            }

            // Thêm thuộc tính ảo để view dễ dùng
            $khoaHoc->hoc_phi = $hocPhi;
        }

        return view('users_layout', compact(
            'khoahocss',
            'totalClasses',
            'totalCourses',
            'totalLevels',
            'totalStudents',
            'trinhdos'
        ));
    }

    public function dem()
    {
        $totalClasses = LopHoc::count();      // Bảng lop_hoc
        $totalCourses = KhoaHoc::count();     // Bảng khoa_hoc
        $totalLevels  = TrinhDo::count();     // Bảng trinh_do
        $totalStudents = HocVien::count();    // Bảng hoc_vien

        return view('pages.content', compact(
            'totalClasses',
            'totalCourses',
            'totalLevels',
            'totalStudents'
        ));
    }


    public function courses_detail($id)
    {
        // Lấy khóa học + quan hệ cần thiết
        $khoaHoc = KhoaHoc::with([
            'lopHocs.trinhDo.dongias',
            'lopHocs.giaoVien',
            'lopHocs.trinhDo.kyNangs',
            'namHoc' // Lấy luôn năm học của KH
        ])->findOrFail($id);

        $trinhDos = $khoaHoc->lopHocs->pluck('trinhDo')->filter()->unique('id');

        $hocPhiTheoTrinhDo = [];

        if ($khoaHoc->namHoc) {
            foreach ($trinhDos as $trinhDo) {
                $dongia = $trinhDo->dongias
                    ->where('namhoc_id', $khoaHoc->namhoc_id) // lấy đúng năm học của KH
                    ->first();

                $hocPhiTheoTrinhDo[$trinhDo->id] = $dongia ? $dongia->hocphi : null;
            }
        } else {
            foreach ($trinhDos as $trinhDo) {
                $hocPhiTheoTrinhDo[$trinhDo->id] = null; // KH chưa có năm học thì ko có giá
            }
        }

        $firstLopHoc = $khoaHoc->lopHocs->first();
        $giangVienTen = $firstLopHoc && $firstLopHoc->giaoVien ? $firstLopHoc->giaoVien->ten : 'Đang cập nhật';

        $khoaHocs = KhoaHoc::withCount('lopHocs')->get();
        $relatedCourses = KhoaHoc::where('id', '!=', $khoaHoc->id)->inRandomOrder()->limit(3)->get();
        $courses = KhoaHoc::all();
        $khoahocss = DB::table('khoahoc')
            ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
            ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
            ->select(
                'khoahoc.id as khoahoc_id',
                'khoahoc.ma  as khoahoc_ten',
                'trinhdo.ten as trinhdo_ten'
            )
            ->distinct()
            ->get();

        return view('pages.courses_detail', compact(
            'khoaHoc',
            'relatedCourses',
            'khoahocss',
            'trinhDos',
            'hocPhiTheoTrinhDo',
            'giangVienTen',
            'khoaHocs',
            'courses'
        ));
    }


    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $khoahocId = $request->input('khoahoc_id');
        $trinhdos = TrinhDo::all();
        // Always get all courses for the dropdown on the search form
        $khoaHocsForDropdown =  DB::table('khoahoc')
            ->join('lophoc', 'khoahoc.id', '=', 'lophoc.khoahoc_id')
            ->join('trinhdo', 'lophoc.trinhdo_id', '=', 'trinhdo.id')
            ->select(
                'khoahoc.id as khoahoc_id',
                'khoahoc.ma as khoahoc_ten',
                'trinhdo.ten as trinhdo_ten'
            )
            ->distinct()
            ->get();

        // Initialize variables for the view
        $selectedKhoaHoc = null;
        $khoaHocResults = collect();
        $lopHocResults = collect();
        $trinhDoResults = collect();
        $courses = Khoahoc::all();

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
                    'trinhdos'
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
            'trinhdos'
        ], compact('courses'));
    }
}
