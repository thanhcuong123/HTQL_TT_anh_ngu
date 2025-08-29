@extends('teacher.teacher_index') {{-- Đảm bảo bạn đang extend layout chính của giáo viên --}}

@section('title-content')
<title>Chi Tiết Lớp: {{ $lophoc->tenlophoc ?? 'N/A' }}</title>
@endsection

@section('teacher-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .class-detail-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .class-detail-card h3 {
        color: #007bff;
        margin-bottom: 20px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .class-detail-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .info-section {
        margin-bottom: 25px;
    }

    .info-section h4 {
        color: #333;
        margin-bottom: 15px;
        font-size: 1.5em;
        display: flex;
        align-items: center;
    }

    .info-section h4 i {
        margin-right: 10px;
        font-size: 0.9em;
        color: #007bff;
    }

    .info-item {
        display: flex;
        margin-bottom: 10px;
        font-size: 1.1em;
        color: #555;
    }

    .info-item strong {
        width: 150px;
        /* Cố định chiều rộng cho label */
        flex-shrink: 0;
        color: #333;
    }

    .info-item span {
        flex-grow: 1;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.95em;
        margin-left: 10px;
    }

    .status-dang_hoat_dong {
        background-color: #d4edda;
        color: #155724;
    }

    .status-sap_khai_giang {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-da_ket_thuc {
        background-color: #e2e3e5;
        color: #6c757d;
    }

    .status-da_huy {
        background-color: #f8d7da;
        color: #721c24;
    }

    .schedule-table-container {
        margin-top: 30px;
    }

    .schedule-table-container h4 {
        color: #333;
        margin-bottom: 15px;
        font-size: 1.5em;
        display: flex;
        align-items: center;
    }

    .schedule-table-container h4 i {
        margin-right: 10px;
        font-size: 0.9em;
        color: #007bff;
    }

    .schedule-table,
    .student-table {
        /* Added .student-table */
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .schedule-table th,
    .schedule-table td,
    .student-table th,
    /* Added .student-table */
    .student-table td {
        /* Added .student-table */
        border: 1px solid #dee2e6;
        padding: 12px 15px;
        text-align: left;
    }

    .schedule-table th,
    .student-table th {
        /* Added .student-table */
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
    }

    .schedule-table tbody tr:nth-child(even),
    .student-table tbody tr:nth-child(even) {
        /* Added .student-table */
        background-color: #f2f2f2;
    }

    .schedule-table tbody tr:hover,
    .student-table tbody tr:hover {
        /* Added .student-table */
        background-color: #e9ecef;
    }

    .no-schedule-message {
        background-color: #f0f8ff;
        color: #0056b3;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        font-size: 1.1em;
        border: 1px solid #cce5ff;
    }



    .student-list-section {
        margin-top: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .student-list-section h4 {
        color: #333;
        margin-bottom: 15px;
        font-size: 1.5em;
        display: flex;
        align-items: center;
    }

    .student-list-section h4 i {
        margin-right: 10px;
        font-size: 0.9em;
        color: #007bff;
    }

    /* Removed .student-list ul/li styles as they are replaced by table */

    /* Custom styles for tabs */
    .nav-tabs .nav-link {
        color: #007bff;
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        margin-bottom: -1px;
        font-weight: bold;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        background-color: #f8f9fa;
    }

    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        border-bottom-color: transparent;
    }

    .tab-content {
        padding: 20px;
        border: 1px solid #dee2e6;
        border-top: none;
        border-bottom-left-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
        background-color: #fff;
    }
</style>

<div class="class-detail-card">
    <div class="mb-4">
        <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Quay lại danh sách lớp</a>
    </div>
    <h3>
        <i class="fas fa-chalkboard-teacher"></i>
        Chi Tiết Lớp Học: {{ $lophoc->malophoc ?? 'N/A' }} - {{ $lophoc->tenlophoc ?? 'N/A' }}
        <!-- ?\  <span class="status-badge status-{{ $lophoc->trangthai ?? '' }}">{{ str_replace('_', ' ', ucfirst($lophoc->trangthai ?? 'N/A')) }}</span> -->
    </h3>

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs mb-3" id="classDetailTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-info-tab" data-bs-toggle="tab" data-bs-target="#general-info" type="button" role="tab" aria-controls="general-info" aria-selected="true">Thông tin chung</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab" aria-controls="schedule" aria-selected="false">Lịch dạy</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">Học viên trong lớp</button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="classDetailTabsContent">
        {{-- Tab Pane: Thông tin chung --}}
        <div class="tab-pane fade show active" id="general-info" role="tabpanel" aria-labelledby="general-info-tab">
            <div class="info-section">
                <h4><i class="fas fa-info-circle"></i> Thông tin chung</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong>Mã lớp:</strong> <span>{{ $lophoc->malophoc ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Tên lớp:</strong> <span>{{ $lophoc->tenlophoc ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Khóa học:</strong> <span>{{ $lophoc->khoahoc->ma ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Trình độ:</strong> <span>{{ $lophoc->trinhdo->ten ?? 'N/A' }}</span>
                        </div>
                        <!-- <div class="info-item">
                            <strong>Giáo viên:</strong> <span>{{ $lophoc->giaovien->ten ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Phòng học chính:</strong> <span>{{ $lophoc->phonghoc->tenphong ?? 'N/A' }}</span>
                        </div> -->
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong>Ngày bắt đầu:</strong> <span>{{ \Carbon\Carbon::parse($lophoc->ngaybatdau)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Ngày kết thúc:</strong> <span>{{ \Carbon\Carbon::parse($lophoc->ngayketthuc)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Sĩ số tối đa:</strong> <span>{{ $lophoc->soluonghocvientoida ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Sĩ số hiện tại:</strong> <span>{{ $lophoc->soluonghocvienhientai ?? 'N/A' }}</span>
                        </div>
                        <!-- <div class="info-item">
                            @php
                            // Lấy namhoc_id của lớp (nếu có), hoặc của khoá học
                            $namhocId = optional($lophoc->khoahoc)->namhoc_id;
                            $dongia = $lophoc->trinhdo->dongias->where('namhoc_id', $namhocId)->first();
                            @endphp

                            <strong>Học phí:</strong>
                            <span>{{ number_format(optional($dongia)->hocphi ?? 0, 0, ',', '.') }} VNĐ</span>

                        </div> -->
                        <!-- <div class="info-item">
                            <strong>Mô tả:</strong> <span>{{ $lophoc->mota ?? 'N/A' }}</span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Pane: Lịch dạy --}}
        <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
            <div class="schedule-table-container">
                <h4><i class="fas fa-calendar-alt"></i> Lịch dạy</h4>
                @php
                // Sắp xếp thời khóa biểu theo thứ tự ngày trong tuần và ca học
                $sortedSchedule = $lophoc->thoikhoabieus->sortBy(function($item) {
                return ($item->thu->thutu ?? 99) . '_' . ($item->cahoc->thoigianbatdau ?? '99:99');
                });
                @endphp

                @if($sortedSchedule->isEmpty())
                <div class="no-schedule-message">
                    <p>Lớp học này chưa có lịch dạy nào được thiết lập.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>Thứ</th>
                                <th>Ca học</th>
                                <th>Tên phòng</th>
                                <th>Thời gian</th>
                                <th>Kỹ năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sortedSchedule as $scheduleItem)
                            <tr>
                                <td>{{ $scheduleItem->thu->tenthu ?? 'N/A' }}</td>
                                <td>{{ $scheduleItem->cahoc->tenca ?? 'N/A' }}</td>
                                <td>{{ $scheduleItem->phonghoc->tenphong ?? 'N/A' }} - {{ $scheduleItem->phonghoc->tang->nhahoc->ma ?? 'N/A' }}</td>
                                <td>{{ $scheduleItem->cahoc->thoigianbatdau ?? 'N/A' }} - {{ $scheduleItem->cahoc->thoigianketthuc ?? 'N/A' }}</td>
                                <td>{{ $scheduleItem->kynang->ten ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Tab Pane: Học viên trong lớp (Updated to table format) --}}
        <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
            <div class="student-list-section">
                <h4><i class="fas fa-users"></i> Học viên trong lớp ({{ $lophoc->hocviens->count() ?? 0 }} / {{ $lophoc->soluonghocvientoida ?? 'N/A' }})</h4>
                @if($lophoc->hocviens->isEmpty())
                <p class="text-muted text-center">Chưa có học viên nào trong lớp này.</p>
                @else
                <div class="table-responsive">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th>Mã học viên</th>
                                <th>Họ tên</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lophoc->hocviens as $student)
                            <tr>
                                <td>{{ $student->mahocvien ?? 'N/A' }}</td>
                                <td>{{ $student->ten ?? 'N/A' }}</td>
                                <td>{{ $student->sdt ?? 'N/A' }}</td>
                                <td>{{ $student->email_hv ?? 'N/A' }}</td> {{-- Assuming HocVien has a 'user' relationship --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Lưu ý: Bootstrap 5 tabs yêu cầu Bootstrap JS bundle.
      Đảm bảo bạn đã nhúng nó trong teacher.index.blade.php hoặc layout chính của bạn.
--}}

@endsection