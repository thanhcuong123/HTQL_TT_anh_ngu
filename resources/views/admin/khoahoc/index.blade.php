@extends('index')

@section('title-content')
<title>Khóa học</title>
@endsection

@section('main-content')



<style>
    /* Popup style */
</style>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

        </div>
        @endif

        <h3 class="card-title">Danh sách khóa học</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-them-khoahoc">+ Thêm mới</button>

            <form class="search-form" action="" method="GET" style="position: relative;">
                <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                <a href="{{ route('khoahoc.index') }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>xóa lọc</a>
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
                        <th>Mã khóa học</th>
                        <th>Tên khóa học</th>
                        <th>Thời lượng</th>
                        <th>Số buổi</th>
                        <th>Hình ảnh</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dsKhoaHoc as $kh)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kh->ma }}</td>
                        <td>{{ $kh->ten }}</td>
                        <td>{{ $kh->thoiluong }}</td>
                        <td>{{ $kh->sobuoi }}</td>
                        <td>
                            @if($kh->hinhanh)
                            {{-- Sử dụng asset() để tạo đường dẫn công khai đến ảnh --}}
                            <img src="{{ asset('storage/' . $kh->hinhanh) }}" alt="Hình ảnh khóa học {{ $kh->ten }}" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                            {{-- Hoặc bạn có thể dùng Storage::url() nếu thích: --}}
                            {{-- <img src="{{ Storage::url($kh->hinhanh) }}" alt="Hình ảnh khóa học {{ $kh->ten }}" style="max-width: 100px; max-height: 100px; object-fit: cover;"> --}}
                            @else
                            Không có ảnh
                            @endif
                        </td>
                        <td class="col-action">
                            <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-khoahoc"
                                data-id="{{ $kh->ma }}"
                                data-ten="{{ $kh->ten }}"
                                data-mota="{!!   htmlspecialchars($kh->mota) !!}"
                                data-thoiluong="{{ $kh->thoiluong }}"
                                data-sobuoi="{{ $kh->sobuoi }}">
                                Sửa
                            </a>

                            <form action="{{ route('khoahoc.destroy',$kh->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $dsKhoaHoc->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- POPUP Thêm khóa học -->
<div id="popup-them-khoahoc" class="popup">
    <div class="popup-close" onclick="dongPopup()">&times;</div>

    <div class="popup-content">
        <h3>Thêm khóa học</h3>
        <form action="{{ route('khoahoc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="ma">Mã khóa học</label>
            <input type="text" name="ma_hienthi" id="ma_hienthi" value="{{ $newMa }}" class="form-control" disabled>
            <input type="hidden" name="ma" value="{{ $newMa }}">
            <label for="ten">Tên khóa học</label>
            <input type="text" name="ten" id="ten" required placeholder="Ví dụ Tiếng anh giao tiếp">
            <label for="mota">Mô tả khóa học</label>
            <div id="editor-container" style="height: 200px;"></div>
            <input type="hidden" name="mota" id="mota">

            <label for="thoiluong">Thời lượng</label>
            <input type="text" name="thoiluong" id="thoiluong" required placeholder=" Ví dụ 52 tuần">

            <label for="sobuoi">Số buổi</label>
            <input type="number" name="sobuoi" id="sobuoi" required placeholder="Ví dụ 20 buổi">
            <label for="hinhanh">Hình ảnh</label>
            <input type="file" name="hinhanh" id="hinhanh" accept="image/*" class="form-control">
            <div class="popup-buttons">
                <button type="submit" class="btn btn-success">Lưu</button>
                <button type="button" class="btn btn-secondary" onclick="dongPopup()">Hủy</button>
            </div>
        </form>
    </div>
</div>
<!-- POPUP Sửa khóa học -->
<div id="popup-sua-khoahoc" class="popup">
    <div class="popup-close" onclick="dongPopupSua()">&times;</div>

    <div class="popup-content">
        <h3>Sửa khóa học</h3>
        <form id="form-sua-khoahoc" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- hoặc PATCH -->
            <label for="ma_sua">Mã khóa học</label>
            <input type="text" name="ma_hienthi_sua" id="ma_hienthi_sua" class="form-control" disabled>
            <input type="hidden" name="ma_sua" id="ma_sua">

            <label for="ten_sua">Tên khóa học</label>
            <input type="text" name="ten_sua" id="ten_sua" required placeholder="Ví dụ Tiếng anh giao tiếp" class="form-control">

            <label for="mota_sua">Mô tả khóa học</label>
            <div id="editor-container-sua" style="height: 200px;"></div>
            <input type="hidden" name="mota_sua" id="mota_sua">

            <label for="thoiluong_sua">Thời lượng</label>
            <input type="text" name="thoiluong_sua" id="thoiluong_sua" required placeholder="Ví dụ 52 tuần" class="form-control">

            <label for="sobuoi_sua">Số buổi</label>
            <input type="number" name="sobuoi_sua" id="sobuoi_sua" required placeholder="Ví dụ 20 buổi" class="form-control">

            <div class="popup-buttons">
                <button type="submit" class="btn btn-success">Lưu</button>
                <button type="button" class="btn btn-secondary" onclick="dongPopupSua()">Hủy</button>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Tìm kiếm theo ajax
        $("#search").on("keyup", function() {
            let tu_khoa = $(this).val();

            $.ajax({
                url: "{{ route('khoahoc.search') }}",
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

        // Mở popup khi click nút "Thêm mới"
        $(".btn-them-khoahoc").on("click", function() {
            moPopup();
        });
    });
</script>
<script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
@endsection