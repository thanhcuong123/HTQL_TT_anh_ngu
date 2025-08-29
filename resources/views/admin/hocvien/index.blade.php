@extends('index')

@section('title-content')
<title>Học viên</title>
@endsection

@section('main-content')

<link rel="stylesheet" href="{{ asset('admin/luanvantemplate/dist/css/hocvien.css') }}">
<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <h3 class="card-title">Danh sách học viên</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-them-hocvien">+ Thêm mới</button>
            <form class="search-form" action="" method="GET" style="position: relative;">
                <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                <a href="{{ route('hocvien.index') }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>xóa lọc</a>
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
                        <th>Mã học viên</th>
                        <th>Tên học viên</th>
                        <th>Email</th>
                        <th>SDT</th>
                        <th>Địa chỉ</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Ngày đăng kí</th>
                        <th>Lớp học đã đăng kí</th>
                        <th>Trạng thái</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dshocvien as $kh)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kh->mahocvien ??'--'}}</td>
                        <td>{{ $kh->ten ??'--'}}</td>
                        <td>{{ $kh->email_hv??'--'}}</td>
                        <td>{{ $kh->sdt??'--' }}</td>
                        <td>{{ $kh->diachi??'--' }}</td>
                        <td>{{ $kh->ngaysinh??'--'}}</td>
                        <td>{{ $kh->gioitinh ??'--'}}</td>
                        <td>{{ $kh->ngaydangki ??'--'}}</td>
                        <td>
                            @if ($kh->lophocs->count() > 0)
                            @foreach ($kh->lophocs as $lh)
                            <span>{{ $lh->tenlophoc }}</span>
                            <br>
                            @endforeach
                            @else
                            Chưa đăng ký
                            @endif
                        </td>
                        <!-- data-email="{{ $kh->user->email ?? '' }}" -->
                        <td>{{ $kh->trangthai }}</td>
                        <td class="col-action">
                            <!-- <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-hocvien"
                                data-id="{{ $kh->id }}"
                                data-ma="{{ $kh->mahocvien }}"
                                data-ten="{{ $kh->ten }}"
                                data-email="{{ $kh->email_hv ?? '' }}"
                                data-sdt="{{ $kh->sdt }}"
                                data-diachi="{{ $kh->diachi }}"
                                data-ngaysinh="{{ $kh->ngaysinh }}"
                                data-gioitinh="{{ $kh->gioitinh }}"
                                data-ngaydangki="{{ $kh->ngaydangki }}"
                                data-trangthai="{{ $kh->trangthai }}">
                                Sửa
                            </a>
                            <!-- <button type="button"
                                class="btn btn-sm btn-success btn-tao-taikhoan"
                                data-id="{{ $kh->id }}"
                                data-mahocvien="{{ $kh->mahocvien }}"
                                data-tenhocvien="{{ $kh->ten }}">Tạo tài khoản</button> -->
                            <form action="{{ route('hocvien.destroy',$kh->id)}}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $dshocvien->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
    <!-- Modal Tạo Tài Khoản -->
    <div class="modal fade" id="modalTaoTaiKhoan" tabindex="-1" aria-labelledby="modalTaoTaiKhoanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="POST" id="form-tao-taikhoan">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTaoTaiKhoanLabel">Tạo tài khoản cho học viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="hocvien_id" id="taikhoan_hocvien_id">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" id="taikhoan_email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu:</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Tạo tài khoản</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- Popup Thêm Học Viên Mới -->
    @include('admin.hocvien.add')

    @include('admin.hocvien.update')
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Xử lý nút Tạo tài khoản
    $('.btn-tao-taikhoan').on('click', function() {
        let id = $(this).data('id');
        let mahocvien = $(this).data('mahocvien');
        let tenhocvien = $(this).data('tenhocvien');

        $('#taikhoan_hocvien_id').val(id);
        $('#taikhoan_email').val(''); // reset nếu cần

        $('#modalTaoTaiKhoan').modal('show');
    });

    $(document).ready(function() {
        // Tìm kiếm theo ajax
        $("#search").on("keyup", function() {
            let tu_khoa = $(this).val();

            $.ajax({
                url: "{{ route('hocvien.search') }}",
                type: "GET",
                data: {
                    tu_khoa: tu_khoa
                },
                success: function(response) {
                    $("#kq-timkiem").html(response);
                },
                error: function() {
                    $("#kq-timkiem").html('<tr><td colspan="6" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>');
                }
            });
        });



        // Cập nhật action của form tìm kiếm và form phân trang để giữ lại các tham số

    });
</script>
<script src="{{ asset('admin/luanvantemplate/dist/js/hocvien.js') }}"></script>

@endsection