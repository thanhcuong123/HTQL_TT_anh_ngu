@extends('teacher.teacher_index') {{-- Đảm bảo bạn đang extend layout chính của giáo viên --}}

@section('title-content')
<title>Báo Cáo Điểm Danh</title>
@endsection

@section('teacher-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .report-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .report-card h3 {
        color: #007bff;
        margin-bottom: 20px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .report-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .info-box {
        background-color: #e9f7ff;
        border: 1px solid #cce5ff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        font-size: 1.1em;
        color: #0056b3;
    }

    .info-box p {
        margin-bottom: 5px;
    }

    .info-box p strong {
        color: #003f80;
    }

    .attendance-report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .attendance-report-table th,
    .attendance-report-table td {
        border: 1px solid #dee2e6;
        padding: 12px 15px;
        text-align: left;
        vertical-align: middle;
    }

    .attendance-report-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
    }

    .attendance-report-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .attendance-report-table tbody tr:hover {
        background-color: #e9ecef;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 0.9em;
        text-transform: capitalize;
    }

    .status-co_mat {
        background-color: #d4edda;
        color: #155724;
    }

    .status-vang_mat {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-co_phep {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-di_muon {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .btn-back {
        background-color: #6c757d;
        color: #fff;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }

    .summary-section {
        margin-top: 30px;
        padding: 20px;
        background-color: #f0f8ff;
        border: 1px solid #cce5ff;
        border-radius: 8px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .summary-item {
        background-color: #e0f2f7;
        padding: 10px 15px;
        border-radius: 5px;
        text-align: center;
        font-size: 1.1em;
        font-weight: bold;
        color: #0056b3;
    }

    .summary-item .count {
        font-size: 1.5em;
        color: #007bff;
    }

    .summary-item.total-students {
        background-color: #d4edda;
        color: #155724;
    }
</style>

<div class="report-card">
    <div class="mb-4">
        <a href="{{ route('teacher.attendance.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách điểm danh
        </a>
    </div>

    <h3><i class="fas fa-chart-bar"></i> Báo Cáo Điểm Danh Chi Tiết</h3>

    <div class="info-box">
        <p><i class="fas fa-chalkboard-teacher"></i> Giáo viên: <strong>{{ $thoikhoabieu->giaovien->ten ?? 'N/A' }}</strong></p>
        <p><i class="fas fa-users"></i> Lớp: <strong>{{ $lophoc->malophoc ?? 'N/A' }} - {{ $lophoc->tenlophoc ?? 'N/A' }}</strong></p>
        <p><i class="fas fa-calendar-day"></i> Ngày: <strong>{{ $ngayDiemDanh->format('d/m/Y') }} ({{ $thoikhoabieu->thu->tenthu ?? 'N/A' }})</strong></p>
        <p><i class="fas fa-clock"></i> Ca học: <strong>{{ $thoikhoabieu->cahoc->tenca ?? 'N/A' }} ({{ $thoikhoabieu->cahoc->thoigianbatdau ?? 'N/A' }} - {{ $thoikhoabieu->cahoc->thoigianketthuc ?? 'N/A' }})</strong></p>
        <p><i class="fas fa-building"></i> Phòng học: <strong>{{ $thoikhoabieu->phonghoc->tenphong ?? 'N/A' }}</strong></p>
    </div>

    <div class="summary-section">
        <div class="summary-item total-students">
            Tổng số học viên: <span class="count">{{ $summary['total_students_in_class'] }}</span>
        </div>
        <div class="summary-item">
            Có mặt: <span class="count">{{ $summary['co_mat'] }}</span>
        </div>
        <div class="summary-item">
            Vắng mặt: <span class="count">{{ $summary['vang_mat'] }}</span>
        </div>
        <div class="summary-item">
            Có phép: <span class="count">{{ $summary['co_phep'] }}</span>
        </div>
        <div class="summary-item">
            Đi muộn: <span class="count">{{ $summary['di_muon'] }}</span>
        </div>
        <div class="summary-item">
            Đã điểm danh: <span class="count">{{ $summary['total_recorded'] }}</span>
        </div>
    </div>

    @if($attendanceRecords->isEmpty())
    <p class="text-center text-muted mt-4">Chưa có dữ liệu điểm danh cho buổi học này.</p>
    @else
    <div class="table-responsive">
        <table class="attendance-report-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã học viên</th>
                    <th>Họ tên học viên</th>
                    <th>Trạng thái</th>
                    <th>Thời gian điểm danh</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceRecords as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $record->hocvien->mahocvien ?? 'N/A' }}</td>
                    <td>{{ $record->hocvien->ten ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ $record->trangthaidiemdanh }}">
                            @switch($record->trangthaidiemdanh)
                            @case('co_mat') Có mặt @break
                            @case('vang_mat') Vắng mặt @break
                            @case('co_phep') Có phép @break
                            @case('di_muon') Đi muộn @break
                            @default N/A
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $record->thoigiandiemdanh ? \Carbon\Carbon::parse($record->thoigiandiemdanh)->format('H:i') : 'N/A' }}</td>
                    <td>{{ $record->ghichu ?? 'Không có' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection