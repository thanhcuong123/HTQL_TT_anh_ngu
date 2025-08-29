@extends('teacher.teacher_index') {{-- Đảm bảo bạn đang extend layout chính của giáo viên --}}

@section('title-content')
<title>Điểm Danh Lớp: {{ $lophoc->tenlophoc }}</title>
@endsection

@section('teacher-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .attendance-form-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .attendance-form-card h3 {
        color: #007bff;
        margin-bottom: 20px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .attendance-form-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .class-info-box {
        background-color: #e9f7ff;
        border: 1px solid #cce5ff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        font-size: 1.1em;
        color: #0056b3;
    }

    .class-info-box p {
        margin-bottom: 5px;
    }

    .class-info-box p strong {
        color: #003f80;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .attendance-table th,
    .attendance-table td {
        border: 1px solid #dee2e6;
        padding: 12px 15px;
        text-align: left;
        vertical-align: middle;
    }

    .attendance-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
    }

    .attendance-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .attendance-table tbody tr:hover {
        background-color: #e9ecef;
    }

    .attendance-status-options label {
        margin-right: 15px;
        font-weight: normal;
        cursor: pointer;
    }

    .attendance-status-options input[type="radio"] {
        margin-right: 5px;
    }

    .note-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        resize: vertical;
        /* Cho phép thay đổi kích thước theo chiều dọc */
        min-height: 40px;
    }

    .btn-submit-attendance {
        background-color: #007bff;
        color: #fff;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
    }

    .btn-submit-attendance:hover {
        background-color: #0056b3;
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

    .alert-message {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 1em;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="attendance-form-card">
    <div class="mb-4">
        <a href="{{ route('teacher.attendance.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <h3><i class="fas fa-clipboard-check"></i> Điểm Danh Lớp: {{ $lophoc->malophoc }} - {{ $lophoc->tenlophoc }}</h3>

    @if (session('success'))
    <div class="alert-message alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert-message alert-danger">{{ session('error') }}</div>
    @endif

    <div class="class-info-box">
        <p><i class="fas fa-calendar-alt"></i> Ngày điểm danh: <strong>{{ \Carbon\Carbon::parse($ngayDiemDanhString)->format('d/m/Y') }}</strong></p>
        <p><i class="fas fa-clock"></i> Ca học: <strong>{{ $thoikhoabieu->cahoc->tenca ?? 'N/A' }} ({{ $thoikhoabieu->cahoc->thoigianbatdau ?? 'N/A' }} - {{ $thoikhoabieu->cahoc->thoigianketthuc ?? 'N/A' }})</strong></p>
        <p><i class="fas fa-building"></i> Phòng: <strong>{{ $thoikhoabieu->phonghoc->tenphong ?? 'N/A' }}</strong></p>
        <p><i class="fas fa-chalkboard-teacher"></i> Giáo viên: <strong>{{ $giaoVien->ten ?? 'N/A' }}</strong></p>
    </div>

    <form action="{{ route('teacher.attendance.store', ['lophoc' => $lophoc->id, 'thoikhoabieu' => $thoikhoabieu->id]) }}" method="POST">
        @csrf
        {{-- Hidden fields for date and time --}}
        <input type="hidden" name="ngay_diem_danh" value="{{ $ngayDiemDanhString     }}">
        <input type="hidden" name="thoi_gian_diem_danh" value="{{ $thoiGianDiemDanh }}">

        <div class="table-responsive">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã học viên</th>
                        <th>Họ tên học viên</th>
                        <th>Trạng thái điểm danh</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                    @php
                    $currentStatus = optional($existingAttendance->get($student->id))->trangthaidiemdanh;
                    $currentNote = optional($existingAttendance->get($student->id))->ghichu;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->mahocvien ?? 'N/A' }}</td>
                        <td>{{ $student->ten ?? 'N/A' }}</td>
                        <td class="attendance-status-options">
                            <label>
                                <input type="radio" name="attendance_status[{{ $student->id }}]" value="co_mat" {{ $currentStatus == 'co_mat' ? 'checked' : '' }} required> Có mặt
                            </label>
                            <label>
                                <input type="radio" name="attendance_status[{{ $student->id }}]" value="vang_mat" {{ $currentStatus == 'vang_mat' ? 'checked' : '' }} required> Vắng mặt
                            </label>
                            <label>
                                <input type="radio" name="attendance_status[{{ $student->id }}]" value="co_phep" {{ $currentStatus == 'co_phep' ? 'checked' : '' }} required> Có phép
                            </label>
                            <label>
                                <input type="radio" name="attendance_status[{{ $student->id }}]" value="di_muon" {{ $currentStatus == 'di_muon' ? 'checked' : '' }} required> Đi muộn
                            </label>
                            @error('attendance_status.' . $student->id)
                            <div class="alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <textarea name="note[{{ $student->id }}]" class="note-input" placeholder="Ghi chú (nếu có)">{{ old('note.' . $student->id, $currentNote) }}</textarea>
                            @error('note.' . $student->id)
                            <div class="alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Không có học viên nào trong lớp này.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-10 text-left">
            <button type="submit" class="btn-submit-attendance inline-flex items-center">
                <i class="fas fa-save mr-2"></i> Lưu Điểm Danh
            </button>
        </div>
    </form>
</div>

@endsection