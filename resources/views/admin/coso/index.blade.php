@extends('index')

@section('title-content')
<title>Cơ sở</title>
@endsection

@section('main-content')


<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>

</style>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

        </div>
        @endif

        <h3 class="card-title">Danh sách cơ sở</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-trinhdo">+ Thêm mới</button>

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
                        <!-- <th>Mã ca học</th> -->
                        <th>Tên cơ sở</th>
                        <th> Địa chỉ</th>
                        <th>Số điện thoại </th>
                        <th>Email</th>
                        <th>Mô tả</th>

                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dscoso as $td)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <!-- <td>{{ $td->maca }}</td> -->
                        <td>{{ $td->tencoso }}</td>
                        <td>{!!$td->diachi !!}</td>
                        <td>{{ $td->sdt}}</td>
                        <td>{{ $td->email }}</td>
                        <td>{{ $td->mota }}</td>

                        <td class="col-action">
                            <!-- <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-trinhdo"
                                data-id="{{ $td->id }}"
                                data-tencoso="{{ $td->tencoso }}"
                                data-diachi="{{ $td->diachi }}"
                                data-sdt="{{ $td->sdt }}"
                                data-email="{{ $td->email }}"
                                data-mota="{{ $td->mota }}">
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
                {{ $dscoso->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <!-- Popup thêm coso-->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Thêm cơ sở</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm" action="{{ route('coso.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="tencoso" class="form-label">Tên cơ sở</label>
                            <input type="text" class="form-control" id="tencoso" name="tencoso" required placeholder="Nhâp tên cơ sở">
                        </div>
                        <div class="mb-3">
                            <label for="diachi" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="diachi" name="diachi" required placeholder="Nhập địa chỉ">
                        </div>
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="sdt" name="sdt" required placeholder="Nhập số diện thoại">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" required placeholder="Nhập Eamil">
                        </div>
                        <div class="mb-3">
                            <label for="mota" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="mota" name="mota" placeholder="Nhập Ghi chú"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm cơ sở</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Popup chỉnh sửa ca học -->
    <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">Chỉnh sửa cơ sở</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editClassForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="tencoso" class="form-label">Tên cơ sở</label>
                            <input type="text" class="form-control" id="tencoso" name="tencoso" required placeholder="Nhâp tên cơ sở">
                        </div>
                        <div class="mb-3">
                            <label for="diachi" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="diachi" name="diachi" required placeholder="Nhập địa chỉ">
                        </div>
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="sdt" name="sdt" required placeholder="Nhập số diện thoại">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" required placeholder="Nhập Eamil">
                        </div>
                        <div class="mb-3">
                            <label for="mota" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="mota" name="mota" placeholder="Nhập Ghi chú"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.btn-trinhdo').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addClassModal'));
            myModal.show();
        });
        document.querySelectorAll('.btn-sua-trinhdo').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                const tencoso = this.dataset.tencoso;
                const diachi = this.dataset.diachi;
                const sdt = this.dataset.sdt;
                const email = this.dataset.email;
                const mota = this.dataset.mota;

                const form = document.getElementById('editClassForm');
                form.action = `/admin/coso/update/${id}`; // Cập nhật route PUT tương ứng

                form.querySelector('#tencoso').value = tencoso;
                form.querySelector('#diachi').value = diachi;
                form.querySelector('#sdt').value = sdt;
                form.querySelector('#email').value = email;
                form.querySelector('#mota').value = mota;
                const editModal = new bootstrap.Modal(document.getElementById('editClassModal'));
                editModal.show();
            });
        });
    </script>





    <script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
    @endsection