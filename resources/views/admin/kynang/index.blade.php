@extends('index')

@section('title-content')
<title>Kỹ năng</title>
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

        <h3 class="card-title">Danh sách kỹ năng</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-them-khoahoc">Thêm mới</button>

            <form class="search-form" action="" method="GET" style="position: relative;">
                <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                <a href="{{ route('kynang.index') }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>xóa lọc</a>
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
                        <th>Mã kỹ năng</th>
                        <th>Tên kỹ năng</th>

                        <th>Mô tả</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dsKynang as $kh)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kh->ma }}</td>
                        <td>{{ $kh->ten }}</td>
                        <td>{{ $kh->mota }}</td>

                        <td class="col-action">
                            <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                            <!-- <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-khoahoc"
                                data-id="{{ $kh->ma }}"
                                data-ten="{{ $kh->ten }}"
                                data-mota="{!!   htmlspecialchars($kh->mota) !!}"
                                data-thoiluong="{{ $kh->thoiluong }}"
                                data-sobuoi="{{ $kh->sobuoi }}">
                                Sửa
                            </a> -->

                            <form action="{{ route('kynang.destroy',[$kh->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $dsKynang->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        // Tìm kiếm theo ajax
        $("#search").on("keyup", function() {
            let tu_khoa = $(this).val();

            $.ajax({
                url: "{{ route('kynang.search') }}",
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