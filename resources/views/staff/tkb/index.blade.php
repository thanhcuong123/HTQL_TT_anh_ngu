@extends('staff.index')

@section('title-content')
<title>Lịch Tổng Quan</title>
@endsection

@section('staff-content')

{{-- NHÚNG FULLCALENDAR JS --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

{{-- NHÚNG BOOTSTRAP CSS (Nếu chưa có trong staff.index) --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
{{-- NHÚNG BOOTSTRAP JS BUNDLE (bao gồm Popper.js và Tooltip) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- NHÚNG jQuery (Nếu chưa có) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- NHÚNG Flatpickr CSS cho Date Picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- NHÚNG Flatpickr JS cho Date Picker --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script> {{-- Thêm ngôn ngữ tiếng Việt --}}

<style>
    /* Custom CSS để đảm bảo giao diện đẹp và rõ ràng */
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        /* Thêm khoảng cách dưới card */
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .card-header h5 {
        margin-bottom: 0;
        color: #333;
        font-size: 1.5em;
    }

    .btn-add-schedule {
        background-color: #27a9e3;
        border-color: #27a9e3;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-add-schedule:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.5rem;
    }

    .modal-title {
        color: #007bff;
        font-weight: 600;
    }

    .modal-body .form-label {
        font-weight: 500;
        color: #495057;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        padding: 0.75rem 1rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004a99;
    }

    /* FullCalendar adjustments */
    .fc-header-toolbar {
        margin-bottom: 1.5em !important;
        /* Tăng khoảng cách dưới header toolbar */
    }

    .fc-event {
        cursor: pointer;
        padding: 3px 5px;
        border-radius: 4px;
        font-size: 0.85em;
        line-height: 1.3;
        border: 1px solid transparent;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .fc-event:hover {
        opacity: 0.9;
    }
</style>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lịch Tổng Quan Lớp Học</h5>
        <button type="button" class="btn btn-add-schedule" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
            <i class="fas fa-plus"></i> Thêm lịch học
        </button>
    </div>
    <div class="card-body">
        <div id='calendar'></div>
    </div>
</div>

{{-- Modal Thêm Lịch Học --}}
<div class="modal fade" id="createScheduleModal" tabindex="-1" aria-labelledby="createScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header  text-white">
                <h5 class="modal-title" id="createScheduleModalLabel">Tạo Lịch Học Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="scheduleForm">
                    @csrf

                    {{-- Hàng 1: Lớp học + Giáo viên --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="lophoc_id" class="form-label fw-semibold">Lớp học</label>
                            <select class="form-select" id="lophoc_id" name="lophoc_id" required>
                                <option value="">-- Chọn lớp học --</option>
                                @foreach($lophocs as $lophoc)
                                <option value="{{ $lophoc->id }}">{{ $lophoc->tenlophoc }} ({{ $lophoc->malophoc }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="giaovien_id" class="form-label fw-semibold">Giáo viên</label>
                            <select class="form-select" id="giaovien_id" name="giaovien_id" required>
                                <option value="">-- Chọn giáo viên --</option>
                                @foreach($giaoviens as $giaovien)
                                <option value="{{ $giaovien->id }}">{{ $giaovien->ten }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Hàng 2: Phòng + Ca học + Kỹ năng --}}
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label for="phong_id" class="form-label fw-semibold">Phòng học</label>
                            <select class="form-select" id="phong_id" name="phong_id" required>
                                <option value="">-- Chọn phòng --</option>
                                @foreach($phongs as $phong)
                                <option value="{{ $phong->id }}">Phòng {{ $phong->tenphong }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ca_id" class="form-label fw-semibold">Ca học</label>
                            <select class="form-select" id="ca_id" name="ca_id" required>
                                <option value="">-- Chọn ca học --</option>
                                @foreach($cas as $ca)
                                <option value="{{ $ca->id }}">Ca {{ $ca->tenca }} ({{ $ca->thoigianbatdau }} - {{ $ca->thoigianketthuc }})</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- <div class="col-md-4">
                            <label for="kynang_id" class="form-label fw-semibold">Kỹ năng</label>
                            <select class="form-select" id="kynang_id" name="kynang_id" required>
                                <option value="">-- Chọn kỹ năng --</option>
                                @foreach($kynangs as $kynang)
                                <option value="{{ $kynang->id }}">{{ $kynang->ten }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kỹ năng:</label>
                            <div id="kynang-container" class="row">
                                <div class="text-muted">Vui lòng chọn lớp học để hiển thị kỹ năng</div>
                            </div>
                        </div>

                    </div>

                    {{-- Hàng 3: Chọn Thứ --}}
                    <div class="mt-3">
                        <label>Chọn Thứ:</label>
                        <div class="row">
                            @foreach ($allthu as $thu)
                            <div class="col-6">
                                <label class="d-block">
                                    <input type="checkbox" name="thu_ids[]" value="{{ $thu->id }}">
                                    {{ $thu->tenthu }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Thông báo --}}
                    <div id="schedule-message" class="alert mt-3" style="display: none;"></div>

                    {{-- Nút lưu --}}
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Lưu lịch học
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $('#lophoc_id').on('change', function() {
        let lophocId = $(this).val();
        let container = $('#kynang-container');

        container.html('<div class="text-muted">Đang tải kỹ năng...</div>');

        if (!lophocId) {
            container.html('<div class="text-muted">Vui lòng chọn lớp học để hiển thị kỹ năng</div>');
            return;
        }

        $.ajax({
            url: `/staff/lophoc/${lophocId}/kynang`,
            type: 'GET',
            success: function(response) {
                container.empty();

                if (!response.kynangs || response.kynangs.length === 0) {
                    container.html('<div class="text-muted">Không có kỹ năng phù hợp</div>');
                    return;
                }

                let half = Math.ceil(response.kynangs.length / 2);
                let col1 = $('<div class="col-md-6"></div>');
                let col2 = $('<div class="col-md-6"></div>');

                response.kynangs.forEach((kn, index) => {
                    let checkbox = `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="kynang_id[]" id="kynang_${kn.id}" value="${kn.id}">
                        <label class="form-check-label" for="kynang_${kn.id}">${kn.ten}</label>
                    </div>
                `;
                    if (index < half) {
                        col1.append(checkbox);
                    } else {
                        col2.append(checkbox);
                    }
                });

                container.append(col1).append(col2);
            },
            error: function() {
                container.html('<div class="text-danger">Lỗi khi tải kỹ năng</div>');
            }
        });
    });

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
                url: '{{ route("staff.calendar.events") }}', // API endpoint để lấy dữ liệu sự kiện
                failure: function(error) { // Thêm tham số error để debug chi tiết hơn
                    console.error('Lỗi khi tải sự kiện lịch:', error);
                    // alert('Có lỗi xảy ra khi tải sự kiện lịch. Vui lòng kiểm tra console để biết chi tiết.');
                }
            },

            eventDidMount: function(info) {
                // Tạo tooltip khi hover qua sự kiện
                new bootstrap.Tooltip(info.el, {
                    title: `
                        <strong>Lớp:</strong> ${info.event.extendedProps.classCode} - ${info.event.extendedProps.className}<br>
                        <strong>GV:</strong> ${info.event.extendedProps.teacher}<br>
                        <strong>Phòng:</strong> ${info.event.extendedProps.room}<br>
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
        });
        calendar.render();

        // Khởi tạo Flatpickr cho trường ngày học
        flatpickr("#ngay_hoc", {
            dateFormat: "Y-m-d",
            locale: "vn", // Sử dụng ngôn ngữ tiếng Việt
            allowInput: true, // Cho phép nhập liệu trực tiếp
            altInput: true, // Tạo một input thay thế thân thiện hơn
            altFormat: "d/m/Y", // Định dạng hiển thị
        });

        // Xử lý submit form tạo lịch học
        $('#scheduleForm').on('submit', function(e) {
            e.preventDefault(); // Ngăn chặn submit form mặc định

            const formData = $(this).serialize(); // Lấy dữ liệu form
            const messageBox = $('#schedule-message');
            const submitBtn = $(this).find('button[type="submit"]');

            submitBtn.prop('disabled', true).text('Đang lưu...');
            messageBox.hide().removeClass('alert-success alert-danger');

            $.ajax({
                url: "{{ route('staff.tkb.store') }}", // Route để lưu TKB
                method: "POST",
                data: formData,
                success: function(response) {
                    messageBox.addClass('alert-success').text(response.message).show();
                    $('#scheduleForm')[0].reset(); // Reset form
                    calendar.refetchEvents(); // Tải lại sự kiện trên FullCalendar

                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createScheduleModal'));
                        modal.hide(); // Đóng modal
                        messageBox.hide();
                    }, 2000); // Ẩn thông báo và đóng modal sau 2 giây
                },
                error: function(xhr) {
                    let errorMessage = 'Có lỗi xảy ra khi lưu lịch học.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Hiển thị lỗi validation từ Laravel
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    messageBox.addClass('alert-danger').html(errorMessage).show();
                    console.error("Lỗi khi lưu lịch học:", xhr.responseText);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Lưu lịch học');
                }
            });
        });
    });
</script>

@endsection