@extends('teacher.teacher_index') {{-- Đảm bảo bạn đang extend layout chính của giáo viên --}}

@section('title-content')
<title>Thời Khóa Biểu Của Tôi</title>
@endsection

@section('teacher-content')
{{-- Font Awesome cho icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- FullCalendar CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css">

<style>
    .timetable-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .timetable-card h3 {
        color: #007bff;
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .timetable-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    /* Styles for FullCalendar */
    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }

    .fc-event {
        background-color: #007bff;
        /* Màu nền sự kiện */
        border-color: #007bff;
        /* Màu viền sự kiện */
        color: #fff;
        /* Màu chữ sự kiện */
        font-size: 0.9em;
        padding: 3px 5px;
        border-radius: 4px;
    }

    .fc-event-title {
        font-weight: bold;
    }

    .fc-event-time {
        font-size: 0.85em;
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

    /* Tùy chỉnh tooltip của Bootstrap */
    .tooltip-inner {
        max-width: 300px;
        /* Giới hạn chiều rộng tooltip */
        text-align: left;
        background-color: #343a40;
        /* Màu nền tooltip */
        color: #fff;
        /* Màu chữ tooltip */
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.9em;
    }

    .tooltip.bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before,
    .tooltip.bs-tooltip-top .tooltip-arrow::before {
        border-top-color: #343a40;
        /* Màu mũi tên tooltip */
    }
</style>

<div class="timetable-card">
    <h3><i class="fas fa-calendar-alt"></i> Thời Khóa Biểu Của Tôi</h3>

    {{-- Hiển thị thông báo nếu giáo viên không có hồ sơ hoặc không có lịch dạy --}}
    @if(!$giaoVien || $giaoVien->thoikhoabieus->isEmpty()) {{-- Giả định GiaoVien có mối quan hệ thoikhoabieus --}}
    <div class="no-schedule-message">
        <i class="fas fa-exclamation-circle"></i>
        <p>Bạn hiện chưa có lịch dạy nào được phân công.</p>
        <p>Vui lòng liên hệ quản lý để biết thêm thông tin.</p>
    </div>
    @else
    <div id="calendar"></div>
    @endif
</div>

{{-- FullCalendar JS --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
{{-- NHÚNG BOOTSTRAP JS BUNDLE (bao gồm Popper.js và Tooltip) --}}
{{-- Đảm bảo Bootstrap JS được nhúng SAU Bootstrap CSS và TRƯỚC mã JS của bạn --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');

        // Chỉ khởi tạo lịch nếu có phần tử lịch (tức là giáo viên có lịch dạy)
        if (calendarEl) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', // Chế độ xem mặc định: Tuần với thời gian (phù hợp hơn cho TKB)
                locale: 'vi', // Ngôn ngữ tiếng Việt
                height: 'auto', // Chiều cao tự động
                expandRows: true, // Mở rộng các hàng để lấp đầy không gian
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // Các chế độ xem khác
                },
                events: {
                    url: '{{ route("teacher.timetable.events") }}', // API endpoint để lấy dữ liệu sự kiện
                    method: 'GET', // Phương thức HTTP
                    failure: function(error) {
                        console.error('Lỗi khi tải sự kiện lịch:', error);
                        const errorMessageDiv = document.createElement('div');
                        errorMessageDiv.className = 'alert alert-danger mt-3';
                        errorMessageDiv.textContent = 'Có lỗi xảy ra khi tải sự kiện lịch. Vui lòng thử lại sau.';
                        calendarEl.parentNode.insertBefore(errorMessageDiv, calendarEl.nextSibling);
                    }
                },
                eventDidMount: function(info) {
                    // Tạo tooltip khi hover qua sự kiện
                    new bootstrap.Tooltip(info.el, {
                        title: `
                            <strong>Lớp:</strong> ${info.event.extendedProps.classCode} - ${info.event.extendedProps.className}<br>
                            <strong>Phòng:</strong> ${info.event.extendedProps.room}<br>
                            <strong>Kỹ năng:</strong> ${info.event.extendedProps.skill}<br>
                            <strong>Ca dạy:</strong> ${info.event.extendedProps.caHoc}<br>
                            <strong>Thứ:</strong> ${info.event.extendedProps.thu}
                        `,
                        placement: 'top',
                        trigger: 'hover',
                        html: true, // Cho phép HTML trong tooltip
                        container: 'body' // Đảm bảo tooltip không bị cắt bởi các phần tử cha
                    });
                },
                eventClick: function(info) {
                    // Chuyển hướng khi nhấp vào sự kiện nếu có URL
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault(); // Ngăn chặn hành vi mặc định của link
                    }
                }
            });

            calendar.render();
        }
    });
</script>

@endsection