@extends('index')

@section('title-content')
<title>Lịch Tổng Quan</title>
@endsection

@section('main-content')

{{-- CHỈ NHÚNG MỘT PHIÊN BẢN CỦA FULLCALENDAR JS --}}
{{-- index.global.min.js là đủ cho hầu hết các trường hợp và bao gồm tất cả các ngôn ngữ --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
{{-- Dòng này đã được loại bỏ vì index.global.min.js đã bao gồm tất cả các ngôn ngữ --}}
{{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.min.js'></script> --}}

{{-- NHÚNG BOOTSTRAP JS BUNDLE (bao gồm Popper.js và Tooltip) --}}
{{-- Đảm bảo Bootstrap JS được nhúng SAU Bootstrap CSS và TRƯỚC mã JS của bạn --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lịch Tổng Quan Lớp Học</h5>
    </div>
    <div class="card-body">
        <div id='calendar'></div>
    </div>
</div>
<!-- Modal hiển thị chi tiết -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailModalLabel">Chi tiết lịch học </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <p><strong>Lớp:</strong> <span id="modal-class"></span></p>
                <p><strong>Giáo viên:</strong> <span id="modal-teacher"></span></p>
                <p><strong>Phòng:</strong> <span id="modal-room"></span></p>
                <p><strong>Nhà học:</strong> <span id="modal-nhahoc"></span></p>
                <p><strong>Kỹ năng:</strong> <span id="modal-skill"></span></p>
                <p><strong>Ca học:</strong> <span id="modal-cahoc"></span></p>
                <p><strong>Thứ:</strong> <span id="modal-thu"></span></p>
            </div>
            <div class="modal-footer">
                <a id="modal-detail-link" href="#" class="btn btn-primary">Xem chi tiết</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Chế độ xem mặc định: Tháng
            locale: 'vi', // Ngôn ngữ tiếng Việt (được hỗ trợ bởi index.global.min.js)
            height: 'auto', // Chiều cao tự động
            expandRows: true, // Mở rộng các hàng để lấp đầy không gian
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay' // Các chế độ xem khác
            },
            events: {
                url: '{{ route("calendar.events") }}', // API endpoint để lấy dữ liệu sự kiện
                failure: function(error) { // Thêm tham số error để debug chi tiết hơn
                    console.error('Lỗi khi tải sự kiện lịch:', error);
                    alert('Có lỗi xảy ra khi tải sự kiện lịch. Vui lòng kiểm tra console để biết chi tiết.');
                }
            },

            eventDidMount: function(info) {
                // Tạo tooltip khi hover qua sự kiện
                // Đảm bảo bạn đã nhúng Bootstrap JS Bundle (bao gồm Popper.js và Tooltip)
                new bootstrap.Tooltip(info.el, {
                    title: `
                        <strong>Lớp:</strong> ${info.event.extendedProps.classCode} - ${info.event.extendedProps.className}<br>
                        <strong>GV:</strong> ${info.event.extendedProps.teacher}<br>
                        <strong>Phòng:</strong> ${info.event.extendedProps.room} 
                        -${info.event.extendedProps.nhaHoc}<br>
                        <strong>Kỹ năng:</strong> ${info.event.extendedProps.skill}<br>
                        <strong>Ca học:</strong> ${info.event.extendedProps.caHoc}<br>
                        <strong>Thứ:</strong> ${info.event.extendedProps.thu}
                    `,
                    placement: 'top',
                    trigger: 'hover',
                    html: true, // Cho phép HTML trong tooltip
                    container: 'body'
                });
            },
            // eventClick: function(info) {
            //     // Xử lý khi click vào một sự kiện
            //     alert('Chi tiết sự kiện:\n' +
            //         'Lớp: ' + info.event.extendedProps.classCode + ' - ' + info.event.extendedProps.className + '\n' +
            //         'Giáo viên: ' + info.event.extendedProps.teacher + '\n' +
            //         'Phòng: ' + info.event.extendedProps.room + '\n' +
            //         'Kỹ năng: ' + info.event.extendedProps.skill + '\n' +
            //         'Ca học: ' + info.event.extendedProps.caHoc + '\n' +
            //         'Thứ: ' + info.event.extendedProps.thu);
            //     // Bạn có thể thay thế alert bằng modal chi tiết hơn
            // }
            // Thêm các tùy chỉnh khác của FullCalendar tại đây

            eventClick: function(info) {
                info.jsEvent.preventDefault(); // Chặn mở link mặc định

                // Tiêu đề động
                document.getElementById('eventDetailModalLabel').textContent = `Chi tiết lịch học lớp: ${info.event.extendedProps.className}`;

                // Dữ liệu chi tiết
                document.getElementById('modal-class').textContent = `${info.event.extendedProps.classCode} - ${info.event.extendedProps.className}`;
                document.getElementById('modal-teacher').textContent = info.event.extendedProps.teacher;
                document.getElementById('modal-room').textContent = info.event.extendedProps.room;
                document.getElementById('modal-nhahoc').textContent = info.event.extendedProps.nhaHoc;
                document.getElementById('modal-skill').textContent = info.event.extendedProps.skill;

                // Ca học: tên ca + giờ bắt đầu - kết thúc
                const caHocText = `${info.event.extendedProps.caHoc} (${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})})`;
                document.getElementById('modal-cahoc').textContent = caHocText;

                document.getElementById('modal-thu').textContent = info.event.extendedProps.thu;

                // Gán link chi tiết
                document.getElementById('modal-detail-link').href = info.event.url;

                // Show modal
                var eventModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                eventModal.show();
            },


        });
        calendar.render();

    });
</script>

@endsection