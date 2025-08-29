<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiemDanh;
use App\Models\GiaoVien;
use App\Models\HocVi;
use App\Models\HocVien;
use App\Models\LopHoc;
use Illuminate\Http\Request;

class ReportDiemdanhController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách các lớp học, giáo viên, học viên để đổ vào dropdown bộ lọc
        $lophocs = LopHoc::orderBy('tenlophoc')->get();
        $giaoviens = GiaoVien::orderBy('ten')->get();
        $hocviens = HocVien::orderBy('ten')->get();

        // Lấy các giá trị bộ lọc từ request
        $selectedLopHoc = $request->input('lophoc_id');
        $selectedGiaoVien = $request->input('giaovien_id');
        $selectedHocVien = $request->input('hocvien_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Bắt đầu query cho dữ liệu điểm danh
        $query = DiemDanh::query()
            ->with(['lophoc', 'hocvien', 'giaovien']); // Eager load các mối quan hệ

        // Áp dụng các bộ lọc
        if ($selectedLopHoc) {
            $query->where('lophoc_id', $selectedLopHoc);
        }
        if ($selectedGiaoVien) {
            $query->where('giaovien_id', $selectedGiaoVien);
        }
        if ($selectedHocVien) {
            $query->where('hocvien_id', $selectedHocVien);
        }
        if ($startDate) {
            $query->whereDate('ngaydiemdanh', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('ngaydiemdanh', '<=', $endDate);
        }

        // Lấy tất cả các bản ghi điểm danh đã lọc
        $diemDanhRecords = $query->get();

        // --- Tính toán thống kê tổng quan ---
        $totalRecords = $diemDanhRecords->count();
        $coMatCount = $diemDanhRecords->where('trangthaidiemdanh', 'co_mat')->count();
        $vangMatCount = $diemDanhRecords->where('trangthaidiemdanh', 'vang_mat')->count();
        $coPhepCount = $diemDanhRecords->where('trangthaidiemdanh', 'co_phep')->count();
        $diMuonCount = $diemDanhRecords->where('trangthaidiemdanh', 'di_muon')->count();

        $attendanceRate = $totalRecords > 0 ? ($coMatCount / $totalRecords) * 100 : 0;
        $absenceRate = $totalRecords > 0 ? (($vangMatCount + $coPhepCount + $diMuonCount) / $totalRecords) * 100 : 0;

        // --- Tính toán thống kê cho từng học viên ---
        $individualStudentStats = [];
        $diemDanhRecords->groupBy('hocvien_id')->each(function ($records, $hocvienId) use (&$individualStudentStats) {
            $hocVien = $records->first()->hocvien; // Lấy thông tin học viên từ bản ghi đầu tiên trong nhóm
            if ($hocVien) {
                $totalSessions = $records->count();
                $coMat = $records->where('trangthaidiemdanh', 'co_mat')->count();
                $vangMat = $records->where('trangthaidiemdanh', 'vang_mat')->count();
                $coPhep = $records->where('trangthaidiemdanh', 'co_phep')->count();
                $diMuon = $records->where('trangthaidiemdanh', 'di_muon')->count();

                $individualStudentStats[] = [
                    'hocvien_id' => $hocvienId,
                    'ten_hoc_vien' => $hocVien->ten, // Giả sử model HocVien có trường 'ten'
                    'ma_hoc_vien' => $hocVien->mahocvien ?? 'N/A', // Giả sử model HocVien có trường 'ma_hoc_vien'
                    'total_sessions' => $totalSessions,
                    'co_mat' => $coMat,
                    'vang_mat' => $vangMat,
                    'co_phep' => $coPhep,
                    'di_muon' => $diMuon,
                    'attendance_rate' => $totalSessions > 0 ? ($coMat / $totalSessions) * 100 : 0,
                ];
            }
        });

        // Sắp xếp thống kê cá nhân theo tên học viên
        usort($individualStudentStats, function ($a, $b) {
            return strcmp($a['ten_hoc_vien'], $b['ten_hoc_vien']);
        });


        return view('admin.reports.diemdanh', compact(
            'lophocs',
            'giaoviens',
            'hocviens',
            'selectedLopHoc',
            'selectedGiaoVien',
            'selectedHocVien',
            'startDate',
            'endDate',
            'totalRecords',
            'coMatCount',
            'vangMatCount',
            'coPhepCount',
            'diMuonCount',
            'attendanceRate',
            'absenceRate',
            'individualStudentStats'
        ));
    }
}
