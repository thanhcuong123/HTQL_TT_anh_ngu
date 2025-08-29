@extends('index')

@section('title-content')
<title>Trình độ</title>
@endsection

@section('main-content')


<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<style>

</style>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

        </div>
        @endif

        <h3 class="card-title">Danh sách trình độ</h3>
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
                        <th>Mã trình độ</th>
                        <th>Tên trình độ</th>
                        <th>Kỹ năng</th>
                        <!-- <th>Học phí</th>
                        <th>Năm Học</th> -->
                        <th>Mô tả</th>

                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dstrinhdo as $td)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $td->ma }}</td>
                        <td>{{ $td->ten }}</td>

                        <td>
                            {{ $td->kynangs->isNotEmpty() ? $td->kynangs->pluck('ten')->join(', ') : 'chưa có' }}
                        </td>

                        <!-- <td>
                            @if ($td->dongia)
                            {{ number_format($td->dongia->hocphi, 0, ',', '.') }} VNĐ
                            @else
                            Chưa có
                            @endif
                        </td>
                        <td>{{ $td->dongia?->namhoc?->nam ?? 'Chưa có' }}</td> -->
                        <td>{!!$td->mota !!}</td>

                        <td class="col-action">
                            <!-- <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->


                            <form action="{{ route('trinhdo.destroy',$td->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $dstrinhdo->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    @include('admin.trinhdo.add')
    @include('admin.trinhdo.update')


    <!-- <div id="popup-sua-trinhdo" class="popup">
        <div class="popup-close" onclick="dongPopupSua()">&times;</div>

        <div class="popup-content">
            <h3>Sửa trình độ</h3>
            <form id="form-sua-trinhdo" method="POST">
                @csrf
                @method('PUT') 
                <label for="ma_sua">Mã trình độ</label>
                <input type="text" name="ma_hienthi_sua" id="ma_hienthi_sua" class="form-control" disabled>
                <input type="hidden" name="ma_sua" id="ma_sua">

                <label for="ten_sua">Tên trình độ</label>
                <input type="text" name="ten_sua" id="ten_sua" required placeholder="Ví dụ Tiếng anh giao tiếp" class="form-control">

                <label for="kynang_id">Kỹ năng liên quan</label>
                <select name="kynang_id_sua" id="kynang_id_sua" class="form-control">
                    <option value="">-- Chọn kỹ năng --</option>
                    @foreach ($dsKyNang as $kynang)
                    <option value="{{ $kynang->id }}">{{ $kynang->ten }}</option>
                    @endforeach
                </select>
                <label for="mota_sua">Mô tả trình độ</label>
                <div id="editor-container-sua" style="height: 200px;"></div>
                <input type="hidden" name="mota_sua" id="mota_sua">
                <div class="popup-buttons">
                    <button type="submit" class="btn btn-success">Lưu</button>
                    <button type="button" class="btn btn-secondary" onclick="dongPopupSua()">Hủy</button>

                </div>
            </form>
        </div>
    </div> -->

</div>



<script>
    $(document).ready(function() {
        // Tìm kiếm theo ajax
        $("#search").on("keyup", function() {
            let tu_khoa = $(this).val();

            $.ajax({
                url: "{{ route('trinhdo.search') }}",
                type: "GET",
                data: {
                    tu_khoa: tu_khoa
                },
                success: function(response) {
                    $("#kq-timkiem").html(response);
                },
                // error: function() {
                //     $("#kq-timkiem").html('<tr><td colspan="6" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>');
                // }
            });
        });

        // Mở popup khi click nút "Thêm mới"
        // $(".btn-trinhdo").on("click", function() {
        //     moPopup();
        // });
    });

    var quill; // Khai báo biến quill ở scope rộng hơn để có thể truy cập từ các hàm khác

    $(document).ready(function() {
        // ... (các script tìm kiếm theo ajax và nút "Thêm mới" hiện có của bạn)

        // Khởi tạo Quill editor

    });

    // Xử lý khi submit form để lấy nội dung HTML từ Quill
    // Đảm bảo bạn gán HTML vào input hidden 'mota' trước khi gửi form
    $('form').on('submit', function(e) {
        // Kiểm tra xem đây có phải form thêm/sửa trình độ không
        // Dùng selector cụ thể hơn nếu bạn có nhiều form trên trang
        if ($(this).attr('action') === "{{ route('trinhdo.store') }}" || $(this).hasClass('edit-trinhdo-form')) {
            // Lấy nội dung HTML từ Quill editor
            let htmlContent = quill.root.innerHTML;
            // Gán giá trị HTML này vào trường input hidden 'mota'
            $('#mota').val(htmlContent);
        }

    });

    // Hàm để mở popup
    function openTrinhDoPopup() {
        $('#popup-trinhdo').css('display', 'flex'); // Hiện popup với flexbox để căn giữa
        quill.setContents([]); // Xóa nội dung cũ trong editor
    }

    // Hàm để đóng popup (đổi tên để tránh xung đột với window.close())
    function closeTrinhDoPopup() {
        $('#popup-trinhdo').css('display', 'none'); // Ẩn popup
    }

    // Gán sự kiện click cho nút "Thêm mới"
    $(".btn-trinhdo").on("click", function() {
        openTrinhDoPopup();
    });

    $('.popup-buttons .btn-secondary').on('click', function() {
        closeTrinhDoPopup();

    });










    $(document).ready(function() {
        // Mở popup sửa khi click nút sửa
        $(document).on("click", ".btn-sua-trinhdo", function() {
            var ma = $(this).data("id");
            var ten = $(this).data("ten");
            var mota = $(this).data("mota");



            // Điền dữ liệu vào form sửa
            $("#ma_hienthi_sua").val(ma);
            $("#ma_sua").val(ma);
            $("#ten_sua").val(ten);
            quillSua.root.innerHTML = mota;


            // Thay đổi action form sửa với route update (có thể theo chuẩn RESTful)
            $("#form-sua-trinhdo").attr("action", "/admin/trinhdo/update/" + ma);

            // Mở popup sửa
            $("#popup-sua-trinhdo").addClass("open");
        });

        // Khi submit form sửa, cập nhật mô tả trước submit
        $("#form-sua-trinhdo").on("submit", function() {
            var motaSua = quillSua.root.innerHTML;
            $("#mota_sua").val(motaSua);
        });
    });

    // Hàm đóng popup sửa
    function dongPopupSua() {
        $("#popup-sua-trinhdo").removeClass("open");
    }
</script>
<script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
@endsection