@extends('index')

@section('title-content')
<title>Báo cáo Thống kê Điểm danh</title>
@endsection

@section('main-content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script> {{-- Hỗ trợ tiếng Việt --}}

<div class="card shadow-sm rounded-lg">
    <div class="card-body p-4">
        <h3 class="card-title text-2xl font-semibold mb-4 text-gray-800">Báo cáo Thống kê Điểm danh</h3>

        <!-- Form Bộ lọc -->
        <form method="GET" action="{{ route('diemdanh.report') }}" class="mb-4 p-3 border rounded-lg bg-light">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="lophoc_id" class="form-label">Lớp học:</label>
                    <select name="lophoc_id" id="lophoc_id" class="form-select rounded-md">
                        <option value="">-- Tất cả lớp học --</option>
                        @foreach($lophocs as $lop)
                        <option value="{{ $lop->id }}" {{ $selectedLopHoc == $lop->id ? 'selected' : '' }}>{{ $lop->tenlophoc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="giaovien_id" class="form-label">Giáo viên:</label>
                    <select name="giaovien_id" id="giaovien_id" class="form-select rounded-md">
                        <option value="">-- Tất cả giáo viên --</option>
                        @foreach($giaoviens as $gv)
                        <option value="{{ $gv->id }}" {{ $selectedGiaoVien == $gv->id ? 'selected' : '' }}>{{ $gv->ten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="hocvien_id" class="form-label">Học viên:</label>
                    <select name="hocvien_id" id="hocvien_id" class="form-select rounded-md">
                        <option value="">-- Tất cả học viên --</option>
                        @foreach($hocviens as $hv)
                        <option value="{{ $hv->id }}" {{ $selectedHocVien == $hv->id ? 'selected' : '' }}>{{ $hv->ten }} (Mã: {{ $hv->mahocvien ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Từ ngày:</label>
                    <input type="text" name="start_date" id="start_date" class="form-control rounded-md" value="{{ $startDate }}" placeholder="Chọn ngày bắt đầu">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Đến ngày:</label>
                    <input type="text" name="end_date" id="end_date" class="form-control rounded-md" value="{{ $endDate }}" placeholder="Chọn ngày kết thúc">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary rounded-md me-2">Xem báo cáo</button>
                <a href="{{ route('diemdanh.report') }}" class="btn btn-outline-secondary rounded-md">Xoá bộ lọc</a>
            </div>
        </form>

        <!-- Thống kê tổng quan -->
        <div class="mb-5">
            <h4 class="text-xl font-semibold mb-3 text-gray-700">Thống kê tổng quan</h4>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-primary text-white rounded-lg shadow-sm">
                        <p class="mb-0 text-sm">Tổng số buổi điểm danh</p>
                        <h5 class="mb-0 text-2xl font-bold">{{ $totalRecords }}</h5>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-success text-white rounded-lg shadow-sm">
                        <p class="mb-0 text-sm">Số buổi có mặt</p>
                        <h5 class="mb-0 text-2xl font-bold">{{ $coMatCount }}</h5>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-danger text-white rounded-lg shadow-sm">
                        <p class="mb-0 text-sm">Số buổi vắng mặt</p>
                        <h5 class="mb-0 text-2xl font-bold">{{ $vangMatCount }}</h5>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-warning text-dark rounded-lg shadow-sm">
                        <p class="mb-0 text-sm">Số buổi đi muộn</p>
                        <h5 class="mb-0 text-2xl font-bold">{{ $diMuonCount }}</h5>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-3 bg-info text-white rounded-lg shadow-sm">
                        <p class="mb-0 text-sm">Tỷ lệ đi học</p>
                        <h5 class="mb-0 text-2xl font-bold">{{ number_format($attendanceRate, 2) }}%</h5>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="p-3 bg-secondary text-white rounded-lg shadow-sm">
                        <p class="mb-0 text-sm">Tỷ lệ nghỉ học (vắng mặt + có phép + đi muộn)</p>
                        <h5 class="mb-0 text-2xl font-bold">{{ number_format($absenceRate, 2) }}%</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê chi tiết từng học viên -->
        <div class="mt-5">
            <h4 class="text-xl font-semibold mb-3 text-gray-700">Thống kê chi tiết từng học viên</h4>
            @if (count($individualStudentStats) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle rounded-lg overflow-hidden">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">#</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Mã học viên</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Tên học viên</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Tổng số buổi</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Có mặt</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Vắng mặt</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Có phép</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Đi muộn</th>
                            <th class="py-3 px-4 text-sm font-medium text-gray-700">Tỷ lệ đi học</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($individualStudentStats as $index => $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $stat['ma_hoc_vien'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $stat['ten_hoc_vien'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $stat['total_sessions'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $stat['co_mat'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $stat['vang_mat'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $stat['co_phep'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $stat['di_muon'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ number_format($stat['attendance_rate'], 2) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center text-gray-600">Không có dữ liệu điểm danh nào phù hợp với các bộ lọc đã chọn.</p>
            @endif
        </div>
    </div>
</div>

<script>
    // Khởi tạo Flatpickr cho các trường ngày
    flatpickr("#start_date", {
        dateFormat: "Y-m-d",
        locale: "vn", // Sử dụng ngôn ngữ tiếng Việt
        allowInput: true, // Cho phép nhập liệu trực tiếp
    });

    flatpickr("#end_date", {
        dateFormat: "Y-m-d",
        locale: "vn", // Sử dụng ngôn ngữ tiếng Việt
        allowInput: true, // Cho phép nhập liệu trực tiếp
    });
</script>

@endsection