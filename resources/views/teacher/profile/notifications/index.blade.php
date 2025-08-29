@extends('teacher.teacher_index') {{-- Đảm bảo bạn đang extend layout chính của giáo viên --}}

@section('title-content')
<title>Thông Báo Đã Gửi</title>
@endsection

@section('teacher-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .notification-list-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .notification-list-card h3 {
        color: #007bff;
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* Để căn chỉnh nút "Tạo mới" */
    }

    .notification-list-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .btn-create-notification {
        background-color: #28a745;
        /* Green */
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
    }

    .btn-create-notification:hover {
        background-color: #218838;
    }

    .notification-item {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .notification-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .notification-item h4 {
        color: #333;
        font-size: 1.4em;
        margin-bottom: 10px;
    }

    .notification-meta {
        font-size: 0.9em;
        color: #6c757d;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        /* Cho phép xuống dòng trên màn hình nhỏ */
    }

    .notification-meta span {
        display: flex;
        align-items: center;
    }

    .notification-meta i {
        margin-right: 5px;
    }

    .notification-content {
        color: #495057;
        line-height: 1.6;
    }

    .no-notifications-message {
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

    .no-notifications-message i {
        font-size: 2em;
        margin-bottom: 15px;
        color: #007bff;
    }

    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        margin: 0 4px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        text-decoration: none;
        color: #007bff;
        transition: all 0.2s ease;
    }

    .pagination a:hover {
        background-color: #e9ecef;
    }

    .pagination .active span {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
</style>

<div class="notification-list-card">
    <h3>
        <span><i class="fas fa-paper-plane"></i> Thông Báo Đã Gửi</span>
        <a href="{{ route('teacher.notifications.create') }}" class="btn-create-notification">
            <i class="fas fa-plus-circle mr-2"></i> Tạo thông báo mới
        </a>
    </h3>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($notifications->isEmpty())
    <div class="no-notifications-message">
        <i class="fas fa-info-circle"></i>
        <p>Bạn chưa gửi thông báo nào.</p>
    </div>
    @else
    @foreach($notifications as $notification)
    <div class="notification-item">
        <h4>{{ $notification->tieude ?? 'Không có tiêu đề' }}</h4>
        <div class="notification-meta">
            <span><i class="fas fa-calendar-alt"></i> Ngày gửi: {{ $notification->ngaydang ? \Carbon\Carbon::parse($notification->ngaydang)->format('d/m/Y H:i') : 'N/A' }}</span>
            <span><i class="fas fa-check-circle text-green-600"></i> Trạng thái: {{ $notification->trangthai == 'da_gui' ? 'Đã gửi' : 'Nháp' }}</span>

            {{-- Hiển thị đối tượng nhận --}}
            @if($notification->loaidoituongnhan == 'lop_hoc' && $notification->doiTuongNhanLopHoc)
            <span><i class="fas fa-users"></i> Gửi đến lớp: <strong>{{ optional($notification->doiTuongNhanLopHoc)->malophoc ?? 'N/A' }} - {{ optional($notification->doiTuongNhanLopHoc)->tenlophoc ?? 'N/A' }}</strong></span>
            @elseif($notification->loaidoituongnhan == 'hoc_vien_cu_the' && $notification->doiTuongNhanHocVien)
            <span><i class="fas fa-user-graduate"></i> Gửi đến học viên: <strong>{{ optional($notification->doiTuongNhanHocVien)->mahocvien ?? 'N/A' }} - {{ optional($notification->doiTuongNhanHocVien)->ten ?? 'N/A' }}</strong></span>
            @else
            <span><i class="fas fa-globe"></i> Gửi đến: <strong>Tất cả học viên</strong></span>
            @endif
        </div>
        <div class="notification-content">
            <p>{{ $notification->noidung ?? 'Không có nội dung' }}</p>
        </div>
    </div>
    @endforeach

    {{-- Phân trang --}}
    <div class="pagination">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@endsection