@extends('student.student_index') {{-- Đảm bảo bạn đang extend layout chính của mình --}}

@section('title-content')
<title>Tổng Quan Học Viên</title>
@endsection

@section('student-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .student-dashboard-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .student-info h3 {
        color: #333;
        margin-bottom: 15px;
        font-size: 1.8em;
    }

    .student-info p {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 8px;
    }

    .schedule-section {
        margin-top: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .schedule-section h4 {
        color: #007bff;
        margin-bottom: 20px;
        font-size: 1.5em;
        display: flex;
        align-items: center;
    }

    .schedule-section h4 i {
        margin-right: 10px;
        font-size: 1.2em;
    }

    .no-schedule {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        font-size: 1.1em;
        border: 1px solid #f5c6cb;
    }

    .schedule-item {
        background-color: #e9f7ff;
        border-left: 5px solid #007bff;
        padding: 15px 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
    }

    .schedule-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .schedule-item h5 {
        color: #0056b3;
        margin-bottom: 5px;
        font-size: 1.3em;
    }

    .schedule-item p {
        margin-bottom: 3px;
        color: #444;
    }

    .schedule-item p strong {
        color: #222;
    }

    .schedule-item .time {
        font-weight: bold;
        color: #28a745;
        font-size: 1.1em;
    }
</style>

<div class="student-dashboard-card">
    <div class="student-info">
        <h3>Chào mừng, {{ $hocvien->ten ?? 'Học viên' }}!</h3>
        <p><i class="fas fa-id-card"></i> Mã học viên: <strong>{{ $hocvien->mahocvien ?? 'N/A' }}</strong></p>
        <p><i class="fas fa-envelope"></i> Email: <strong>{{ $hocvien->user->email   ?? 'N/A' }}</strong></p>
        <p><i class="fas fa-phone"></i> Điện thoại: <strong>{{ $hocvien->sdt ?? 'N/A' }}</strong></p>
    </div>

    <div class="schedule-section">
        <h4><i class="fas fa-calendar-day"></i> Lịch học hôm nay ({{ \Carbon\Carbon::parse($today)->format('d/m/Y') }})</h4>

        @if($scheduleToday->isEmpty())
        <div class="no-schedule">
            <p>Hôm nay ({{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}) bạn không có lịch học nào.</p>
            <p>Hãy tận hưởng thời gian rảnh rỗi hoặc ôn tập bài cũ nhé!</p>
        </div>
        @else
        @foreach($scheduleToday as $item)
        <div class="schedule-item">
            <h5>Lớp: {{ $item->lophoc->malophoc ?? 'N/A' }} - {{ $item->lophoc->tenlophoc ?? 'N/A' }}</h5>
            <p class="time"><i class="fas fa-clock"></i> Ca học: {{ $item->cahoc->tenca ?? 'N/A' }} ({{ $item->cahoc->thoigianbatdau ?? 'N/A' }} - {{ $item->cahoc->thoigianketthuc ?? 'N/A' }})</p>
            <p><i class="fas fa-chalkboard-teacher"></i> Giáo viên: <strong>{{ $item->giaovien->ten ?? 'N/A' }}</strong></p>
            <p><i class="fas fa-building"></i> Phòng học: <strong>{{ $item->phonghoc->tenphong ?? 'N/A' }}</strong></p>
            <p><i class="fas fa-book"></i> Kỹ năng: <strong>{{ $item->kynang->ten ?? 'N/A' }}</strong></p>
        </div>
        @endforeach
        @endif
    </div>
</div>

@endsection