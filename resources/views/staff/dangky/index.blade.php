@extends('staff.index') {{-- Đảm bảo đây là layout chính của staff của bạn --}}

@section('title-content')
<title>Đăng Ký Học Viên Vào Lớp</title>
@endsection

@section('staff-content')

{{-- Import các thư viện cần thiết --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- Bootstrap CSS (nếu bạn đang dùng Bootstrap 5) --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Custom CSS để đảm bảo giao diện đẹp và rõ ràng */
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .card-title {
        color: #007bff;
        font-size: 2em;
        margin-bottom: 25px;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
    }

    .form-section {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .form-section h4 {
        color: #333;
        margin-bottom: 20px;
        font-size: 1.5em;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
    }

    .form-group label {
        font-weight: bold;
        color: #555;
        display: block;
        margin-bottom: 5px;
    }

    .form-control,
    .form-select {
        border-radius: 5px;
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 200px;
        overflow-y: auto;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    .autocomplete-items div:hover {
        background-color: #e9e9e9;
    }

    .autocomplete-active {
        background-color: DodgerBlue !important;
        color: #ffffff;
    }

    .selected-info-box {
        background-color: #e9f7ef;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        color: #155724;
    }

    .selected-info-box p {
        margin-bottom: 5px;
    }

    .selected-info-box p:last-child {
        margin-bottom: 0;
    }

    .modal-body .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }

    .modal-body .table th,
    .modal-body .table td {
        white-space: nowrap;
    }

    /* Style for history table */
    #registration_history_content .table th,
    #registration_history_content .table td {
        font-size: 0.9em;
        padding: 8px;
    }

    /* .modal-content {
        width: 100%;
        min-width: 900px;
    } */
</style>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div id="alert-error" class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if ($errors->any())
        <div id="alert-validation" class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <h3 class="card-title">Đăng Ký Học Viên Vào Lớp Học</h3>

        <div class="row">
            {{-- Cột trái: Form Đăng Ký --}}
            <div class="col-md-6">
                <form id="registrationForm" action="{{ route('staff.registrations.store') }}" method="POST">
                    @csrf

                    {{-- Phần chọn Học viên --}}
                    <div class="form-section">
                        <h4>Chọn Học Viên</h4>
                        <div class="mb-3 position-relative" style="display: flex;">
                            <label for="student_search_input" class="form-label">Tìm kiếm học viên (Tên/Mã)</label>
                            <input type="text" class="form-control" id="student_search_input" placeholder="Nhập tên hoặc mã học viên">
                            <div id="student_search_results" class="autocomplete-items"></div>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#selectStudentModal">
                                <i class="fas fa-list"></i> Chọn từ danh sách
                            </button>
                        </div>


                        <input type="hidden" name="hocvien_id" id="selected_hocvien_id" required>
                        <div id="selected_student_info" class="selected-info-box mt-3" style="display: none;">
                            <p><strong>Mã HV:</strong> <span id="info_mahocvien"></span></p>
                            <p><strong>Tên HV:</strong> <span id="info_tenhocvien"></span></p>
                            <p><strong>Email:</strong> <span id="info_email"></span></p>
                            <p><strong>SĐT:</strong> <span id="info_sdt"></span></p>
                            <p><strong>Địa chỉ:</strong> <span id="info_diachi"></span></p>
                        </div>
                    </div>

                    {{-- Phần chọn Lớp học --}}
                    <div class="form-section">
                        <h4>Chọn Lớp Học</h4>
                        <!-- <div class="mb-3">
                            <label for="khoahoc_filter" class="form-label">Lọc theo Khóa học:</label>
                            <select class="form-select" id="khoahoc_filter">
                                <option value="">-- Tất cả khóa học --</option>
                                @foreach($khoahocs as $kh)
                                <option value="{{ $kh->id }}">{{ $kh->ten }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="mb-3 position-relative" style="display: flex;">
                            <label for="class_search_input" class="form-label">Tìm kiếm lớp học (Tên/Mã)</label>
                            <input type="text" class="form-control" id="class_search_input" placeholder="Nhập tên hoặc mã lớp học">
                            <div id="class_search_results" class="autocomplete-items"></div>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#selectClassModal">
                                <i class="fas fa-list"></i> Chọn từ danh sách
                            </button>
                        </div>


                        <input type="hidden" name="lophoc_id" id="selected_lophoc_id" required>
                        <div id="selected_class_info" class="selected-info-box mt-3" style="display: none;">
                            <p><strong>Mã lớp:</strong> <span id="info_malop"></span></p>
                            <p><strong>Tên lớp:</strong> <span id="info_tenlop"></span></p>
                            <p><strong>Sỉ số:</strong> <span id="info_succhua"></span></p>
                            <p><strong>Khóa học:</strong> <span id="info_khoahoc"></span></p>
                            <p><strong>Trình độ:</strong> <span id="info_trinhdo"></span></p>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" id="btnDangKy" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Đăng Ký Học Viên
                        </button>
                    </div>
                </form>
            </div>

            {{-- Cột phải: Lịch sử đăng ký của Học viên --}}
            <div class="col-md-6">
                <div class="form-section">
                    <h4>Lịch sử đăng ký của Học viên: <span id="history_student_name" class="text-primary fw-bold"></span></h4>
                    <div id="history_loading" class="text-center text-muted py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-2">Đang tải lịch sử đăng ký...</p>
                    </div>
                    <div id="registration_history_content">
                        <p class="text-muted text-center py-4" id="no_history_message">Vui lòng chọn một học viên để xem lịch sử đăng ký.</p>
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th>Lớp học</th>
                                        <th>Ngày ĐK</th>
                                        <th>Học phí</th>
                                        <th>Trạng thái TT</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="history_table_body">
                                    {{-- Lịch sử đăng ký sẽ được tải bằng JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Chọn Học Viên (Không thay đổi) --}}
<div class="modal fade" id="selectStudentModal" tabindex="-1" aria-labelledby="selectStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectStudentModalLabel">Chọn Học Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="modal_student_search" placeholder="Tìm kiếm trong danh sách...">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mã HV</th>
                                <th>Tên HV</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Địa chỉ</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="modal_student_list">
                            @foreach($hocviens as $hv)
                            <tr data-id="{{ $hv->id }}" data-ma="{{ $hv->mahocvien }}" data-ten="{{ $hv->ten }}" data-email="{{ $hv->email_hv }}" data-sdt="{{ $hv->sdt }}" data-diachi="{{ $hv->diachi }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $hv->mahocvien }}</td>
                                <td>{{ $hv->ten }}</td>
                                <td>{{ $hv->email_hv }}</td>
                                <td>{{ $hv->sdt }}</td>
                                <td>{{ $hv->diachi }}</td>
                                <td><button type="button" class="btn btn-sm btn-success select-student-btn">Chọn</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Thu học phí cho học viên <span id="modal-student-name-display"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" id="modal-student-id" name="hocvien_id">
                    <input type="hidden" id="modal-class-id" name="lophoc_id">

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
                        <label for="amountToPay" class="form-label">Số tiền cần đóng:</label>
                        <input type="number" class="form-control" id="amountToPay" name="sotien" placeholder="Nhập số tiền học viên muốn đóng" required min="1" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Phương thức thanh toán:</label>
                        <select class="form-select" id="paymentMethod" name="phuongthuc" required style="width:100%">
                            <option value="">Chọn phương thức</option>
                            <option value="tien_mat">Tiền mặt</option>
                            <option value="chuyen_khoan">Chuyển khoản</option>
                        </select>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="paymentDate" class="form-label">Ngày thanh toán:</label>
                        <input type="text" class="form-control" id="paymentDate" name="ngaythanhtoan" required>
                    </div> -->
                    <div class="mb-3">
                        <label for="paymentNote" class="form-label">Ghi chú:</label>
                        <textarea class="form-control" id="paymentNote" name="ghichu" rows="3"></textarea>
                    </div>
                    <div id="payment-message" class="alert mt-3" style="display: none;"></div>
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Chọn Lớp Học (Không thay đổi) --}}
<div class="modal fade" id="selectClassModal" tabindex="-1" aria-labelledby="selectClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectClassModalLabel">Chọn Lớp Học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <input type="text" class="form-control" id="modal_class_search" placeholder="Tìm kiếm trong danh sách...">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mã lớp</th>
                                <th>Tên lớp</th>
                                <th>Sĩ số</th>
                                <th>Hiện tại</th>
                                <th>Khóa học</th>
                                <th>Trình độ</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="modal_class_list">
                            @foreach($lophocs as $lh)
                            <tr data-id="{{ $lh->id }}" data-ma="{{ $lh->malophoc }}" data-ten="{{ $lh->tenlophoc }}" data-succhua="{{ $lh->soluonghocvientoida }}" data-khoahoc="{{ $lh->khoahoc->ten ?? 'N/A' }}" data-trinhdo="{{ $lh->trinhdo->ten ?? 'N/A' }}" data-khoahoc-id="{{ $lh->khoahoc_id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $lh->malophoc }}</td>
                                <td>{{ $lh->tenlophoc }}</td>
                                <td>{{ $lh->soluonghocvientoida }}</td>
                                <td>{{ $lh->soluonghocvienhientai ??'0' }}</td>
                                <td>{{ $lh->khoahoc->ma ?? 'N/A' }}</td>
                                <td>{{ $lh->trinhdo->ten ?? 'N/A' }}</td>
                                <td><button type="button" class="btn btn-sm btn-success select-class-btn">Chọn</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap JS (nếu bạn đang dùng Bootstrap 5) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Ẩn tất cả các alert sau 3 giây (3000 ms)
    setTimeout(() => {
        const successAlert = document.getElementById('alert-success');
        if (successAlert) {
            // Sử dụng Bootstrap 5 API để ẩn alert (để trigger animation)
            bootstrap.Alert.getOrCreateInstance(successAlert).close();
        }
        const errorAlert = document.getElementById('alert-error');
        if (errorAlert) {
            bootstrap.Alert.getOrCreateInstance(errorAlert).close();
        }
        const validationAlert = document.getElementById('alert-validation');
        if (validationAlert) {
            bootstrap.Alert.getOrCreateInstance(validationAlert).close();
        }
    }, 3000);
</script>
<script>
    document.querySelectorAll('.select-class-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let row = this.closest('tr');
            let lopHocId = row.dataset.id;
            let btnDangKy = document.getElementById('btnDangKy');

            fetch(`/check-thoi-khoa-bieu/${lopHocId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.hasSchedule) {
                        // Có TKB -> mở nút, gán thông tin, đóng modal
                        btnDangKy.disabled = false;
                        btnDangKy.classList.remove('btn-secondary');
                        btnDangKy.classList.add('btn-primary');

                        document.getElementById('selected_class_id').value = lopHocId;
                        document.getElementById('selected_class_name').textContent = row.dataset.ten;

                        bootstrap.Modal.getInstance(
                            document.getElementById('selectClassModal')
                        ).hide();
                    } else {
                        // Không có TKB -> khóa nút
                        alert('❌ Lớp này chưa có thời khóa biểu.');
                        btnDangKy.disabled = true;
                        btnDangKy.classList.remove('btn-primary');
                        btnDangKy.classList.add('btn-secondary');
                    }
                })
                .catch(err => console.error(err));
        });
    });



    $(document).ready(function() {
        // Biến toàn cục để lưu trữ tất cả học viên
        let allStudents = [];
        let allClasses = [];

        // Lấy thông tin học viên được chọn ban đầu từ Controller (nếu có)
        const initialSelectedStudentData = @json($initialSelectedStudent);

        // --- Hàm chung cho Autocomplete ---
        function autocomplete(inp, arr, displayKey, valueKey, onSelectCallback) {
            let currentFocus;

            inp.addEventListener("input", function(e) {
                let a, b, i, val = this.value;
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                this.parentNode.appendChild(a);

                let filteredArr = arr.filter(item => {
                    return (item[displayKey] && item[displayKey].toUpperCase().includes(val.toUpperCase())) ||
                        (item.mahocvien && item.mahocvien.toUpperCase().includes(val.toUpperCase())); // Thêm tìm kiếm theo mã học viên
                });

                for (i = 0; i < filteredArr.length; i++) {
                    b = document.createElement("DIV");
                    let displayValue = filteredArr[i][displayKey];
                    let matchIndex = displayValue.toUpperCase().indexOf(val.toUpperCase());

                    if (matchIndex > -1) {
                        b.innerHTML = displayValue.substr(0, matchIndex);
                        b.innerHTML += "<strong>" + displayValue.substr(matchIndex, val.length) + "</strong>";
                        b.innerHTML += displayValue.substr(matchIndex + val.length);
                    } else {
                        b.innerHTML = displayValue;
                    }

                    b.innerHTML += "<input type='hidden' value='" + filteredArr[i][valueKey] + "' data-item='" + JSON.stringify(filteredArr[i]) + "'>";
                    b.addEventListener("click", function(e) {
                        onSelectCallback(JSON.parse(this.getElementsByTagName("input")[0].dataset.item));
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            });

            inp.addEventListener("keydown", function(e) {
                let x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) { // Arrow Down
                    currentFocus++;
                    addActive(x);
                } else if (e.keyCode == 38) { // Arrow Up
                    currentFocus--;
                    addActive(x);
                } else if (e.keyCode == 13) { // Enter
                    e.preventDefault();
                    if (currentFocus > -1) {
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                if (!x) return false;
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                for (let i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                let x = document.getElementsByClassName("autocomplete-items");
                for (let i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }

            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        // --- Hàm để chọn học viên ---
        function selectStudent(student) {
            $('#selected_hocvien_id').val(student.id);
            $('#student_search_input').val(student.ten);
            $('#info_mahocvien').text(student.mahocvien);
            $('#info_tenhocvien').text(student.ten);
            $('#info_email').text(student.email_hv);
            $('#info_sdt').text(student.sdt);
            $('#info_diachi').text(student.diachi);
            $('#selected_student_info').show();
            $('#selectStudentModal').modal('hide');

            // Load history for the selected student
            loadStudentHistory(student.id, student.ten);
        }

        // --- Hàm để chọn lớp học ---
        function selectClass(classroom) {
            $('#selected_lophoc_id').val(classroom.id);
            $('#class_search_input').val(classroom.tenlophoc);
            $('#info_malop').text(classroom.malophoc);
            $('#info_tenlop').text(classroom.tenlophoc);
            $('#info_succhua').text(classroom.soluonghocvientoida);
            $('#info_khoahoc').text(classroom.khoahoc_ten);
            $('#info_trinhdo').text(classroom.trinhdo_ten);
            $('#selected_class_info').show();
            $('#selectClassModal').modal('hide');
        }

        // --- Tải dữ liệu Học viên cho Autocomplete ---
        // Sử dụng Promise để đảm bảo allStudents được tải trước khi xử lý initialSelectedStudentData
        function loadAllStudents() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('staff.registrations.searchStudents') }}",
                    method: "GET",
                    data: {
                        query: ''
                    }, // Gửi query rỗng để lấy tất cả ban đầu
                    success: function(data) {
                        allStudents = data;
                        autocomplete(document.getElementById("student_search_input"), allStudents, "ten", "id", selectStudent);
                        resolve();
                    },
                    error: function(xhr) {
                        console.error("Lỗi tải danh sách học viên:", xhr.responseText);
                        reject(xhr);
                    }
                });
            });
        }

        // --- Tải dữ liệu Lớp học cho Autocomplete ---
        function loadClassesForAutocomplete(khoahocId = '', query = '') {
            $.ajax({
                url: "{{ route('staff.registrations.searchClasses') }}",
                method: "GET",
                data: {
                    khoahoc_id: khoahocId,
                    query: query
                },
                success: function(data) {
                    allClasses = data;
                    autocomplete(document.getElementById("class_search_input"), allClasses, "tenlophoc", "id", selectClass);
                },
                error: function(xhr) {
                    console.error("Lỗi tải danh sách lớp học:", xhr.responseText);
                }
            });
        }
        loadClassesForAutocomplete(); // Load ban đầu

        // --- Lọc lớp học theo khóa học (cho ô search chính) ---
        $('#khoahoc_filter').on('change', function() {
            loadClassesForAutocomplete($(this).val(), $('#class_search_input').val());
        });
        $('#class_search_input').on('keyup', function() {
            loadClassesForAutocomplete($('#khoahoc_filter').val(), $(this).val());
        });

        // --- Xử lý Modal Chọn Học Viên ---
        $('#selectStudentModal').on('shown.bs.modal', function() {
            $('#modal_student_search').val('');
            filterModalStudents('');
        });

        $('#modal_student_search').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterModalStudents(searchTerm);
        });

        function filterModalStudents(searchTerm) {
            $('#modal_student_list tr').each(function() {
                const studentName = $(this).data('ten').toLowerCase();
                const studentCode = $(this).data('ma').toLowerCase();
                if (studentName.includes(searchTerm) || studentCode.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }


        $('.select-student-btn').on('click', function() {
            const row = $(this).closest('tr');
            const student = {
                id: row.data('id'),
                mahocvien: row.data('ma'),
                ten: row.data('ten'),
                email_hv: row.data('email'),
                sdt: row.data('sdt'),
                diachi: row.data('diachi')
            };
            selectStudent(student);
        });

        // --- Xử lý Modal Chọn Lớp Học ---
        $('#selectClassModal').on('shown.bs.modal', function() {
            $('#modal_class_search').val('');
            $('#modal_khoahoc_filter').val('');
            filterModalClasses('', '');
        });

        $('#modal_khoahoc_filter').on('change', function() {
            const khoahocId = $(this).val();
            const searchTerm = $('#modal_class_search').val().toLowerCase();
            filterModalClasses(searchTerm, khoahocId);
        });

        $('#modal_class_search').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            const khoahocId = $('#modal_khoahoc_filter').val();
            filterModalClasses(searchTerm, khoahocId);
        });

        function filterModalClasses(searchTerm, khoahocId) {
            $('#modal_class_list tr').each(function() {
                const className = $(this).data('ten').toLowerCase();
                const classCode = $(this).data('ma').toLowerCase();
                const classKhoaHocId = $(this).data('khoahoc-id');

                const matchesSearch = className.includes(searchTerm) || classCode.includes(searchTerm);
                const matchesKhoaHoc = !khoahocId || classKhoaHocId == khoahocId;

                if (matchesSearch && matchesKhoaHoc) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('.select-class-btn').on('click', function() {
            const row = $(this).closest('tr');
            const classroom = {
                id: row.data('id'),
                malophoc: row.data('ma'),
                tenlophoc: row.data('ten'),
                soluonghocvientoida: row.data('succhua'),
                khoahoc_ten: row.data('khoahoc'),
                trinhdo_ten: row.data('trinhdo')
            };
            selectClass(classroom);
        });

        let currentStudentId = null;
        let currentStudentName = null;
        // --- Tải lịch sử đăng ký của học viên ---
        function loadStudentHistory(hocvienId, hocvienName) {
            currentStudentId = hocvienId; // Cập nhật biến toàn cục
            currentStudentName = hocvienName; // Cập nhật biến toàn cục
            console.log(`Đang tải lịch sử cho Học viên ID: ${hocvienId}, Tên: ${hocvienName}`);

            $('#history_student_name').text(hocvienName);
            $('#no_history_message').hide();
            $('#history_loading').show();
            $('#history_table_body').empty(); // Xóa lịch sử cũ

            $.ajax({
                url: "{{ route('staff.registrations.getStudentHistory') }}",
                method: "GET",
                data: {
                    hocvien_id: hocvienId
                },
                success: function(data) {
                    console.log("Dữ liệu lịch sử nhận được:", data);
                    $('#history_loading').hide();
                    let historyHtml = '';
                    if (data.length > 0) {
                        data.forEach(reg => {
                            // Đảm bảo các trường này tồn tại từ backend getStudentHistory
                            const ngayDangKy = new Date(reg.ngaydangky).toLocaleDateString('vi-VN');
                            const hocPhiFormatted = reg.hocphi ? new Intl.NumberFormat('vi-VN').format(reg.hocphi) + ' VNĐ' : 'N/A';
                            // Sử dụng reg.total_paid_amount nếu backend getStudentHistory cung cấp, nếu không thì dùng 0
                            const totalPaidFormatted = reg.total_paid_amount ? new Intl.NumberFormat('vi-VN').format(reg.total_paid_amount) + ' VNĐ' : '0 VNĐ';
                            const paymentStatus = reg.payment_status || 'Không xác định'; // Đảm bảo trường này tồn tại

                            let actionButtons = '';

                            if (paymentStatus === 'Đã thanh toán') {
                                actionButtons = `
                                <a href="/staff/phieuthu/print/${hocvienId}/${reg.lophoc_id}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="fas fa-print"></i> In biên lai
                                </a>
                            `;
                            } else {
                                actionButtons = `
                                <button type="button" class="btn btn-sm btn-primary btn-thu-hocphi"
                                    data-hocvien-id="${hocvienId}"
                                    data-lophoc-id="${reg.lophoc_id}"
                                    data-lophoc-ten="${reg.lophoc_ten}"
                                    data-hocvien-ten="${hocvienName}">
                                    <i class="fas fa-money-bill-wave"></i> Thu học phí
                                </button>
                            `;
                            }

                            historyHtml += `
                            <tr data-lophoc-id="${reg.lophoc_id}" data-hocvien-id="${hocvienId}">
                                <td>${reg.lophoc_ten} (${reg.lophoc_ma})</td>
                                <td>${ngayDangKy}</td>
                                <td>${hocPhiFormatted}</td>
                                
                                <td class="payment-status-cell">
                                    <span class="badge ${paymentStatus === 'Đã thanh toán' ? 'bg-success' : (paymentStatus === 'Một phần' ? 'bg-info text-dark' : 'bg-warning text-dark')}">
                                        ${paymentStatus}
                                    </span>
                                </td>
                                <td class="action-buttons-cell">
                                    ${actionButtons}
                                </td>
                            </tr>
                        `;
                        });
                    } else {
                        historyHtml = `<tr><td colspan="6" class="text-center text-muted py-3">Chưa có lịch sử đăng ký cho học viên này.</td></tr>`;
                    }
                    $('#history_table_body').html(historyHtml);
                    $('#registration_history_content').show();
                    console.log("Bảng lịch sử đã được cập nhật HTML.");
                },
                error: function(xhr) {
                    $('#history_loading').hide();
                    $('#history_table_body').html(`<tr><td colspan="6" class="text-center text-danger py-3">Lỗi tải lịch sử: ${xhr.statusText}</td></tr>`);
                    console.error("Lỗi tải lịch sử đăng ký:", xhr.responseText);
                }
            });
        }

        // Xử lý click nút "Thu học phí"
        $(document).on('click', '.btn-thu-hocphi', function() {
            const hocvienId = $(this).data('hocvien-id');
            const lophocId = $(this).data('lophoc-id');
            const lophocTen = $(this).data('lophoc-ten');
            const hocvienTen = $(this).data('hocvien-ten');

            $('#modal-student-id').val(hocvienId);
            $('#modal-class-id').val(lophocId);
            $('#modal-student-name-display').text(hocvienTen + ' - Lớp: ' + lophocTen);
            $('#payment-message').hide().removeClass('alert-success alert-danger alert-warning');
            $('#paymentForm')[0].reset();

            $.ajax({
                url: `/staff/hocphi/get-tuition-info/${lophocId}/${hocvienId}`,
                method: 'GET',
                success: function(response) {
                    console.log("Tuition info response:", response);
                    $('#modal-total-tuition').val(new Intl.NumberFormat('vi-VN').format(response.total_tuition) + ' VNĐ');
                    $('#modal-paid-amount').val(new Intl.NumberFormat('vi-VN').format(response.paid_amount) + ' VNĐ');
                    $('#modal-remaining-amount').val(new Intl.NumberFormat('vi-VN').format(response.remaining_amount) + ' VNĐ');

                    $('#amountToPay').val(response.remaining_amount > 0 ? response.remaining_amount : 0);
                    $('#amountToPay').attr('max', response.remaining_amount > 0 ? response.remaining_amount : 0);

                    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                    paymentModal.show();
                },
                error: function(xhr) {
                    let errorMessage = 'Không thể tải thông tin học phí.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    alert(errorMessage);
                    console.error('Error fetching tuition info:', xhr.responseText);
                }
            });
        });

        // --- Xử lý submit form thanh toán trong modal ---
        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const messageBox = $('#payment-message');
            const submitBtn = $(this).find('button[type="submit"]');

            const hocvienId = $('#modal-student-id').val();
            const lophocId = $('#modal-class-id').val();
            const lophocTen = $('#modal-student-name-display').text().split(' - Lớp: ')[1]; // Lấy tên lớp từ modal display
            const hocvienTen = $('#history_student_name').text(); // Lấy tên học viên từ display

            submitBtn.prop('disabled', true).text('Đang xử lý...');

            $.ajax({
                url: "{{ route('staff.hocphi.processPayment') }}",
                method: "POST",
                data: formData,
                success: function(response) {
                    messageBox.removeClass('alert-danger alert-warning').addClass('alert-success').text(response.message).show();

                    // --- LOGIC CẬP NHẬT GIAO DIỆN NGAY LẬP TỨC ---
                    // 1. Tải lại thông tin học phí chi tiết cho lớp học vừa thanh toán
                    $.ajax({
                        url: `/staff/hocphi/get-tuition-info/${lophocId}/${hocvienId}`,
                        method: 'GET',
                        success: function(tuitionInfoResponse) {
                            const newPaymentStatus = tuitionInfoResponse.payment_status; // Lấy trạng thái mới từ backend
                            const newTotalPaidAmount = tuitionInfoResponse.paid_amount;

                            // 2. Tìm dòng tương ứng trong bảng lịch sử
                            const $targetRow = $(`#history_table_body tr[data-lophoc-id="${lophocId}"][data-hocvien-id="${hocvienId}"]`);

                            if ($targetRow.length) {
                                // 3. Cập nhật cột "Đã Đóng"
                                $targetRow.find('.total-paid-amount-cell').text(new Intl.NumberFormat('vi-VN').format(newTotalPaidAmount) + ' VNĐ');

                                // 4. Cập nhật trạng thái và màu sắc badge
                                const $statusCell = $targetRow.find('.payment-status-cell span.badge');
                                $statusCell.text(newPaymentStatus);
                                $statusCell.removeClass('bg-success bg-info text-dark bg-warning')
                                    .addClass(
                                        newPaymentStatus === 'Đã thanh toán' ? 'bg-success' :
                                        (newPaymentStatus === 'Một phần' ? 'bg-info text-dark' : 'bg-warning text-dark')
                                    );

                                // 5. Cập nhật nút hành động
                                const $actionCell = $targetRow.find('.action-buttons-cell');
                                let newActionButtons = '';
                                if (newPaymentStatus === 'Đã thanh toán') {
                                    newActionButtons = `
                                    <a href="/staff/phieuthu/print/${hocvienId}/${lophocId}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="fas fa-print"></i> In biên lai
                                    </a>
                                `;
                                } else {
                                    newActionButtons = `
                                    <button type="button" class="btn btn-sm btn-primary btn-thu-hocphi"
                                        data-hocvien-id="${hocvienId}"
                                        data-lophoc-id="${lophocId}"
                                        data-lophoc-ten="${lophocTen}"
                                        data-hocvien-ten="${hocvienTen}">
                                        <i class="fas fa-money-bill-wave"></i> Thu học phí
                                    </button>
                                `;
                                }
                                $actionCell.html(newActionButtons);
                            }
                        },
                        error: function(xhr) {
                            console.error("Lỗi khi tải lại thông tin học phí để cập nhật UI:", xhr.responseText);
                            // Fallback: Nếu không thể cập nhật từng dòng, hãy tải lại toàn bộ lịch sử (ít tối ưu hơn)
                            loadStudentHistory(currentStudentId, currentStudentName);
                        }
                    });
                    loadStudentHistory(currentStudentId, currentStudentName);
                    // --- KẾT THÚC LOGIC CẬP NHẬT GIAO DIỆN NGAY LẬP TỨC ---

                    // setTimeout(() => {
                    //     messageBox.hide();
                    //     const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                    //     paymentModal.hide();
                    //     $('#paymentForm')[0].reset(); // Reset form sau khi đóng modal
                    // }, 2000);
                },
                error: function(xhr) {
                    let errorMessage = 'Có lỗi xảy ra khi xử lý thanh toán.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    messageBox.removeClass('alert-success alert-warning').addClass('alert-danger').html(errorMessage).show();
                    console.error("Lỗi thanh toán:", xhr.responseText);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Xác nhận thanh toán');
                }
            });
        });
        // --- Validate form submission ---
        $('form#registrationForm').on('submit', function(e) {
            if (!$('#selected_hocvien_id').val()) {
                e.preventDefault();
                alert('Vui lòng chọn một học viên.');
                return false;
            }
            if (!$('#selected_lophoc_id').val()) {
                e.preventDefault();
                alert('Vui lòng chọn một lớp học.');
                return false;
            }
        });

        // --- Logic để tự động tải lịch sử khi trang load (nếu có initialSelectedStudentData) ---
        loadAllStudents().then(() => {
            if (initialSelectedStudentData) {
                // Nếu có dữ liệu học viên được truyền từ Controller, tự động chọn và tải lịch sử
                selectStudent(initialSelectedStudentData);
            }
        });
    });
</script>

@endsection