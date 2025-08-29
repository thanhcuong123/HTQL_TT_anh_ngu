@extends('student.student_index') {{-- Đảm bảo bạn đang extend layout chính của mình --}}

@section('title-content')
<title>Lớp Học Của Tôi</title>
@endsection

@section('student-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .class-card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        padding: 20px 0;
    }

    .class-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        /* Đảm bảo các card có chiều cao bằng nhau */
    }

    .card-body h3 {
        color: #333;
        margin-bottom: 15px;
        font-size: 1.8em;
    }

    .class-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .class-card h4 {
        color: #007bff;
        margin-bottom: 10px;
        font-size: 1.6em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
    }

    .class-card p {
        font-size: 1.05em;
        color: #555;
        margin-bottom: 8px;
    }

    .class-card p strong {
        color: #333;
    }

    .class-card .status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9em;
        margin-top: 10px;
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

    .class-card .card-footer {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        text-align: right;
    }

    .class-card .card-footer a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.2s ease;
    }

    .class-card .card-footer a:hover {
        color: #0056b3;
    }

    .no-classes-message {
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

    .no-classes-message i {
        font-size: 2em;
        margin-bottom: 15px;
        color: #007bff;
    }
</style>

<div class="card">
    <div class="card-body">
        <h3>Các Lớp Học Của Tôi</h3>

        @if($enrolledClasses->isEmpty())
        <div class="no-classes-message">
            <i class="fas fa-exclamation-circle"></i>
            <p>Bạn hiện chưa đăng ký lớp học nào.</p>
            <p>Hãy liên hệ với trung tâm để tìm khóa học phù hợp nhé!</p>
        </div>
        @else
        <div class="class-card-container">
            @foreach($enrolledClasses as $class)
            <div class="class-card">
                <div>
                    <h4>{{ $class->malophoc ?? 'N/A' }} - {{ $class->tenlophoc ?? 'N/A' }}</h4>
                    <p><i class="fas fa-book-open"></i> Khóa học: <strong>{{ $class->khoahoc->ma ?? 'N/A' }}</strong></p>
                    <p><i class="fas fa-graduation-cap"></i> Trình độ: <strong>{{ $class->trinhdo->ten ?? 'N/A' }}</strong></p>
                    <p><i class="fas fa-calendar-alt"></i> Ngày bắt đầu: <strong>{{ \Carbon\Carbon::parse($class->ngaybatdau)->format('d/m/Y') }}</strong></p>
                    <p><i class="fas fa-calendar-check"></i> Ngày kết thúc: <strong>{{ \Carbon\Carbon::parse($class->ngayketthuc)->format('d/m/Y') }}</strong></p>
                    <p><i class="fas fa-users"></i> Sĩ số: <strong>{{ $class->hocviens->count() ?? 0 }} / {{ $class->soluonghocvientoida ?? 'N/A' }}</strong></p>
                    <span class="status status-{{ $class->trangthai }}">{{ str_replace('_', ' ', ucfirst($class->trangthai)) }}</span>
                </div>
                <div class="card-footer">
                    <a href="{{ route('student.lophoc.show', $class->id) }}">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection