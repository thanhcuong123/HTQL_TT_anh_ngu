@extends('index')

@section('title-content')
<title>Nhân viên</title>
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

        <h3 class="card-title">Danh sách nhân viên</h3>

        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-them-nhanvien">+ Thêm mới</button>

            <form class="search-form" action="" method="GET" style="position: relative;">
                <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                <a href="{{ route('admin.nhanvien') }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xóa lọc</a>
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
                        <th>Mã nhân viên</th>
                        <th>Họ tên</th>

                        <th>SĐT</th>
                        <th>Địa chỉ</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Trạng thái</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dsnhanvien as $nv)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $nv->manhanvien ?? '---' }}</td>
                        <td>{{ $nv->ten ?? '---'}}</td>

                        <td>{{ $nv->sdt ?? '---'}}</td>
                        <td>{{ $nv->diachi ?? '---'}}</td>
                        <td>{{ $nv->ngaysinh ?? '---'}}</td>
                        <td>{{ $nv->gioitinh ?? '---'}}</td>
                        <td>{{ $nv->trangthai ?? '---'}}</td>
                        <td class="col-action">
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-nhanvien"
                                data-id="{{ $nv->id }}"
                                data-ma="{{ $nv->manhanvien }}"
                                data-ten="{{ $nv->ten }}"
                                data-sdt="{{ $nv->sdt }}"
                                data-diachi="{{ $nv->diachi }}"
                                data-ngaysinh="{{ $nv->ngaysinh }}"
                                data-gioitinh="{{ $nv->gioitinh }}"

                                data-trangthai="{{ $nv->trangthai }}">
                                Sửa
                            </a>

                            <form action="{{ route('nhanvien.destroy',$nv->id)}}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $dsnhanvien->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddNhanVien" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('nhanvien.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Nhân Viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Mã nhân viên</label>
                            <!-- <input type="text" name="manhanvien" class="form-control" required> -->
                            <input type="text" name="manhanvien" class="form-control" id="ma_hoc_vien" value="{{ $newMa }}" disabled>
                            <input type="hidden" name="manhanvien" value="{{ $newMa }}">

                        </div>
                        <div class="mb-3">
                            <label>Họ tên</label>
                            <input type="text" name="ten" class="form-control" required placeholder="Nhập tên nhân viên">
                        </div>
                        <!-- <div class="mb-3">
                            <label>Chức danh</label>
                            <select name="chucdanh_id" class="form-control">
                                <option value="">-- Chọn chức danh --</option>
                                @foreach($chucdanhs as $cd)
                                <option value="{{ $cd->id }}">{{ $cd->ten }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="mb-3">
                            <label>SĐT</label>
                            <input type="text" name="sdt" class="form-control" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="mb-3">
                            <label>Địa chỉ</label>
                            <input type="text" name="diachi" class="form-control" placeholder=" Nhập địa chỉ">
                        </div>
                        <div class="mb-3">
                            <label>Ngày sinh</label>
                            <input type="date" name="ngaysinh" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Giới tính</label>
                            <select name="gioitinh" class="form-control">
                                <option value="">-- Chọn --</option>
                                <option value="nam">Nam</option>
                                <option value="nữ">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateNhanVien" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('nhanvien.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="update_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Nhân Viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Mã nhân viên</label>
                            <input type="text" name="manhanvien" id="update_manhanvien" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Họ tên</label>
                            <input type="text" name="ten" id="update_ten" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>SĐT</label>
                            <input type="text" name="sdt" id="update_sdt" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Địa chỉ</label>
                            <input type="text" name="diachi" id="update_diachi" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Ngày sinh</label>
                            <input type="date" name="ngaysinh" id="update_ngaysinh" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Giới tính</label>
                            <select name="gioitinh" id="update_gioitinh" class="form-control">
                                <option value="">-- Chọn --</option>
                                <option value="nam">Nam</option>
                                <option value="nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Trạng thái</label>
                            <input type="text" name="trangthai" id="update_trangthai" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Mở modal thêm nhân viên
        $('.btn-them-nhanvien').on('click', function() {
            $('#modalAddNhanVien').modal('show');
        });

        // Mở modal sửa nhân viên
        $('.btn-sua-nhanvien').on('click', function() {
            let btn = $(this);
            $('#update_id').val(btn.data('id'));
            $('#update_manhanvien').val(btn.data('ma'));
            $('#update_ten').val(btn.data('ten'));
            $('#update_sdt').val(btn.data('sdt'));
            $('#update_diachi').val(btn.data('diachi'));
            $('#update_ngaysinh').val(btn.data('ngaysinh'));
            $('#update_gioitinh').val(btn.data('gioitinh'));

            $('#update_trangthai').val(btn.data('trangthai'));

            $('#modalUpdateNhanVien').modal('show');
        });

        $("#search").on("keyup", function() {
            let tu_khoa = $(this).val();
            $.ajax({
                url: "{{ route('admin.nhanvien') }}",
                type: "GET",
                data: {
                    tu_khoa: tu_khoa
                },
                success: function(response) {
                    $("#kq-timkiem").html($(response).find("#kq-timkiem").html());
                },
                error: function() {
                    $("#kq-timkiem").html('<tr><td colspan="8" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>');
                }
            });
        });
    });
</script>

@endsection