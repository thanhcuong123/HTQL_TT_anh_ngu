@extends('index')

@section('title-content')
<title>Quản lý chuyên môn</title>
@endsection

@section('main-content')

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <h3 class="card-title">Danh sách chuyên môn</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-add">+ Thêm mới</button>

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
                        <th>Mã chuyên môn</th>
                        <th>Tên chuyên môn</th>
                        <th>Mô tả</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dsChuyenMon as $cm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cm->machuyenmon }}</td>
                        <td>{{ $cm->tenchuyenmon }}</td>
                        <td>{{ $cm->mota }}</td>
                        <td class="col-action">
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-edit"
                                data-id="{{ $cm->id }}"
                                data-ma="{{ $cm->machuyenmon }}"
                                data-ten="{{ $cm->tenchuyenmon }}"
                                data-mota="{{ $cm->mota }}">
                                Sửa
                            </a>
                            <form action="{{ route('chuyenmon.destroy', $cm->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $dsChuyenMon->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <!-- Popup thêm chuyên môn -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm chuyên môn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" action="{{ route('chuyenmon.store') }}" method="POST">
                        @csrf
                        <!-- <div class="mb-3">
                            <label for="machuyenmon" class="form-label">Mã chuyên môn</label>
                            <input type="text" class="form-control" id="machuyenmon"
                                name="machuyenmon" value="{{ $nextMa }}" readonly>
                        </div> -->

                        <div class="mb-3">
                            <label for="tenchuyenmon" class="form-label">Tên chuyên môn</label>
                            <input type="text" class="form-control" id="tenchuyenmon" name="tenchuyenmon" required placeholder="Nhập tên chuyên môn">
                        </div>
                        <div class="mb-3">
                            <label for="mota" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="mota" name="mota" placeholder="Nhập mô tả"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup sửa chuyên môn -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Chỉnh sửa chuyên môn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="machuyenmon" class="form-label">Mã chuyên môn</label>
                            <input type="text" class="form-control" id="edit_ma" name="machuyenmon" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenchuyenmon" class="form-label">Tên chuyên môn</label>
                            <input type="text" class="form-control" id="edit_ten" name="tenchuyenmon" required>
                        </div>
                        <div class="mb-3">
                            <label for="mota" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="edit_mota" name="mota"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // mở popup thêm
    document.querySelector('.btn-add').addEventListener('click', function() {
        var myModal = new bootstrap.Modal(document.getElementById('addModal'));
        myModal.show();
    });

    // mở popup sửa
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const ma = this.dataset.ma;
            const ten = this.dataset.ten;
            const mota = this.dataset.mota;

            const form = document.getElementById('editForm');
            form.action = `/admin/chuyenmon/update/${id}`;

            document.getElementById('edit_ma').value = ma;
            document.getElementById('edit_ten').value = ten;
            document.getElementById('edit_mota').value = mota;

            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });
    });
</script>

@endsection