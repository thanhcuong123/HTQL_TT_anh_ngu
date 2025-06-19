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
</style>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <h3 class="card-title">Danh sách Giáo viên</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-trinhdo">Thêm mới</button>

            <form class="search-form" action="" method="GET" style="position: relative;">
                <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
            </form>
        </div>

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
                <tbody id="kq-timkiem">
                    @foreach($dsgiaovien as $gv)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $gv->magiaovien }}</td>
                        <td>{{ $gv->ten }}</td>
                        <td>{{ $gv->user->email }}</td>
                        <td>{{ $gv->chucdanh->ten ??'""' }}</td>
                        <td>{{ $gv->chuyenmon->tenchuyenmon ??'""' }}</td>

                        <td>{{ $gv->hocvi->tenhocvi ??'""'}}</td>
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
                                data-email="{{ $gv->user->email }}"
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
                            <form action="" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $dsgiaovien->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <!-- Popup thêm giáo viên -->

    <!-- Popup thêm giáo viên -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Thêm giáo viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm" action="{{ route('giaovien.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">

                            <label for="magiaovien">Mã giáo viên</label>
                            <input type="magiaovien" name="magiaovien" class="form-control" id="magiaovien" value="{{ $newMa }}" disabled>
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
    <!-- Popup chỉnh sửa giáo viên -->
    <div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editTeacherForm" method="POST" action="">
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
                            <input type="text" id="edit_magiaovien" name="magiaovien" class="form-control" placeholder="Nhập mã giáo viên">
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
        document.querySelector('.btn-trinhdo').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addClassModal'));
            myModal.show();
        });

        document.querySelectorAll('.btn-sua-giaovien').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const magiaovien = this.dataset.magiaovien || '';
                const email = this.dataset.email || '';
                const chucdanh_id = this.dataset.chucdanh_id || '';
                const hocvi_id = this.dataset.hocvi_id || '';
                const chuyenmon_id = this.dataset.chuyenmon_id || '';
                const ten = this.dataset.ten || '';
                const sdt = this.dataset.sdt || '';
                const ngaysinh = this.dataset.ngaysinh || '';
                const gioitinh = this.dataset.gioitinh || '';
                const diachi = this.dataset.diachi || '';
                const stk = this.dataset.stk || '';
                const trangthai = this.dataset.trangthai || '';

                const form = document.getElementById('editTeacherForm');
                form.action = `/giaovien/update/${id}`; // Cập nhật đường route tương ứng

                form.querySelector('#edit_magiaovien').value = magiaovien;
                form.querySelector('#edit_email').value = email;
                form.querySelector('#edit_chucdanh_id').value = chucdanh_id;
                form.querySelector('#edit_hocvi_id').value = hocvi_id;
                form.querySelector('#edit_chuyenmon_id').value = chuyenmon_id;
                form.querySelector('#edit_ten').value = ten;
                form.querySelector('#edit_sdt').value = sdt;
                form.querySelector('#edit_ngaysinh').value = ngaysinh;
                form.querySelector('#edit_gioitinh').value = gioitinh;
                form.querySelector('#edit_diachi').value = diachi;
                form.querySelector('#edit_stk').value = stk;
                form.querySelector('#edit_trangthai').value = trangthai;

                const editModal = new bootstrap.Modal(document.getElementById('editTeacherModal'));
                editModal.show();
            });
        });
    </script>
    <script src="{{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
    @endsection