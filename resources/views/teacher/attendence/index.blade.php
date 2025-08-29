@extends('teacher.teacher_index') {{-- Đảm bảo bạn đang extend layout chính của giáo viên --}}

@section('title-content')
<title>Điểm Danh Học Viên</title>
@endsection

@section('teacher-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .attendance-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .attendance-card h3 {
        color: #007bff;
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .attendance-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .date-filter-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding: 15px;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        flex-wrap: wrap;
        /* Cho phép xuống dòng trên màn hình nhỏ */
    }

    .date-filter-section label {
        font-weight: bold;
        color: #333;
    }

    .date-filter-section input[type="date"] {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1rem;
        flex-grow: 1;
        /* Cho phép input mở rộng */
        max-width: 200px;
        /* Giới hạn chiều rộng */
    }

    .date-filter-section button {
        background-color: #007bff;
        color: #fff;
        padding: 8px 15px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
    }

    .date-filter-section button:hover {
        background-color: #0056b3;
    }

    .schedule-item {
        background-color: #e9f7ff;
        border-left: 5px solid #007bff;
        padding: 15px 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        /* Cho phép xuống dòng trên màn hình nhỏ */
    }

    .schedule-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .schedule-details {
        flex-grow: 1;
        margin-right: 15px;
        /* Khoảng cách với nút */
    }

    .schedule-details h5 {
        color: #0056b3;
        margin-bottom: 5px;
        font-size: 1.3em;
    }

    .schedule-details p {
        margin-bottom: 3px;
        color: #444;
    }

    .schedule-details p strong {
        color: #222;
    }

    .schedule-details .time {
        font-weight: bold;
        color: #28a745;
        font-size: 1.1em;
    }

    .btn-attendance {
        background-color: #28a745;
        /* Green */
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
        white-space: nowrap;
        /* Ngăn không cho nút xuống dòng */
    }

    .btn-attendance:hover {
        background-color: #218838;
    }

    /* New styles for attendance summary */
    .attendance-action-area {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        /* Căn phải các phần tử bên trong */
        gap: 10px;
    }

    .attendance-summary-box {
        background-color: #f0f8ff;
        /* Light blue background */
        border: 1px solid #cce5ff;
        /* Light blue border */
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 0.95em;
        color: #0056b3;
        width: 100%;
        /* Đảm bảo chiếm hết chiều rộng có thể */
        max-width: 250px;
        /* Giới hạn chiều rộng tối đa */
        text-align: left;
    }

    .attendance-summary-box p {
        margin-bottom: 3px;
    }

    .attendance-summary-box p:last-child {
        margin-bottom: 0;
    }

    .attendance-summary-box .fas {
        margin-right: 5px;
    }

    .btn-re-attendance {
        background-color: #ffc107;
        /* Yellow for edit */
        color: #343a40;
    }

    .btn-re-attendance:hover {
        background-color: #e0a800;
    }

    .btn-view-report {
        /* New style for report button */
        background-color: #17a2b8;
        /* Info blue */
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        /* Center icon and text */
        margin-top: 5px;
        /* Add some space if needed */
    }

    .btn-view-report:hover {
        background-color: #138496;
    }

    .no-schedule-message {
        background-color: #f0f8ff;
        color: #0056b3;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        font-size: 1.2em;
        border: 1px solid #cce5ff;
        margin-top: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .no-schedule-message i {
        font-size: 2em;
        margin-bottom: 15px;
        color: #007bff;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .date-filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .date-filter-section input[type="date"] {
            max-width: 100%;
        }

        .schedule-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .schedule-details {
            margin-right: 0;
            margin-bottom: 15px;
            width: 100%;
        }

        .attendance-action-area {
            width: 100%;
            align-items: center;
            /* Căn giữa các phần tử trong cột trên mobile */
        }

        .attendance-summary-box {
            max-width: 100%;
            /* Cho phép chiếm toàn bộ chiều rộng trên mobile */
        }

        .btn-attendance,
        .btn-re-attendance,
        .btn-view-report {
            /* Apply to new button too */
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="attendance-card">
    <h3><i class="fas fa-clipboard-check"></i> Điểm Danh Học Viên</h3>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Phần chọn ngày để lọc --}}
    <div class="date-filter-section">
        <label for="attendance_date">Chọn ngày:</label>
        <input type="date" id="attendance_date" value="{{ $selectedDate->format('Y-m-d') }}">
        <button id="filter_date_btn">Xem</button>
    </div>

    <h4>Lịch dạy ngày {{ $selectedDate->format('d/m/Y') }}</h4>

    @if($scheduleForSelectedDay->isEmpty())
    <div class="no-schedule-message">
        <i class="fas fa-exclamation-circle"></i>
        <p>Ngày {{ $selectedDate->format('d/m/Y') }} bạn không có lịch dạy nào.</p>
        <p>Hãy tận hưởng thời gian rảnh rỗi hoặc chuẩn bị cho các buổi học sắp tới nhé!</p>
    </div>
    @else
    @foreach($scheduleForSelectedDay as $item)
    <div class="schedule-item">
        <div class="schedule-details">
            <h5>Lớp: {{ $item->lophoc->malophoc ?? 'N/A' }} - {{ $item->lophoc->tenlophoc ?? 'N/A' }}</h5>
            <p class="time"><i class="fas fa-clock"></i> Ca dạy: {{ $item->cahoc->tenca ?? 'N/A' }} ({{ $item->cahoc->thoigianbatdau ?? 'N/A' }} - {{ $item->cahoc->thoigianketthuc ?? 'N/A' }})</p>
            <p><i class="fas fa-building"></i> Phòng học: <strong>{{ $item->phonghoc->tenphong ?? 'N/A' }}</strong></p>
            <p><i class="fas fa-book"></i> Kỹ năng: <strong>{{ $item->kynang->ten ?? 'N/A' }}</strong></p>
        </div>
        <div class="attendance-action-area">
            @php
            $summary = $attendanceSummaries[$item->id] ?? null;
            @endphp

            @if($summary && $summary['has_attendance_taken'])
            <div class="attendance-summary-box">
                <p><strong>Đã điểm danh:</strong></p>
                <p><i class="fas fa-check-circle text-green-600"></i> Có mặt: {{ $summary['co_mat'] }}</p>
                <p><i class="fas fa-times-circle text-red-600"></i> Vắng mặt: {{ $summary['vang_mat'] }}</p>
                <p><i class="fas fa-minus-circle text-yellow-600"></i> Có phép: {{ $summary['co_phep'] }}</p>
                <p><i class="fas fa-clock text-blue-600"></i> Đi muộn: {{ $summary['di_muon'] }}</p>
                <p class="mt-2 text-sm text-gray-600">({{ $summary['total_recorded'] }} / {{ $summary['total_students_in_class'] }} học viên)</p>
            </div>
            {{-- Nút Sửa điểm danh --}}
            <a href="{{ route('teacher.attendance.create', [
                        'lophoc' => $item->lophoc->id,
                        'thoikhoabieu' => $item->id,
                        'date' => $selectedDate->toDateString() // Truyền ngày đã chọn
                    ]) }}" class="btn-attendance btn-re-attendance mt-1">
                <i class="fas fa-edit mr-2"></i> Sửa điểm danh
            </a>
            {{-- Nút Xem báo cáo --}}
            <a href="{{ route('teacher.attendance.report', [
                        'lophoc' => $item->lophoc->id,
                        'thoikhoabieu' => $item->id,
                        'ngayDiemDanhString' => $selectedDate->toDateString() // Truyền ngày đã chọn
                    ]) }}" class="btn-view-report mt-1">
                <i class="fas fa-chart-bar mr-2"></i> Xem báo cáo
            </a>
            @else
            <a href="{{ route('teacher.attendance.create', [
                        'lophoc' => $item->lophoc->id,
                        'thoikhoabieu' => $item->id,
                        'date' => $selectedDate->toDateString() // Truyền ngày đã chọn
                    ]) }}" class="btn-attendance">
                <i class="fas fa-user-check mr-2"></i> Điểm danh
            </a>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const attendanceDateInput = document.getElementById('attendance_date');
        const filterDateBtn = document.getElementById('filter_date_btn');

        filterDateBtn.addEventListener('click', function() {
            const selectedDate = attendanceDateInput.value;
            if (selectedDate) {
                // Chuyển hướng đến URL với tham số ngày
                window.location.href = `{{ route('teacher.attendance.index') }}?date=${selectedDate}`;
            } else {
                alert('Vui lòng chọn một ngày để xem điểm danh.');
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy ngày hiện tại (YYYY-MM-DD)
        const todayStr = new Date().toISOString().split('T')[0];

        // Lấy ngày buổi học từ PHP (YYYY-MM-DD)
        const selectedDateStr = "{{ $selectedDate->format('Y-m-d') }}";

        // Nếu không trùng ngày → disable nút
        if (selectedDateStr !== todayStr) {
            document.querySelectorAll('.btn-attendance, .btn-re-attendance').forEach(btn => {
                btn.classList.add('disabled');
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.6';
                btn.title = 'Chỉ có thể điểm danh trong ngày học';
            });
        }
    });
</script>



@endsection