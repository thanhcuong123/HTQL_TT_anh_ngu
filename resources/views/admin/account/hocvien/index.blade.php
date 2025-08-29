@extends('index')

@section('title-content')
<title>Quản lý tài khoản học viên</title>
@endsection

@section('main-content')

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h3 class="card-title">Quản lý tài khoản học viên</h3>

        <!-- Form tìm kiếm -->
        <form method="GET" class="mb-3 d-flex justify-content-end align-items-center">

            <input type="search" name="tu_khoa" class="form-control w-25 me-2"
                placeholder="Nhập tên hoặc mã HV"
                value="{{ request('tu_khoa') }}">

            <select name="trangthai" class="form-select w-auto me-2">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="active" {{ request('trangthai') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="locked" {{ request('trangthai') == 'locked' ? 'selected' : '' }}>Đã khoá</option>
                <option value="no_account" {{ request('trangthai') == 'no_account' ? 'selected' : '' }}>Chưa có tài khoản</option>
            </select>

            <button class="btn btn-primary">Lọc</button>
            <a href="{{ route('admin.hocvien.accountIndex') }}" class="btn btn-secondary ms-2">Xoá lọc</a>
        </form>


        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã học viên</th>
                        <th>Tên học viên</th>
                        <th>Email</th>
                        <th>Email đăng nhập</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dshocvien as $hv)
                    <tr>
                        <td>{{ $loop->iteration + ($dshocvien->currentPage() - 1) * $dshocvien->perPage() }}</td>
                        <td>{{ $hv->mahocvien }}</td>
                        <td>{{ $hv->ten }}</td>
                        <td>{{ $hv->email_hv ??'--' }}</td>
                        <td>{{ $hv->user->email ?? 'Chưa có tài khoản' }}</td>
                        <td>
                            @if ($hv->user)
                            @if ($hv->user->trangthai)
                            <span class="">Đang hoạt động</span>
                            @else
                            <span class="">Đã khoá</span>
                            @endif
                            @else
                            <span class="">Chưa có tài khoản</span>
                            @endif
                        </td>

                        <td>
                            @if (!$hv->user)
                            <button type="button"
                                class="btn btn-sm btn-primary btn-tao-taikhoan"
                                data-id="{{ $hv->id }}"
                                data-ten="{{ $hv->ten }}"
                                data-ma="{{ $hv->mahocvien }}">
                                Tạo tài khoản
                            </button>
                            @else
                            @if ($hv->user->trangthai == 0)
                            <span class="badge bg-danger">Đã khoá</span>
                            @else
                            <form action="{{ route('hocvien.lockAccount', $hv->user->id) }}" method="POST" style="display: inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn Khoá tài khoản này?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-warning">Khoá tài khoản</button>
                            </form>
                            @endif
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-end">
                {{ $dshocvien->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tạo Tài Khoản -->
<div class="modal fade" id="modalTaoTaiKhoan" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('hocvien.createAccount') }}" method="POST">
            @csrf
            <input type="hidden" name="hocvien_id" id="modal_hocvien_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Email đăng nhập</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mật khẩu</label>
                        <input type="matkhau" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tạo</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('.btn-tao-taikhoan').click(function() {
        let id = $(this).data('id');
        let ten = $(this).data('ten');
        let ma = $(this).data('ma');

        // Hàm chuyển tên thành slug không dấu + nối dấu chấm
        function toSlug(str) {
            str = str.toLowerCase();
            str = str.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // bỏ dấu
            str = str.replace(/đ/g, "d");
            str = str.replace(/\s+/g, ""); // thay khoảng trắng = dấu chấm
            return str;
        }

        let email = `${toSlug(ten)}${ma}@student.edu.vn`;

        $('#modal_hocvien_id').val(id);
        $('#modalTaoTaiKhoan input[name="email"]').val(email);

        $('#modalTaoTaiKhoan').modal('show');
    });
</script>


@endsection