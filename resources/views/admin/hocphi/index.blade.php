@extends('index')

@section('title-content')
<title>Quản lý học phí</title>
@endsection

@section('main-content')

{{-- Import thư viện cần thiết --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
{{-- Đường dẫn đến CSS tùy chỉnh của bạn --}}
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Nếu bạn muốn hỗ trợ tiếng Việt cho Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
{{-- Đường dẫn đến CSS của template --}}
<link href="{{ asset('admin/luanvantemplate/dist/css/style.min.css') }}" rel="stylesheet">

<style>
    .class-list-panel {
        border-right: 1px solid #e0e0e0;
        padding-right: 15px;
        max-height: calc(100vh - 250px);
        overflow-y: auto;
        padding-bottom: 15px;
    }

    .class-item {
        cursor: pointer;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: all 0.2s ease-in-out;
    }

    .class-item:hover {
        background-color: #f0f0f0;
    }

    .class-item.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .student-list-panel {
        padding-left: 15px;
    }

    .search-filter-container {
        margin-bottom: 20px;
    }

    .student-table-container {
        overflow-x: auto;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .student-table th,
    .student-table td {
        padding: 10px;
        border: 1px solid #e0e0e0;
        text-align: left;
        vertical-align: middle;
    }

    .student-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .student-action-links a {
        margin-right: 5px;
        color: #007bff;
        text-decoration: none;
    }

    .student-action-links a:hover {
        text-decoration: underline;
    }

    .tuition-status-col .badge {
        font-size: 0.85em;
        padding: 0.4em 0.6em;
    }

    .print-receipt-btn {
        margin-left: 10px;
    }

    .hocphi {
        display: flex;
        gap: 50%;
    }
</style>

<div class="card-body">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div id="ajax-message-container" class="mt-3"></div>

    <h3 class="card-title mb-4">Quản lý học phí</h3>

    <div class="row">
        <div class="col-md-5 class-list-panel">
            <div class="search-filter-container">
                <input type="text" id="class-search" class="form-control" placeholder="Tìm kiếm lớp học...">
                <select id="class-filter" class="form-select mt-2">
                    <option value="">Tất cả trạng thái</option>
                    <option value="dang_hoat_dong">Đang hoạt động</option>
                    <option value="da_ket_thuc">Đã kết thúc</option>
                    <option value="sap_khai_giang">Sắp khai giảng</option>
                    <option value="da_huy">Đã hủy</option>
                </select>
            </div>

            <div id="class-list-container">
                @if(isset($classes) && $classes->count() > 0)
                @foreach($classes as $class)
                <div class="class-item" data-class-id="{{ $class->id }}" data-status="{{ $class->trangthai }}">
                    <h5>{{ $class->tenlophoc }}</h5>
                    <p>Mã lớp: {{ $class->malophoc }}</p>
                    <p>Số lượng học viên: {{ $class->hocviens->count() }}</p>
                    <p>Trạng thái: {{ $class->trangthai }}</p>
                </div>
                @endforeach
                @else
                <p>Không tìm thấy lớp học nào.</p>
                @endif
            </div>
        </div>

        <div class="col-md-7 class-list-panel">
            <div class="mb-3">
                <input type="text" id="student-search" class="form-control" placeholder="Tìm kiếm học viên theo mã, tên hoặc SĐT...">
            </div>
            <div class="hocphi">
                <h4>Danh sách học viên trong lớp <span id="selected-class-name"></span></h4>
                <div class="">
                    <button id="send-message-btn" class="btn btn-secondary">Gửi Email nhắc học phí</button>
                </div>
            </div>
            <div class="student-table-container">
                <table class="table table-bordered student-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;"><input type="checkbox" id="select-all-students"></th>
                            <th style="width: 120px;">Mã học viên</th>
                            <th style="width:300px">Thành viên</th>
                            <th style="width: 150px;">Điện thoại</th>
                            <th style="width: 120px;">Nhập học</th>
                            <th style="width: 200px;">Học phí</th>
                        </tr>
                    </thead>
                    <tbody id="student-list-tbody">
                        <tr>
                            <td colspan="6" class="text-center">Vui lòng chọn một lớp học để xem danh sách học viên.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- <div class="mt-3">
                <button id="send-message-btn" class="btn btn-secondary">Gửi Email nhắc học phí</button>
            </div> -->
        </div>
    </div>
</div>

{{-- Modal popup cho thanh toán học phí --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Thu học phí cho học viên <span id="modal-student-name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" id="modal-student-id" name="student_id">
                    <input type="hidden" id="modal-class-id" name="class_id">

                    <div class="mb-3">
                        <label for="modal-total-tuition" class="form-label">Tổng học phí lớp:</label>
                        <input type="text" class="form-control" id="modal-total-tuition" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modal-paid-amount" class="form-label">Số tiền đã đóng:</label>
                        <input type="text" class="form-control" id="modal-paid-amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modal-remaining-amount" class="form-label">Số tiền còn lại:</label>
                        <input type="text" class="form-control" id="modal-remaining-amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amountToPay" class="form-label">Số tiền muốn đóng:</label>
                        <input type="number" class="form-control" id="amountToPay" name="amount" placeholder="Nhập số tiền học viên muốn đóng" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Phương thức thanh toán:</label>
                        <select class="form-select" id="paymentMethod" name="payment_method" required>
                            <option value="">Chọn phương thức</option>
                            <option value="tien_mat">Tiền mặt</option>
                            <option value="chuyen_khoan">Chuyển khoản</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paymentDate" class="form-label">Ngày thanh toán:</label>
                        <input type="text" class="form-control" id="paymentDate" name="payment_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentNote" class="form-label">Ghi chú:</label>
                        <textarea class="form-control" id="paymentNote" name="note" rows="3"></textarea>
                    </div>
                    <div id="payment-message" class="alert alert-info mt-3" style="display: none;"></div>
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Các script của bạn, đặt ở cuối body hoặc trước đóng @endsection --}}
{{-- Nếu bạn có custom JS khác, hãy giữ nguyên đường dẫn này --}}
<script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
<script>
    // Biến toàn cục để lưu trữ danh sách học viên của lớp đang chọn
    let currentStudents = [];
    // Biến để lưu classId của lớp đang được chọn
    let currentClassId = null;

    $(document).ready(function() {
        // Khởi tạo flatpickr cho trường ngày thanh toán
        flatpickr("#paymentDate", {
            dateFormat: "Y-m-d",
            defaultDate: "today",
            locale: "vn" // Sử dụng locale tiếng Việt
        });

        // --- CHỨC NĂNG TÌM KIẾM VÀ LỌC LỚP HỌC ---
        $('#class-search, #class-filter').on('keyup change', function() {
            const searchTerm = $('#class-search').val().toLowerCase();
            const filterStatus = $('#class-filter').val();

            $('.class-item').each(function() {
                const className = $(this).find('h5').text().toLowerCase();
                const classCode = $(this).find('p:contains("Mã lớp")').text().toLowerCase();
                const classStatus = $(this).data('status');

                const matchesSearch = className.includes(searchTerm) || classCode.includes(searchTerm);
                const matchesFilter = (filterStatus === '' || classStatus === filterStatus);

                if (matchesSearch && matchesFilter) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // --- XỬ LÝ CLICK VÀO LỚP HỌC ---
        $(document).on('click', '.class-item', function() {
            $('.class-item').removeClass('active');
            $(this).addClass('active');

            currentClassId = $(this).data('class-id'); // Cập nhật currentClassId
            const className = $(this).find('h5').text();
            loadStudents(currentClassId, className); // Tải học viên cho lớp đã chọn
        });

        // Tải danh sách học viên cho lớp đầu tiên khi trang tải xong (nếu có lớp)
        if ($('.class-item').length > 0) {
            $('.class-item').first().click();
        }

        // --- CHỨC NĂNG TÌM KIẾM HỌC VIÊN (MỚI) ---
        $('#student-search').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            // Gọi hàm displayStudents để lọc và hiển thị dựa trên currentStudents
            displayStudents(currentStudents, currentClassId, searchTerm);
        });

        // --- XỬ LÝ "SELECT ALL" VÀ CHECKBOX HỌC VIÊN ---
        $(document).on('change', '#select-all-students', function() {
            // Chỉ chọn/bỏ chọn các checkbox đang hiển thị
            $('#student-list-tbody tr:visible .student-checkbox').prop('checked', $(this).prop('checked'));
        });

        $(document).on('change', '#student-list-tbody input[type="checkbox"]', function() {
            // Chỉ đếm các checkbox đang hiển thị
            const totalVisibleCheckboxes = $('#student-list-tbody tr:visible .student-checkbox').length;
            const checkedVisibleCheckboxes = $('#student-list-tbody tr:visible .student-checkbox:checked').length;
            const allVisibleChecked = totalVisibleCheckboxes > 0 && totalVisibleCheckboxes === checkedVisibleCheckboxes;
            $('#select-all-students').prop('checked', allVisibleChecked);
        });


        // --- HÀM XỬ LÝ "THU HỌC PHÍ" LINK CLICK ---
        $(document).on('click', '.thu-hoc-phi-btn', function(e) {
            e.preventDefault();
            const studentId = $(this).data('student-id');
            const classId = $(this).data('class-id');
            const studentName = $(this).closest('tr').find('td:nth-child(3)').text(); // Lấy tên từ cột thứ 3

            $('#modal-student-id').val(studentId);
            $('#modal-class-id').val(classId);
            $('#modal-student-name').text(studentName);
            $('#payment-message').hide(); // Ẩn thông báo cũ
            $('#paymentForm')[0].reset(); // Reset form

            // Gửi AJAX request để lấy thông tin học phí
            $.ajax({
                url: `/hocphi/get-tuition-info/${classId}/${studentId}`,
                method: 'GET',
                success: function(response) {
                    console.log("Tuition info response:", response);
                    // Định dạng số tiền
                    $('#modal-total-tuition').val(Math.round(response.total_tuition).toLocaleString('vi-VN') + ' VNĐ');
                    $('#modal-paid-amount').val(Math.round(response.paid_amount).toLocaleString('vi-VN') + ' VNĐ');
                    $('#modal-remaining-amount').val(Math.round(response.remaining_amount).toLocaleString('vi-VN') + ' VNĐ');

                    $('#amountToPay').attr('max', response.remaining_amount);
                    $('#amountToPay').val(response.remaining_amount > 0 ? response.remaining_amount : ''); // Đặt giá trị gợi ý là số tiền còn lại

                    // Hiển thị modal Bootstrap
                    var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                    paymentModal.show();
                },
                error: function(xhr) {
                    let errorMessage = 'Không thể tải thông tin học phí.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    displayAjaxMessage(errorMessage, 'danger'); // Hiển thị lỗi dùng hàm mới
                    console.error('Error fetching tuition info:', xhr.responseText);
                }
            });
        });

        // --- XỬ LÝ GỬI FORM THANH TOÁN ---
        // $('#paymentForm').submit(function(e) {
        //     e.preventDefault();
        //     const studentId = $('#modal-student-id').val();
        //     const classId = $('#modal-class-id').val();
        //     const amount = parseFloat($('#amountToPay').val());
        //     // Lấy số tiền còn lại đã được định dạng và chuyển đổi ngược về số
        //     const remainingText = $('#modal-remaining-amount').val();
        //     const remaining = parseFloat(remainingText.replace(/ VNĐ/g, '').replace(/\./g, '').replace(/,/g, '')); // Dùng replace(/,/g, '') để loại bỏ dấu phân cách hàng nghìn nếu có

        //     if (isNaN(amount) || amount <= 0 || amount > remaining + 0.01) { // Thêm 0.01 để xử lý sai số nhỏ
        //         $('#payment-message').removeClass('alert-success alert-danger').addClass('alert-warning').text('Số tiền đóng không hợp lệ hoặc lớn hơn số tiền còn lại.').show();
        //         return;
        //     }

        //     const formData = $(this).serialize();

        //     $.ajax({
        //         url: `/hocphi/process-payment`,
        //         method: 'POST',
        //         data: formData,
        //         success: function(response) {
        //             console.log("Payment success response:", response);
        //             $('#payment-message').removeClass('alert-warning alert-danger').addClass('alert-success').text(response.message).show();

        //             // Cập nhật trạng thái trực tiếp trong currentStudents và hiển thị lại
        //             const updatedInfo = response.updated_tuition_info;
        //             const studentIndex = currentStudents.findIndex(s => s.id == updatedInfo.student_id); // Dùng == để so sánh số và chuỗi
        //             if (studentIndex > -1) {
        //                 currentStudents[studentIndex].hocphi_status = updatedInfo.hocphi_status;
        //                 currentStudents[studentIndex].hocphi_badge_class = updatedInfo.hocphi_badge_class;
        //                 currentStudents[studentIndex].paid_amount = updatedInfo.paid_amount;
        //                 currentStudents[studentIndex].remaining_amount = updatedInfo.remaining_amount;
        //                 currentStudents[studentIndex].total_tuition = updatedInfo.total_tuition; // Cập nhật cả tổng học phí nếu có thể thay đổi
        //             }

        //             // Tải lại danh sách học viên (chỉ hiển thị lại từ biến `currentStudents`)
        //             // Gọi `displayStudents` với từ khóa tìm kiếm hiện tại để giữ bộ lọc
        //             const currentStudentSearchTerm = $('#student-search').val().toLowerCase();
        //             displayStudents(currentStudents, classId, currentStudentSearchTerm);

        //             // Đóng modal Bootstrap sau khi cập nhật
        //             var paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
        //             if (paymentModal) {
        //                 paymentModal.hide();
        //             }
        //             $('#paymentForm')[0].reset(); // Reset form
        //             $('#payment-message').hide(); // Ẩn thông báo
        //         },
        //         error: function(xhr) {
        //             let errorMessage = 'Đã xảy ra lỗi khi xử lý thanh toán.';
        //             if (xhr.responseJSON && xhr.responseJSON.message) {
        //                 errorMessage = xhr.responseJSON.message;
        //             } else if (xhr.responseText) {
        //                 errorMessage = xhr.responseText;
        //             }
        //             $('#payment-message').removeClass('alert-warning alert-success').addClass('alert-danger').text(errorMessage).show();
        //             console.error('Error processing payment:', xhr.responseText);
        //         }
        //     });
        // });


        // --- XỬ LÝ GỬI FORM THANH TOÁN ---
        $('#paymentForm').submit(function(e) {
            e.preventDefault();
            const studentId = $('#modal-student-id').val();
            const classId = $('#modal-class-id').val();
            const amount = parseFloat($('#amountToPay').val());

            // Lấy số tiền còn lại đã được định dạng và chuyển đổi ngược về số
            // Cần đảm bảo rằng remaining_amount được làm tròn chính xác để tránh sai số dấu phẩy động
            const remainingText = $('#modal-remaining-amount').val();
            const remaining = parseFloat(remainingText.replace(/ VNĐ/g, '').replace(/\./g, '')); // Loại bỏ dấu phân cách hàng nghìn và "VNĐ"

            // Kiểm tra số tiền đóng hợp lệ
            if (isNaN(amount) || amount <= 0 || amount > remaining + 0.01) { // Thêm 0.01 để xử lý sai số nhỏ
                $('#payment-message').removeClass('alert-success alert-danger').addClass('alert-warning').text('Số tiền đóng không hợp lệ hoặc lớn hơn số tiền còn lại.').show();
                return;
            }

            const formData = $(this).serialize();

            $.ajax({
                url: `/hocphi/process-payment`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log("Payment success response:", response);
                    $('#payment-message').removeClass('alert-warning alert-danger').addClass('alert-success').text(response.message).show();

                    // Cập nhật trạng thái trực tiếp trong currentStudents và hiển thị lại
                    const updatedInfo = response.updated_tuition_info;
                    const studentIndex = currentStudents.findIndex(s => s.id == updatedInfo.student_id);
                    if (studentIndex > -1) {
                        currentStudents[studentIndex].hocphi_status = updatedInfo.hocphi_status;
                        currentStudents[studentIndex].hocphi_badge_class = updatedInfo.hocphi_badge_class;
                        currentStudents[studentIndex].paid_amount = updatedInfo.paid_amount;
                        currentStudents[studentIndex].remaining_amount = updatedInfo.remaining_amount;
                        currentStudents[studentIndex].total_tuition = updatedInfo.total_tuition;
                    }

                    // Tải lại danh sách học viên (chỉ hiển thị lại từ biến `currentStudents`)
                    const currentStudentSearchTerm = $('#student-search').val().toLowerCase();
                    displayStudents(currentStudents, classId, currentStudentSearchTerm);

                    // Đóng modal Bootstrap sau khi cập nhật
                    var paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                    if (paymentModal) {
                        paymentModal.hide();
                    }
                    $('#paymentForm')[0].reset(); // Reset form
                    $('#payment-message').hide(); // Ẩn thông báo
                },
                error: function(xhr) {
                    let errorMessage = 'Đã xảy ra lỗi khi xử lý thanh toán.';
                    let messageType = 'danger';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                        if (xhr.responseJSON.type) {
                            messageType = xhr.responseJSON.type;
                        }
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    $('#payment-message').removeClass('alert-warning alert-success').addClass('alert-danger').text(errorMessage).show();
                    console.error('Error processing payment:', xhr.responseText);
                }
            });
        });

        // --- XỬ LÝ NÚT "GỬI TIN NHẮN" ---
        $('#send-message-btn').on('click', function() {
            const selectedStudentIds = [];
            const selectedClassId = $('.class-item.active').data('class-id');

            // Chỉ lấy ID của các học viên đang hiển thị và được chọn
            $('#student-list-tbody input.student-checkbox:checked').each(function() {
                selectedStudentIds.push($(this).data('student-id'));
            });

            if (selectedStudentIds.length === 0) {
                displayAjaxMessage('Vui lòng chọn ít nhất một học viên để gửi tin nhắn.', 'warning');
                return;
            }

            $.ajax({
                url: `/hocphi/send-reminders`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids: selectedStudentIds,
                    class_id: selectedClassId
                },
                success: function(response) {
                    console.log("Email sending response:", response);
                    displayAjaxMessage(response.message, 'success');

                    // Sau khi gửi, bỏ chọn tất cả các checkbox
                    $('#select-all-students').prop('checked', false);
                    $('#student-list-tbody input.student-checkbox').prop('checked', false);
                },
                error: function(xhr) {
                    let errorMessage = 'Đã xảy ra lỗi khi gửi tin nhắn.';
                    let messageType = 'danger';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                        if (xhr.responseJSON.type) {
                            messageType = xhr.responseJSON.type;
                        }
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    console.error('Error sending message:', xhr.responseText);
                    displayAjaxMessage(errorMessage, messageType);
                }
            });
        });

    }); // End $(document).ready


    // --- HÀM TẢI HỌC VIÊN TỪ SERVER VÀ LƯU VÀO BIẾN TOÀN CỤC ---
    // function loadStudents(classId, className) {
    //     $('#student-list-tbody').html('<tr><td colspan="6" class="text-center">Đang tải danh sách học viên...</td></tr>');
    //     $('#selected-class-name').text(className);
    //     $('#student-search').val(''); // Xóa ô tìm kiếm học viên khi đổi lớp

    //     $.ajax({
    //         url: '/classes/' + classId + '/students',
    //         method: 'GET',
    //         success: function(response) {
    //             currentStudents = response.students || []; // Lưu dữ liệu học viên vào biến toàn cục
    //             displayStudents(currentStudents, classId); // Hiển thị tất cả học viên lần đầu (không có từ khóa tìm kiếm)
    //         },
    //         error: function(xhr) {
    //             console.error('Error loading students:', xhr.responseText);
    //             $('#student-list-tbody').html('<tr><td colspan="6" class="text-center text-danger">Lỗi khi tải danh sách học viên.</td></tr>');
    //         }
    //     });
    // }


    // --- HÀM TẢI HỌC VIÊN TỪ SERVER VÀ LƯU VÀO BIẾN TOÀN CỤC ---
    function loadStudents(classId, className) {
        $('#student-list-tbody').html('<tr><td colspan="6" class="text-center">Đang tải danh sách học viên...</td></tr>');
        $('#selected-class-name').text(className);
        $('#student-search').val(''); // Xóa ô tìm kiếm học viên khi đổi lớp

        $.ajax({
            url: '/classes/' + classId + '/students', // Route để lấy danh sách học viên và thông tin học phí
            method: 'GET',
            success: function(response) {
                // Đảm bảo response.students chứa các trường total_tuition, paid_amount, remaining_amount, hocphi_status, hocphi_badge_class
                currentStudents = response.students || [];
                displayStudents(currentStudents, classId);
            },
            error: function(xhr) {
                console.error('Error loading students:', xhr.responseText);
                $('#student-list-tbody').html('<tr><td colspan="6" class="text-center text-danger">Lỗi khi tải danh sách học viên.</td></tr>');
            }
        });
    }
    // --- HÀM ĐỂ LỌC VÀ HIỂN THỊ HỌC VIÊN TỪ BIẾN TOÀN CỤC `currentStudents` ---
    /*
    function displayStudents(students, classId, searchTerm = '') {
        let studentRowsHtml = '';
        let hasVisibleStudents = false;

        if (students && students.length > 0) {
            students.forEach(function(student) {
                const mahocvien = (student.mahocvien || '').toLowerCase();
                const ten = (student.ten || '').toLowerCase();
                const sdt = (student.sdt ? String(student.sdt) : '').toLowerCase(); // Đảm bảo sdt là chuỗi

                // Kiểm tra xem học viên có khớp với từ khóa tìm kiếm không
                const matchesSearch = searchTerm === '' ||
                    mahocvien.includes(searchTerm) ||
                    ten.includes(searchTerm) ||
                    sdt.includes(searchTerm);

                if (matchesSearch) {
                    hasVisibleStudents = true;
                    const currentStatus = student.hocphi_status || 'Chưa xác định';
                    // Tạo nút "In biên lai" chỉ khi trạng thái là 'Đã đóng đủ'
                    const printReceiptBtn = currentStatus === 'Đã đóng đủ' ?
                        `<a href="/phieuthu/print/${student.id}/${classId}" target="_blank" class="text-primary print-receipt-btn" data-student-id="${student.id}" data-class-id="${classId}">In biên lai</a>` :
                        '';

                    studentRowsHtml += `
                        <tr id="student-row-${student.id}">
                            <td><input type="checkbox" class="student-checkbox" data-student-id="${student.id}"></td>
                            <td>${student.mahocvien || ''}</td>
                            <td>${student.ten || ''}</td>
                            <td>${student.sdt || ''}</td>
                            <td>${student.ngaydangky || ''}</td>
                            <td class="tuition-status-col">
                                <span class="${student.hocphi_badge_class || 'badge bg-secondary'}">${currentStatus}</span>
                                <div class="mt-1">
                                    <a href="#" class="text-info thu-hoc-phi-btn" data-student-id="${student.id}" data-class-id="${classId}">Thu học phí</a>
                                    ${printReceiptBtn}
                                </div>
                            </td>
                        </tr>
                    `;
                }
            });
        }

        if (!hasVisibleStudents) {
            studentRowsHtml = '<tr><td colspan="6" class="text-center">Không tìm thấy học viên nào phù hợp.</td></tr>';
        }

        $('#student-list-tbody').html(studentRowsHtml);
        // Đảm bảo "Select All" checkbox được reset khi danh sách học viên thay đổi
        $('#select-all-students').prop('checked', false);
    }*/
    // --- HÀM ĐỂ LỌC VÀ HIỂN THỊ HỌC VIÊN TỪ BIẾN TOÀN CỤC `currentStudents` ---
    function displayStudents(students, classId, searchTerm = '') {
        let studentRowsHtml = '';
        let hasVisibleStudents = false;

        if (students && students.length > 0) {
            students.forEach(function(student) {
                const mahocvien = (student.mahocvien || '').toLowerCase();
                const ten = (student.ten || '').toLowerCase();
                const sdt = (student.sdt ? String(student.sdt) : '').toLowerCase();

                const matchesSearch = searchTerm === '' ||
                    mahocvien.includes(searchTerm) ||
                    ten.includes(searchTerm) ||
                    sdt.includes(searchTerm);

                if (matchesSearch) {
                    hasVisibleStudents = true;

                    // Lấy thông tin học phí đã được tính toán từ backend
                    const totalTuition = student.total_tuition || 0;
                    const paidAmount = student.paid_amount || 0;
                    const remainingAmount = student.remaining_amount || totalTuition - paidAmount; // Đảm bảo remaining_amount được tính đúng

                    let currentStatus = 'Chưa xác định';
                    let badgeClass = 'badge bg-secondary';
                    let printReceiptBtn = '';

                    if (totalTuition === 0) {
                        currentStatus = 'Chưa có học phí lớp';
                        badgeClass = 'badge bg-info';
                    } else if (remainingAmount <= 0) {
                        currentStatus = 'Đã đóng đủ';
                        badgeClass = 'badge bg-success';
                        // Nút In biên lai chỉ hiện khi đã đóng đủ
                        printReceiptBtn = `<a href="/phieuthu/print/${student.id}/${classId}" target="_blank" class="text-primary print-receipt-btn" data-student-id="${student.id}" data-class-id="${classId}">In biên lai</a>`;
                    } else if (paidAmount > 0 && remainingAmount > 0) {
                        currentStatus = `Còn nợ (${(remainingAmount).toLocaleString('vi-VN')} VNĐ)`;
                        badgeClass = 'badge bg-warning text-dark';
                    } else { // paidAmount === 0 && remainingAmount > 0
                        currentStatus = 'Chưa đóng';
                        badgeClass = 'badge bg-danger';
                    }


                    studentRowsHtml += `
                    <tr id="student-row-${student.id}">
                        <td><input type="checkbox" class="student-checkbox" data-student-id="${student.id}"></td>
                        <td>${student.mahocvien || ''}</td>
                        <td>${student.ten || ''}</td>
                        <td>${student.sdt || ''}</td>
                        <td>${student.ngaydangky || ''}</td>
                        <td class="tuition-status-col">
                            <span class="${badgeClass}">${currentStatus}</span>
                            <div class="mt-1">
                                <a href="#" class="text-info thu-hoc-phi-btn" data-student-id="${student.id}" data-class-id="${classId}">Thu học phí</a>
                                ${printReceiptBtn}
                            </div>
                        </td>
                    </tr>
                `;
                }
            });
        }

        if (!hasVisibleStudents) {
            studentRowsHtml = '<tr><td colspan="6" class="text-center">Không tìm thấy học viên nào phù hợp.</td></tr>';
        }

        $('#student-list-tbody').html(studentRowsHtml);
        $('#select-all-students').prop('checked', false);
    }


    // --- HÀM HIỂN THỊ THÔNG BÁO AJAX (ALERT) ---
    function displayAjaxMessage(message, type = 'info') {
        const messageContainer = $('#ajax-message-container');
        messageContainer.empty();
        const alertClass = `alert-${type}`;

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        messageContainer.append(alertHtml);

        // Tự động ẩn thông báo sau 10 giây
        setTimeout(function() {
            messageContainer.find('.alert').alert('close');
        }, 5000);
    }
</script>
@endsection