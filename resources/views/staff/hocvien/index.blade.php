@extends('staff.index')

@section('title-content')
<title>Học viên</title>
@endsection

@section('staff-content')

<link rel="stylesheet" href="{{ asset('admin/luanvantemplate/dist/css/hocvien.css') }}">
<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

        </div>
        @endif



        <h3 class="card-title">Danh sách học viên</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-them-hocvien">+ Thêm mới</button>
            <form class="search-form" action="" method="GET" style="position: relative;">
                <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                <a href="{{ route('staff.hocvien') }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>xóa lọc</a>
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
                        <th>Trạng thái</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dshocvien as $kh)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kh->mahocvien }}</td>
                        <td>{{ $kh->ten }}</td>
                        <td>{{ $kh->user->email}}</td>
                        <td>{{ $kh->sdt }}</td>
                        <td>{{ $kh->diachi }}</td>
                        <td>{{ $kh->ngaysinh}}</td>
                        <td>{{ $kh->gioitinh }}</td>
                        <td>{{ $kh->ngaydangki }}</td>
                        <td>{{ $kh->trangthai }}</td>
                        <td class="col-action">
                            <!-- <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-hocvien"
                                data-id="{{ $kh->id }}"
                                data-ma="{{ $kh->mahocvien }}"
                                data-ten="{{ $kh->ten }}"
                                data-email="{{ $kh->user->email ?? '' }}"
                                data-sdt="{{ $kh->sdt }}"
                                data-diachi="{{ $kh->diachi }}"
                                data-ngaysinh="{{ $kh->ngaysinh }}"
                                data-gioitinh="{{ $kh->gioitinh }}"
                                data-ngaydangki="{{ $kh->ngaydangki }}"
                                data-trangthai="{{ $kh->trangthai }}">
                                Sửa
                            </a>

                            <form action="{{ route('staff.hocvien.destroy',$kh->id)}}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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



    <!-- Popup Thêm Học Viên Mới -->
    <div class="popup-overlay" id="addHocVienPopup">
        <div class="sidebar-popup">
            <div class="popup-header">
                <h4>Thêm Học Viên Mới</h4>
                <button type="button" class="popup-close">&times;</button>
            </div>
            <form action="{{ route('staff.hocvien.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="ma_hoc_vien">Mã học viên:</label>
                    <input type="text" name="mahocvien" class="form-control" id="ma_hoc_vien" value="{{ $newMa }}" disabled>
                    <input type="hidden" name="mahocvien" value="{{ $newMa }}">
                </div>
                <div class="form-group mb-3">
                    <label for="ten_hoc_vien">Tên học viên:</label>
                    <input type="text" class="form-control" id="ten_hoc_vien" name="ten" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email_user">Email (liên kết tài khoản người dùng):</label>
                    <input type="email" class="form-control" id="email_user" name="email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="sdt_hoc_vien">Số điện thoại:</label>
                    <input type="text" class="form-control" id="sdt_hoc_vien" name="sdt" required>
                </div>
                <div class="form-group mb-3">
                    <label for="diachi_hoc_vien">Địa chỉ:</label>
                    <input type="text" class="form-control" id="diachi_hoc_vien" name="diachi" required>
                </div>
                <div class="form-group mb-3">
                    <label for="ngaysinh_hoc_vien">Ngày sinh:</label>
                    <input type="date" class="form-control" id="ngaysinh_hoc_vien" name="ngaysinh" required>
                </div>
                <div class="form-group mb-3">
                    <label for="gioitinh_hoc_vien">Giới tính:</label>
                    <select class="form-control" id="gioitinh_hoc_vien" name="gioitinh" required>
                        <option value="">Chọn giới tính</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="ngaydangki_hoc_vien">Ngày đăng kí:</label>
                    <input type="date" class="form-control" id="ngaydangki_hoc_vien" name="ngaydangki" required>
                </div>
                <div class="form-group mb-3">
                    <label for="trangthai_hoc_vien">Trạng thái:</label>
                    <select class="form-control" id="trangthai_hoc_vien" name="trangthai" required>
                        <option value="Đang học">Đang học</option>
                        <option value="Đã tốt nghiệp">Đã tốt nghiệp</option>
                        <option value="Bảo lưu">Bảo lưu</option>

                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-save-hocvien">Lưu Học Viên</button>
            </form>
        </div>
    </div>
    <div class="popup-overlay" id="editHocVienPopup">
        <div class="sidebar-popup">
            <div class="popup-header">
                <h4>Chỉnh Sửa Học Viên</h4>
                <button type="button" class="popup-close" data-target="editHocVienPopup">&times;</button>
            </div>
            <form id="editHocVienForm" method="POST">
                @csrf
                @method('PUT') {{-- Sử dụng phương thức PUT cho cập nhật --}}
                <input type="hidden" id="edit_hoc_vien_id" name="id"> {{-- Để lưu ID học viên cần chỉnh sửa --}}
                <div class="form-group mb-3">
                    <label for="edit_ma_hoc_vien">Mã học viên:</label>
                    <input type="text" class="form-control" id="edit_ma_hoc_vien" name="mahocvien" disabled>
                    <input type="hidden" name="ma_sua" id="ma_sua">


                </div>
                <div class="form-group mb-3">
                    <label for="edit_ten_hoc_vien">Tên học viên:</label>
                    <input type="text" class="form-control" id="edit_ten_hoc_vien" name="ten" reqired>
                </div>
                <div class="form-group mb-3">
                    <label for="edit_email_user">Email (liên kết tài khoản người dùng):</label>
                    <input type="email" class="form-control" id="edit_email_user" name="email">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_sdt_hoc_vien">Số điện thoại:</label>
                    <input type="text" class="form-control" id="edit_sdt_hoc_vien" name="sdt">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_diachi_hoc_vien">Địa chỉ:</label>
                    <input type="text" class="form-control" id="edit_diachi_hoc_vien" name="diachi">
                </div>
                <div class="form-group mb-3">
                    <label for="edit_ngaysinh_hoc_vien">Ngày sinh:</label>
                    <input type="date" class="form-control" id="edit_ngaysinh_hoc_vien" name="ngaysinh" reqired>
                </div>
                <div class="form-group mb-3">
                    <label for="edit_gioitinh_hoc_vien">Giới tính:</label>
                    <select class="form-control" id="edit_gioitinh_hoc_vien" name="gioitinh">
                        <option value="">Chọn giới tính</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="edit_ngaydangki_hoc_vien">Ngày đăng kí:</label>
                    <input type="date" class="form-control" id="edit_ngaydangki_hoc_vien" name="ngaydangki" required>
                </div>
                <div class="form-group mb-3">
                    <label for="edit_trangthai_hoc_vien">Trạng thái:</label>
                    <select class="form-control" id="edit_trangthai_hoc_vien" name="trangthai" required>
                        <option value="Đang học">Đang học</option>
                        <option value="Đã tốt nghiệp">Đã tốt nghiệp</option>
                        <option value="Bảo lưu">Bảo lưu</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-save-hocvien">Cập nhật Học Viên</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Tìm kiếm theo ajax
        $("#search").on("keyup", function() {
            let tu_khoa = $(this).val();

            $.ajax({
                url: "{{ route('staff.hocvien.search') }}",
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

        // });

        function moPopup(popupId) {
            $("#" + popupId).css("display", "flex");
            setTimeout(function() {
                $("#" + popupId + " .sidebar-popup").addClass("open");
            }, 10);
        }

        // Hàm đóng popup
        function dongPopup(popupId) {
            $("#" + popupId + " .sidebar-popup").removeClass("open");
            setTimeout(function() {
                $("#" + popupId).css("display", "none");
            }, 300);
        }

        // Mở popup "Thêm mới"
        $(".btn-them-hocvien").on("click", function() {
            moPopup("addHocVienPopup");
            // Xóa dữ liệu cũ trong form thêm mới khi mở popup
            $("#addHocVienPopup form")[0].reset();
        });

        // Mở popup "Chỉnh sửa" và điền dữ liệu
        $(".btn-sua-hocvien").on("click", function() {
            let data = $(this).data(); // Lấy tất cả data-* attributes

            // Điền dữ liệu vào form chỉnh sửa
            $("#edit_hoc_vien_id").val(data.id);
            $("#edit_ma_hoc_vien").val(data.ma);
            $("#edit_ten_hoc_vien").val(data.ten);
            $("#edit_email_user").val(data.email);
            $("#edit_sdt_hoc_vien").val(data.sdt);
            $("#edit_diachi_hoc_vien").val(data.diachi);
            $("#edit_ngaysinh_hoc_vien").val(data.ngaysinh);
            $("#edit_gioitinh_hoc_vien").val(data.gioitinh);
            $("#edit_ngaydangki_hoc_vien").val(data.ngaydangki);
            $("#edit_trangthai_hoc_vien").val(data.trangthai);

            // Cập nhật action của form chỉnh sửa
            // Sử dụng hàm route() của Laravel để tạo URL chính xác
            $("#editHocVienForm").attr("action", `/staff/hocvien/update/${data.id}`); // Sửa lại URL

            moPopup("editHocVienPopup");
        });

        // Đóng popup khi click nút đóng (x) trên cả hai popup
        $(".popup-close").on("click", function() {
            let targetPopupId = $(this).data("target");
            dongPopup(targetPopupId);
        });

        // Đóng popup khi click ra ngoài popup
        $(".popup-overlay").on("click", function(event) {
            if ($(event.target).hasClass("popup-overlay")) {
                dongPopup(event.target.id);
            }
        });
    });
</script>


@endsection