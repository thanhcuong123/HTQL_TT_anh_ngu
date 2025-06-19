@extends('index')

@section('title-content')
<title>Phòng học</title>
@endsection

@section('main-content')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <h3 class="card-title">Danh sách Phòng học</h3>
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
                        <th>Tên phòng</th>
                        <th>Sức chứa</th>
                        <th>Tầng</th>
                        <th>Nhà học</th>
                        <th>Cơ sở</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dsphong as $td)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $td->tenphong }}</td>
                        <td>{{ $td->succhua }}</td>
                        <td>{{ $td->tang->ten ?? 'N/A' }}</td>
                        <td>{{ $td->tang->nhahoc->ten ?? 'N/A' }}</td>
                        <td>{{ $td->tang->nhahoc->coso->tencoso ?? 'N/A' }}</td>
                        <td class="col-action">
                            <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-sua-trinhdo"
                                data-id="{{ $td->id }}"
                                data-tenphong="{{ $td->tenphong }}"
                                data-succhua="{{ $td->succhua }}"
                                data-tang_id="{{ $td->tang_id }}"
                                data-mota="{{ $td->mota }}">Sửa</a>
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
                {{ $dsphong->appends(request()->all())->links() }}
            </div>
        </div>

        <!-- Popup thêm phòng học -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Thêm Phòng Học</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addRoomForm" action="{{ route('phonghoc.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="tenphong" class="form-label">Tên Phòng</label>
                                <input type="text" class="form-control" id="tenphong" name="tenphong" required placeholder="Nhập tên phòng">
                            </div>
                            <div class="mb-3">
                                <label for="succhua" class="form-label">Sức Chứa</label>
                                <input type="number" class="form-control" id="succhua" name="succhua" required placeholder="Nhập sức chứa">
                            </div>
                            <div class="mb-3">
                                <label for="tang_id" class="form-label">Tầng</label>
                                <select class="form-select" id="tang_id" name="tang_id" required>
                                    <option value="">Chọn tầng</option>
                                    @foreach($tangs as $tang)
                                    <option value="{{ $tang->id }}">
                                        {{ $tang->ten }} - {{ $tang->nhahoc->ten }} - {{ $tang->nhahoc->coso->tencoso }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Thêm Phòng Học</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popup chỉnh sửa phòng học -->
        <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoomModalLabel">Chỉnh sửa Phòng Học</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editRoomForm" method="POST" action="">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="tenphong" class="form-label">Tên Phòng</label>
                                <input type="text" class="form-control" id="tenphong" name="tenphong" required placeholder="Nhập tên phòng">
                            </div>
                            <div class="mb-3">
                                <label for="succhua" class="form-label">Sức Chứa</label>
                                <input type="number" class="form-control" id="succhua" name="succhua" required placeholder="Nhập sức chứa">
                            </div>
                            <div class="mb-3">
                                <label for="tang_id" class="form-label">Tầng</label>
                                <select class="form-select" id="tang_id" name="tang_id" required>
                                    <option value="">Chọn tầng</option>
                                    @foreach($tangs as $tang)
                                    <option value="{{ $tang->id }}">
                                        {{ $tang->ten }} - {{ $tang->nhahoc->ten }} - {{ $tang->nhahoc->coso->tencoso }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    document.querySelector('.btn-trinhdo').addEventListener('click', function() {
        var myModal = new bootstrap.Modal(document.getElementById('addRoomModal'));
        myModal.show();
    });
    document.querySelectorAll('.btn-sua-trinhdo').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;

            const tenphong = this.dataset.tenphong;
            const succhua = this.dataset.succhua;
            const tang_id = this.dataset.tang_id;


            const form = document.getElementById('editRoomForm');
            form.action = `/admin/phonghoc/update/${id}`; // Cập nhật route PUT tương ứng

            form.querySelector('#tenphong').value = tenphong;
            form.querySelector('#succhua').value = succhua;
            form.querySelector('#tang_id').value = tang_id;


            const editModal = new bootstrap.Modal(document.getElementById('editRoomModal'));
            editModal.show();
        });
    });
</script>
<script src="{{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
@endsection