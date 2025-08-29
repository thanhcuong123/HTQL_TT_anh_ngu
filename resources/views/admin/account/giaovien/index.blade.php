@extends('index')

@section('title-content')
<title>Quản lý tài khoản Giáo viên</title>
@endsection

@section('main-content')

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h3 class="card-title">Quản lý tài khoản Giáo viên</h3>

        <!-- Form tìm kiếm -->
        <form method="GET" class="mb-3 d-flex justify-content-end">
            <input type="search" name="tu_khoa" class="form-control w-25" placeholder="Nhập tên hoặc mã HV"
                value="{{ request('tu_khoa') }}">
            <!-- <button class="btn btn-primary ms-2">Tìm kiếm</button> -->
            <a href="{{ route('admin.giaovien.accountIndex') }}" class="btn btn-primary ms-2" style="margin-left:20px">Xoá lọc</a>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã giáo viên</th>
                        <th>Tên giáo viên</th>
                        <th>Email đăng nhập</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dshocvien as $hv)
                    <tr>
                        <td>{{ $loop->iteration + ($dshocvien->currentPage() - 1) * $dshocvien->perPage() }}</td>
                        <td>{{ $hv->magiaovien }}</td>
                        <td>{{ $hv->ten }}</td>
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
                                data-ma="{{ $hv->magiaovien }}">
                                Tạo tài khoản
                            </button>

                            @else
                            <form action="{{ route('giaovien.lockAccount', $hv->user->id) }}" method="POST" style="display: inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn Khoá tài khoản này?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-warning">Khoá tài khoản</button>
                            </form>
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
        <form action="{{ route('giaovien.createAccount') }}" method="POST">
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

        let email = `${toSlug(ten)}${ma}@teacher.edu.vn`;

        $('#modal_hocvien_id').val(id);
        $('#modalTaoTaiKhoan input[name="email"]').val(email);

        $('#modalTaoTaiKhoan').modal('show');
    });
</script>


@endsection