@extends('index')

@section('title-content')
<title>Danh sách Giáo viên</title>
@endsection

@section('main-content')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Nếu bạn muốn hỗ trợ tiếng Việt cho Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>


<style>
    /* Đồng bộ giao diện cho input và select theo phong cách hiện đại */
    input.form-control,
    select.form-select {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        /* Padding vừa phải, thoáng */
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        /* Bo góc vừa phải (6px) */
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-sizing: border-box;
    }

    input.form-control:focus,
    select.form-select:focus {
        color: #212529;
        background-color: #fff;
        border-color: #6366f1;
        /* Màu tím nổi bật */
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        /* Ánh sáng tím nhẹ */
    }

    /* Đảm bảo select không cao hơn input */
    select.form-select {
        height: calc(2.25rem + 2px);
        /* Chiều cao input chuẩn Bootstrap (~ 36px) */
        padding-right: 2.25rem;
        /* Để cho icon mũi tên */
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image:
            url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23333' viewBox='0 0 4 5'%3e%3cpath d='M2 0L0 2h4L2 0zM2 5L0 3h4l-2 2z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 8px 10px;
        cursor: pointer;
    }

    /* Đảm bảo các ô form-group có margin dưới đồng đều */
    .mb-3 {
        margin-bottom: 1rem !important;
    }

    /* CSS cho kết quả tìm kiếm */
    #search-results-list {
        /* Đổi ID cho phù hợp với cách tìm kiếm client-side */
        list-style: none;
        padding: 0;
        margin: 0;
        border: 1px solid #eee;
        border-top: none;
        max-height: 200px;
        overflow-y: auto;
        background-color: #fff;
    }

    #search-results-list li {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    #search-results-list li:hover {
        background-color: #f8f9fa;
    }

    #search-results-list li:last-child {
        border-bottom: none;
    }
</style>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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

        <h3 class="card-title">Danh sách Giáo viên</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-trinhdo">+ Thêm mới</button>

            <div class="search-form" style="position: relative;">
                <input type="search" id="teacher-search" name="tu_khoa" placeholder="Tìm kiếm giáo viên theo mã, tên, email, SĐT..." autocomplete="off" class="form-control" />
                {{-- <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div> --}}
            </div>
        </div>

        {{-- Giữ nguyên phần phân trang nếu bạn muốn phân trang Laravel vẫn hoạt động khi không tìm kiếm --}}
        <form action="" method="GET" class="mb-3">
            <label for="per_page">Chọn số trang cần hiển thị:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()" class="form-select form-select-sm w-auto d-inline-block ms-2">
                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
            </select>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã giáo viên</th>
                        <th>Tên giáo viên</th>
                        <th>Hình ảnh</th>
                        <th>Email</th>
                        <th>Chức danh</th>
                        <th>Chuyên môn</th>
                        <th>Học vị</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Trạng thái</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="teacher-list-tbody"> {{-- Đổi ID từ kq-timkiem sang teacher-list-tbody để dễ quản lý --}}
                    @forelse($dsgiaovien as $gv)
                    <tr
                        data-id="{{ $gv->id }}"
                        data-magiaovien="{{ $gv->magiaovien }}"
                        data-ten="{{ $gv->ten }}"
                        data-image="{{ $gv->hinhanh ?? '' }}"
                        data-email="{{ $gv->user->email ?? '' }}"
                        data-chucdanh="{{ $gv->chucdanh->ten ?? '' }}"
                        data-chuyenmon="{{ $gv->chuyenmon->tenchuyenmon ?? '' }}"
                        data-hocvi="{{ $gv->hocvi->tenhocvi ?? '' }}"
                        data-diachi="{{ $gv->diachi }}"
                        data-sdt="{{ $gv->sdt }}"
                        data-ngaysinh="{{ $gv->ngaysinh }}"
                        data-gioitinh="{{ $gv->gioitinh }}"
                        data-trangthai="{{ $gv->trangthai }}"
                        data-chucdanh_id="{{ $gv->chucdanh_id }}"
                        data-chuyenmon_id="{{ $gv->chuyenmon_id }}"
                        data-hocvi_id="{{ $gv->hocvi_id }}"
                        data-stk="{{ $gv->stk }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $gv->magiaovien }}</td>
                        <td>{{ $gv->ten }}</td>
                        <td> {{-- Cột hình ảnh mới --}}
                            @if($gv->hinhanh)
                            <img src="{{ asset('storage/teacher_images/' . $gv->hinhanh) }}" alt="Ảnh giáo viên" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            @else
                            Không ảnh
                            @endif
                        </td>
                        <td>{{ $gv->user->email ?? '' }}</td>
                        <td>{{ $gv->chucdanh->ten ?? '' }}</td>
                        <td>{{ $gv->chuyenmon->tenchuyenmon ?? '' }}</td>
                        <td>{{ $gv->hocvi->tenhocvi ?? '' }}</td>
                        <td>{!! $gv->diachi !!}</td>
                        <td>{{ $gv->sdt }}</td>
                        <td>{{ $gv->ngaysinh }}</td>
                        <td>{{ $gv->gioitinh }}</td>
                        <td>{{ $gv->trangthai }}</td>
                        <td class="col-action">
                            <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-giaovien"
                                data-id="{{ $gv->id }}"
                                data-magiaovien="{{ $gv->magiaovien }}"
                                data-ten="{{ $gv->ten }}"
                                data-email="{{ $gv->user->email ?? '' }}"
                                data-diachi="{{ $gv->diachi }}"
                                data-chucdanh_id="{{ $gv->chucdanh_id }}"
                                data-chuyenmon_id="{{ $gv->chuyenmon_id }}"
                                data-hocvi_id="{{ $gv->hocvi_id }}"
                                data-sdt="{{ $gv->sdt }}"
                                data-stk="{{ $gv->stk }}"
                                data-ngaysinh="{{ $gv->ngaysinh }}"
                                data-gioitinh="{{ $gv->gioitinh }}"
                                data-trangthai="{{ $gv->trangthai }}">
                                Sửa
                            </a>
                            <form action="{{ route('giaovien.destroy', $gv->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center">Không có giáo viên nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $dsgiaovien->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Thêm giáo viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm" action="{{ route('giaovien.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="magiaovien">Mã giáo viên</label>
                            <input type="text" name="magiaovien_display" class="form-control" id="magiaovien" value="{{ $newMa }}" disabled>
                            <input type="hidden" name="magiaovien" value="{{ $newMa }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Nhập Email">
                        </div>
                        <div class="mb-3">
                            <label for="chucdanh_id" class="form-label">Chức danh</label>
                            <select class="form-select" id="chucdanh_id" name="chucdanh_id" required>
                                <option value="">-- Chọn chức danh --</option>
                                @foreach($chucdanh as $cd)
                                <option value="{{ $cd->id }}">{{ $cd->ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hocvi_id" class="form-label">Học vị</label>
                            <select class="form-select" id="hocvi_id" name="hocvi_id" required>
                                <option value="">-- Chọn học vị --</option>
                                @foreach($hocvi as $hv)
                                <option value="{{ $hv->id }}">{{ $hv->tenhocvi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="chuyenmon_id" class="form-label">Chuyên môn</label>
                            <select class="form-select" id="chuyenmon_id" name="chuyenmon_id" required>
                                <option value="">-- Chọn chuyên môn --</option>
                                @foreach($chuyenmon as $cm)
                                <option value="{{ $cm->id }}">{{ $cm->tenchuyenmon }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ten" class="form-label">Tên giáo viên</label>
                            <input type="text" class="form-control" id="ten" name="ten" required placeholder="Nhập tên giáo viên">
                        </div>
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="sdt" name="sdt" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="mb-3">
                            <label for="ngaysinh" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" id="ngaysinh" name="ngaysinh">
                        </div>
                        <div class="mb-3">
                            <label for="gioitinh" class="form-label">Giới tính</label>
                            <select class="form-select" id="gioitinh" name="gioitinh">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam">Nam</option>
                                <option value="nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="diachi" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="diachi" name="diachi" placeholder="Nhập địa chỉ">
                        </div>
                        <div class="mb-3">
                            <label for="stk" class="form-label">Số tài khoản</label>
                            <input type="text" class="form-control" id="stk" name="stk" placeholder="Nhập số tài khoản">
                        </div>
                        <div class="mb-3">
                            <label for="trangthai" class="form-label">Trạng thái</label>
                            <input type="text" class="form-control" id="trangthai" name="trangthai" value="đang dạy" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm giáo viên</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editTeacherForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTeacherModalLabel">Chỉnh sửa giáo viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_magiaovien" class="form-label">Mã giáo viên</label>
                            <input type="text" id="edit_magiaovien" name="magiaovien" class="form-control" placeholder="Nhập mã giáo viên" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control" placeholder="Nhập email">
                        </div>

                        <div class="mb-3">
                            <label for="edit_chucdanh_id" class="form-label">Chức danh</label>
                            <select id="edit_chucdanh_id" name="chucdanh_id" class="form-select">
                                <option value="">-- Chọn chức danh --</option>
                                @foreach($chucdanh as $cd)
                                <option value="{{ $cd->id }}">{{ $cd->ten }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_hocvi_id" class="form-label">Học vị</label>
                            <select id="edit_hocvi_id" name="hocvi_id" class="form-select">
                                <option value="">-- Chọn học vị --</option>
                                @foreach($hocvi as $hv)
                                <option value="{{ $hv->id }}">{{ $hv->tenhocvi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_chuyenmon_id" class="form-label">Chuyên môn</label>
                            <select id="edit_chuyenmon_id" name="chuyenmon_id" class="form-select">
                                <option value="">-- Chọn chuyên môn --</option>
                                @foreach($chuyenmon as $cm)
                                <option value="{{ $cm->id }}">{{ $cm->tenchuyenmon }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_ten" class="form-label">Tên giáo viên</label>
                            <input type="text" id="edit_ten" name="ten" class="form-control" required placeholder="Nhập tên giáo viên">
                        </div>

                        <div class="mb-3">
                            <label for="edit_sdt" class="form-label">Số điện thoại</label>
                            <input type="text" id="edit_sdt" name="sdt" class="form-control" placeholder="Nhập số điện thoại">
                        </div>

                        <div class="mb-3">
                            <label for="edit_ngaysinh" class="form-label">Ngày sinh</label>
                            <input type="date" id="edit_ngaysinh" name="ngaysinh" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="edit_gioitinh" class="form-label">Giới tính</label>
                            <select id="edit_gioitinh" name="gioitinh" class="form-select">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam">Nam</option>
                                <option value="nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Hình ảnh</label>
                            {{-- Hiển thị ảnh hiện tại (nếu có) --}}
                            <div id="current_image_preview" style="margin-bottom: 10px;">
                                @if(isset($gv) && $gv->hinhanh)
                                <img src="{{ asset('storage/teacher_images/' . $gv->hinhanh) }}" alt="Ảnh hiện tại" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                @endif
                            </div>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                            <small class="form-text text-muted">Chọn file ảnh mới nếu bạn muốn thay đổi.</small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_diachi" class="form-label">Địa chỉ</label>
                            <input type="text" id="edit_diachi" name="diachi" class="form-control" placeholder="Nhập địa chỉ">
                        </div>

                        <div class="mb-3">
                            <label for="edit_stk" class="form-label">Số tài khoản</label>
                            <input type="text" id="edit_stk" name="stk" class="form-control" placeholder="Nhập số tài khoản">
                        </div>

                        <div class="mb-3">
                            <label for="edit_trangthai" class="form-label">Trạng thái</label>
                            <input type="text" id="edit_trangthai" name="trangthai" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Khởi tạo một mảng JavaScript để lưu trữ tất cả giáo viên
        let allTeachers = [];

        $(document).ready(function() {
            console.log("Document ready and jQuery is loaded."); // Debug 1: Kiểm tra jQuery

            // Lấy dữ liệu giáo viên từ DOM và lưu vào allTeachers
            $('#teacher-list-tbody tr').each(function(index) {
                const teacherData = $(this).data(); // Lấy tất cả data-* attributes
                if (Object.keys(teacherData).length > 0) {
                    allTeachers.push(teacherData);
                    // console.log(`Teacher row ${index} data:`, teacherData); // Debug 2: Kiểm tra dữ liệu từng hàng
                }
            });
            console.log("All teachers data loaded:", allTeachers); // Debug 3: Kiểm tra toàn bộ mảng

            // Ghi đè sự kiện tìm kiếm hiện có
            $("#teacher-search").off("keyup").on("keyup", function() {
                const searchTerm = $(this).val().toLowerCase();
                displayTeachers(searchTerm);
            });

            // Mở popup khi click nút "Thêm mới"
            document.querySelector('.btn-trinhdo').addEventListener('click', function() {
                console.log("Add new teacher button clicked."); // Debug 4: Kiểm tra nút Thêm mới
                var myModal = new bootstrap.Modal(document.getElementById('addClassModal'));
                myModal.show();
            });

            // =========================================================================
            // PHẦN QUAN TRỌNG NHẤT: Xử lý sự kiện click cho nút "Sửa"
            // =========================================================================
            $(document).on('click', '.btn-sua-giaovien', function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>

                console.log("Edit teacher button clicked!"); // Debug 5: Kiểm tra click
                const button = $(this); // Lấy đối tượng jQuery của nút được click

                // Lấy dữ liệu từ data-attributes của chính nút đó
                const id = button.data('id');
                const magiaovien = button.data('magiaovien') || '';
                const email = button.data('email') || '';
                const chucdanh_id = button.data('chucdanh_id') || '';
                const hocvi_id = button.data('hocvi_id') || '';
                const chuyenmon_id = button.data('chuyenmon_id') || '';
                const ten = button.data('ten') || '';
                const sdt = button.data('sdt') || '';
                const ngaysinh = button.data('ngaysinh') || '';
                const gioitinh = button.data('gioitinh') || '';
                const diachi = button.data('diachi') || '';
                const stk = button.data('stk') || '';
                const trangthai = button.data('trangthai') || '';

                console.log("Teacher ID:", id); // Debug 6: Kiểm tra ID
                console.log("Teacher Data (from button data):", button.data()); // Debug 7: Kiểm tra tất cả data-*

                const form = $('#editTeacherForm'); // Sử dụng jQuery selector cho form
                form.attr('action', `/giaovien/update/${id}`); // Cập nhật đường route tương ứng

                // Điền dữ liệu vào form
                $('#edit_magiaovien').val(magiaovien);
                $('#edit_email').val(email);
                $('#edit_chucdanh_id').val(chucdanh_id);
                $('#edit_hocvi_id').val(hocvi_id);
                $('#edit_chuyenmon_id').val(chuyenmon_id);
                $('#edit_ten').val(ten);
                $('#edit_sdt').val(sdt);
                $('#edit_ngaysinh').val(ngaysinh);
                $('#edit_gioitinh').val(gioitinh);
                $('#edit_diachi').val(diachi);
                $('#edit_stk').val(stk);
                $('#edit_trangthai').val(trangthai);

                // Kiểm tra xem các giá trị đã được điền vào input chưa
                // console.log("Value of #edit_ten after filling:", $('#edit_ten').val()); // Debug 8

                // Hiển thị modal
                var editModal = new bootstrap.Modal(document.getElementById('editTeacherModal'));
                editModal.show();
                console.log("Modal show command sent."); // Debug 9
            });
            // =========================================================================

            // Gọi hàm displayTeachers ban đầu để hiển thị tất cả giáo viên
            displayTeachers('');
        }); // End $(document).ready()

        // Hàm hiển thị giáo viên dựa trên từ khóa tìm kiếm
        function displayTeachers(searchTerm) {
            let teacherRowsHtml = '';
            let hasVisibleTeachers = false;

            // Bỏ phần phân trang Laravel nếu bạn muốn tìm kiếm trên tất cả dữ liệu
            // Nếu không, bạn sẽ chỉ tìm kiếm trên dữ liệu của trang hiện tại.


            if (allTeachers.length > 0) {
                allTeachers.forEach(function(teacher, index) { // Thêm index để có thể dùng cho số thứ tự
                    // Debug: Kiểm tra cấu trúc của từng đối tượng teacher
                    // console.log("Processing teacher:", teacher);

                    const magiaovien = (teacher.magiaovien || '').toLowerCase();
                    const ten = (teacher.ten || '').toLowerCase();
                    const email = (teacher.email || '').toLowerCase();
                    const sdt = (teacher.sdt ? String(teacher.sdt) : '').toLowerCase();
                    const chucdanh = (teacher.chucdanh ? String(teacher.chucdanh) : '').toLowerCase(); // Chắc chắn là chuỗi
                    const chuyenmon = (teacher.chuyenmon ? String(teacher.chuyenmon) : '').toLowerCase(); // Chắc chắn là chuỗi
                    const hocvi = (teacher.hocvi ? String(teacher.hocvi) : '').toLowerCase(); // Chắc chắn là chuỗi
                    const diachi = (teacher.diachi || '').toLowerCase();
                    const ngaysinh = (teacher.ngaysinh || '').toLowerCase();
                    const gioitinh = (teacher.gioitinh || '').toLowerCase();
                    const trangthai = (teacher.trangthai || '').toLowerCase();


                    // Kiểm tra xem giáo viên có khớp với từ khóa tìm kiếm không
                    const matchesSearch = searchTerm === '' ||
                        magiaovien.includes(searchTerm) ||
                        ten.includes(searchTerm) ||
                        email.includes(searchTerm) ||
                        sdt.includes(searchTerm) ||
                        chucdanh.includes(searchTerm) ||
                        chuyenmon.includes(searchTerm) ||
                        hocvi.includes(searchTerm) ||
                        diachi.includes(searchTerm) ||
                        ngaysinh.includes(searchTerm) ||
                        gioitinh.includes(searchTerm) ||
                        trangthai.includes(searchTerm);

                    if (matchesSearch) {
                        hasVisibleTeachers = true;
                        // Để giữ số thứ tự đúng với bảng gốc, dùng loop->iteration
                        // Nhưng nếu bạn đang lọc từ `allTeachers` mà nó không còn là một collection Laravel,
                        // thì `loop->iteration` sẽ không còn đúng nữa.
                        // Bạn có thể dùng `index + 1` nếu `allTeachers` là một mảng JavaScript thuần.
                        // Tuy nhiên, để giữ sự nhất quán với phân trang Laravel,
                        // tốt nhất là giữ nguyên dữ liệu gốc trong DOM và chỉ ẩn/hiện hàng.
                        // Hoặc, nếu muốn tìm kiếm trên toàn bộ dữ liệu, bạn cần truyền tất cả data từ controller.

                        // Ở đây tôi sẽ vẫn giữ việc tạo HTML dynamically,
                        // nhưng bạn cần đảm bảo các giá trị chucdanh, chuyenmon, hocvi là chuỗi từ data-attributes.
                        // Lưu ý: Các giá trị data-chucdanh, data-chuyenmon, data-hocvi được đọc từ các thuộc tính trên <tr>,
                        // không phải là đối tượng. Do đó, cần kiểm tra lại cách bạn đang gán chúng.
                        // Trong template Blade, bạn đã gán `data-chucdanh="{{ $gv->chucdanh->ten ?? '' }}"`,
                        // nên trong JS, `teacher.chucdanh` sẽ là chuỗi tên chứ không phải đối tượng.

                        teacherRowsHtml += `
                        <tr
                            data-id="${teacher.id}"
                            data-magiaovien="${teacher.magiaovien || ''}"
                            data-ten="${teacher.ten || ''}"
                             data-image="${teacher.image || ''}" {{-- Đảm bảo data-image có ở đây --}}
                            data-email="${teacher.email || ''}"
                            data-chucdanh="${teacher.chucdanh || ''}"
                            data-chuyenmon="${teacher.chuyenmon || ''}"
                            data-hocvi="${teacher.hocvi || ''}"
                            data-diachi="${teacher.diachi || ''}"
                            data-sdt="${teacher.sdt || ''}"
                            data-ngaysinh="${teacher.ngaysinh || ''}"
                            data-gioitinh="${teacher.gioitinh || ''}"
                            data-trangthai="${teacher.trangthai || ''}"
                            data-chucdanh_id="${teacher.chucdanh_id || ''}"
                            data-chuyenmon_id="${teacher.chuyenmon_id || ''}"
                            data-hocvi_id="${teacher.hocvi_id || ''}"
                            data-stk="${teacher.stk || ''}"
                        >
                            <td>${index + 1}</td> <td>${teacher.magiaovien || ''}</td>
                            <td>${teacher.ten || ''}</td>
                             <td> {{-- Cột hình ảnh trong JS --}}
            ${teacher.image ? `<img src="${window.location.origin}/storage/teacher_images/${teacher.image}" alt="Ảnh giáo viên" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">` : 'Không ảnh'}
        </td>
                            <td>${teacher.email || ''}</td>
                            <td>${teacher.chucdanh || ''}</td>
                            <td>${teacher.chuyenmon || ''}</td>
                            <td>${teacher.hocvi || ''}</td>
                            <td>${teacher.diachi || ''}</td>
                            <td>${teacher.sdt || ''}</td>
                            <td>${teacher.ngaysinh || ''}</td>
                            <td>${teacher.gioitinh || ''}</td>
                            <td>${teacher.trangthai || ''}</td>
                            <td class="col-action">
                                <a href="#" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                                <a href="javascript:void(0);"
                                    class="btn btn-sm btn-warning btn-sua-giaovien"
                                    data-id="${teacher.id}"
                                    data-magiaovien="${teacher.magiaovien || ''}"
                                    data-ten="${teacher.ten || ''}"
                                    data-email="${teacher.email || ''}"
                                    data-diachi="${teacher.diachi || ''}"
                                    data-chucdanh_id="${teacher.chucdanh_id || ''}"
                                    data-chuyenmon_id="${teacher.chuyenmon_id || ''}"
                                    data-hocvi_id="${teacher.hocvi_id || ''}"
                                    data-sdt="${teacher.sdt || ''}"
                                    data-stk="${teacher.stk || ''}"
                                    data-ngaysinh="${teacher.ngaysinh || ''}"
                                    data-gioitinh="${teacher.gioitinh || ''}"
                                    data-trangthai="${teacher.trangthai || ''}">
                                    Sửa
                                </a>
                                <form action="/giaovien/destroy/${teacher.id}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    `;
                    }
                });
            }

            if (!hasVisibleTeachers) {
                teacherRowsHtml = '<tr><td colspan="13" class="text-center">Không tìm thấy giáo viên nào phù hợp.</td></tr>';
            }

            $('#teacher-list-tbody').html(teacherRowsHtml);
        }
    </script>
    <script src="{{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
    @endsection