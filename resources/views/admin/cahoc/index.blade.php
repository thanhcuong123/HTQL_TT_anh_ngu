@extends('index')

@section('title-content')
<title>Ca hoc</title>
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

        <h3 class="card-title">Danh sách ca học</h3>
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
                        <th>Stt ca</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th>Ghi chú</th>

                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dscahoc as $td)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <!-- <td>{{ $td->maca }}</td> -->
                        <td>{{ $td->tenca }}</td>
                        <td>{!!$td->thoigianbatdau !!}</td>
                        <td>{{ $td->thoigianketthuc}}</td>
                        <td>{{ $td->ghichu }}</td>

                        <td class="col-action">
                            <!-- <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-trinhdo"
                                data-id="{{ $td->id }}"
                                data-tenca="{{ $td->tenca }}"
                                data-thoigianbatdau="{{ $td->thoigianbatdau }}"
                                data-thoigianketthuc="{{ $td->thoigianketthuc }}"
                                data-ghichu="{{ $td->ghichu }}">
                                Sửa
                            </a>


                            <form action="{{ route('cahoc.destroy',[$td->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $dscahoc->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <!-- Popup thêm ca học -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Thêm ca học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm" action="{{ route('cahoc.store')}}" method="POST">
                        @csrf
                        <!-- <div class="mb-3">
                            <label for="maca" class="form-label">Mã ca học</label>
                            <input type="text" class="form-control" id="maca" name="maca" required>
                        </div> -->
                        <div class="mb-3">
                            <label for="tenca" class="form-label">Tên ca</label>
                            <input type="text" class="form-control" id="tenca" name="tenca" required>
                        </div>
                        <div class="mb-3">
                            <label for="thoigianbatdau" class="form-label">Thời gian bắt đầu</label>
                            <input type="text" class="form-control" id="thoigianbatdau" name="thoigianbatdau" required>
                        </div>
                        <div class="mb-3">
                            <label for="thoigianketthuc" class="form-label">Thời gian kết thúc</label>
                            <input type="text" class="form-control" id="thoigianketthuc" name="thoigianketthuc" required>
                        </div>
                        <div class="mb-3">
                            <label for="ghichu" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="ghichu" name="ghichu"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm ca học</button>
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
                    <h5 class="modal-title" id="editClassModalLabel">Chỉnh sửa ca học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editClassForm" method="POST" action="">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="edit_tenca" class="form-label">Tên ca</label>
                            <input type="text" class="form-control" id="edit_tenca" name="tenca" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_thoigianbatdau" class="form-label">Thời gian bắt đầu</label>
                            <input type="text" class="form-control" id="edit_thoigianbatdau" name="thoigianbatdau" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_thoigianketthuc" class="form-label">Thời gian kết thúc</label>
                            <input type="text" class="form-control" id="edit_thoigianketthuc" name="thoigianketthuc" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ghichu" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="edit_ghichu" name="ghichu"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script>
        flatpickr("#thoigianbatdau", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", // Định dạng 24 giờ
            time_24hr: true // Bật chế độ 24 giờ
        });
        flatpickr("#thoigianketthuc", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", // Định dạng 24 giờ
            time_24hr: true // Bật chế độ 24 giờ
        });
        document.querySelector('.btn-trinhdo').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addClassModal'));
            myModal.show();
        });

        // Khởi tạo flatpickr 24h cho các input chọn giờ trong form chỉnh sửa
        flatpickr("#edit_thoigianbatdau", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
        flatpickr("#edit_thoigianketthuc", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
        // Sự kiện mở modal và đổ dữ liệu vào form
        document.querySelectorAll('.btn-sua-trinhdo').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                const tenca = this.dataset.tenca;
                const thoigianbatdau = this.dataset.thoigianbatdau;
                const thoigianketthuc = this.dataset.thoigianketthuc;
                const ghichu = this.dataset.ghichu;
                const form = document.getElementById('editClassForm');
                form.action = `/admin/cahoc/update/${id}`; // Cập nhật route PUT tương ứng

                form.querySelector('#edit_tenca').value = tenca;
                form.querySelector('#edit_thoigianbatdau').value = thoigianbatdau;
                form.querySelector('#edit_thoigianketthuc').value = thoigianketthuc;
                form.querySelector('#edit_ghichu').value = ghichu;
                const editModal = new bootstrap.Modal(document.getElementById('editClassModal'));
                editModal.show();
            });
        });
    </script>

    <script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
    @endsection