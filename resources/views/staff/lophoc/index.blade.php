@extends('staff.index')

@section('staff-content')
<!-- <link rel="stylesheet" href="{{ asset('admin/luanvantemplate/dist/css/hocvien.css') }}"> -->
<style>
    /* body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;

    } */

    .container {
        max-width: 1700px;
        margin: 0 auto;
        padding: 20px;
    }

    h1 {
        text-align: left;
        color: #333;
        margin-bottom: 10px;
    }

    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        /* Adjust card width as needed */
        gap: 30px;
        padding: 20px;
        /* Để nội dung không dính sát viền */
        border: 2px solid #ccc;
        /* Viền xung quanh */
        border-radius: 10px;
        /* Bo góc (tuỳ chọn) */
        /* background-color: #f9f9f9; */
    }

    .course-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    /* Hover effect */
    .course-card:hover {
        transform: translateY(-5px);
        /* Lifts the card slightly */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        /* Stronger shadow on hover */
    }

    .class-name {
        background-color: #222222;
        /* Example blue background */
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.1em;
    }

    .course-details {
        flex-grow: 1;
        /* Allows details section to take up available space */
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        border-bottom: 1px dashed #eee;
        /* Subtle separator */
        padding-bottom: 5px;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .label {
        font-weight: bold;
        color: #555;
        flex-shrink: 0;
        /* Prevent label from shrinking */
        margin-right: 10px;
    }

    .value {
        color: #333;
        text-align: right;
        flex-grow: 1;
    }

    .study-days {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .day-tag {
        background-color: #dc3545;
        /* Red for days, similar to image */
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9em;
        white-space: nowrap;
        /* Prevent day tags from breaking */
    }

    .attendance-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .status {
        color: #6c757d;
        font-style: italic;
    }

    .action-link {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    .form-label {
        margin-left: 20px;
    }

    .khoahocbutton {
        margin-left: 10px;
        /* width: 200px; */
    }
</style>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}

</div>
@endif



<body>
    <div class="card">
        <div class="card-body">
            <h1>Danh sách lớp học</h1>
            <div class="container">
                <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
                    <form action="" method="GET" class="mb-3 d-flex align-items-end gap-3 flex-wrap">
                        <!-- <button type="button" class="btn btn-primary btn-them" style="margin-right: 5px;"> + Thêm mới</button> -->
                        <div>
                            <label for="per_page">Chọn số trang cần hiển thị:</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()" class="form-select form-select-sm w-auto d-inline-block ms-2">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>

                        <div class="form-label">
                            <label for="khoahoc_id" class="form-label">Lọc theo Khóa học:</label>
                            <select name="khoahoc_id" id="khoahoc_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                <option value="">-- Tất cả --</option>
                                @foreach ($khoahocs as $kh)
                                <option value="{{ $kh->id }}" {{ $khoahoc_id == $kh->id ? 'selected' : '' }}>
                                    {{ $kh->ten }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('staff.lophoc') }}" class="btn btn-sm btn-info" style="width:100px;margin-left:10px"><i class="bi bi-eye"></i>xóa lọc</a>
                    </form>
                    <form class="search-form" action="" method="GET" style="position: relative;">
                        <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                        <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                        <a href="{{ route('staff.lophoc') }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>xóa lọc</a>
                    </form>
                </div>
                <div class="course-grid">
                    @if ($dslophoc->count() > 0)
                    @foreach ($dslophoc as $lh)
                    <a href="{{ route('staff.lophoc.show',$lh->id )}}" style="text-decoration: none; color: inherit;">
                        <div class="course-card">
                            <div class="class-name">{{ $lh->tenlophoc }}</div>
                            <div class="course-details">
                                <div class="detail-row">
                                    <span class="label">Mã lớp học:</span>
                                    <span class="value">{!! $lh->malophoc !!}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Ngày bắt đầu :</span>
                                    <span class="value">{!! $lh->ngaybatdau !!}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Ngày kết thúc:</span>
                                    <span class="value">{{ $lh->ngayketthuc }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Trạng thái:</span>
                                    <span class="value">{{ $lh->trangthai }}</span>
                                </div>

                                <div class="attendance-section">
                                    <form action="{{ route('lophoc.destroy', $lh->id) }}" onsubmit="return confirm('Bạn chắc chắn muốn xóa lớp học này?')" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link" style="background: none; border: none; color: #dc3545; cursor: pointer;">Xóa lớp</button>
                                    </form>
                                    <span class="action-link">Xem chi tiết</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach

                    </a>
                    @else
                    <div class="text-center w-100 py-5" style="grid-column: 1 / -1;">
                        <h4 class="text-muted">Không có lớp học nào.</h4>
                    </div>
                    @endif
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $dslophoc->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="popup-overlay" id="addLopHocPopup" style="display: none;">
        <div class="sidebar-popup">
            <div class="popup-header">
                <h4>Thêm Mới Lớp Học</h4>
                <button type="button" class="popup-close" data-target="addLopHocPopup">&times;</button>
            </div>
            <form id="addLopHocForm" action="{{ route('lophoc.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- <div class="form-group mb-3">
                    <label for="malophoc">Mã lớp học:</label>
                    <input type="text" class="form-control" id="malophoc" name="malophoc" required>
                </div> -->

                <label for="malophoc">Mã lớp học</label>
                <input type="text" name="ma_hienthi" id="ma_hienthi" value="{{ $newMa }}" class="form-control" disabled>
                <input type="hidden" name="malophoc" value="{{ $newMa }}">

                <div class="form-group mb-3">
                    <label for="tenlophoc">Tên lớp học:</label>
                    <input type="text" class="form-control" id="tenlophoc" name="tenlophoc" required>
                </div>

                <div class="form-group mb-3">
                    <label for="hinhanh">Hình ảnh lớp học:</label>
                    <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*">
                    <small class="form-text text-muted">Chọn một ảnh cho lớp học (tùy chọn).</small>
                </div>
                <div class="form-group mb-3">
                    <label for="ngaybatdau">Ngày bắt đầu:</label>
                    <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" required>
                </div>

                <div class="form-group mb-3">
                    <label for="ngayketthuc">Ngày kết thúc:</label>
                    <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" required>
                </div>
                <div class="form-group mb-3">
                    <label for="soluonghocvientoida">Số lượng học viên tối đa:</label>
                    <input type="number" class="form-control" id="soluonghocvientoida" name="soluonghocvientoida">
                </div>
                <div class="form-group mb-3">
                    <label for="trinhdo_id">Trình độ:</label>
                    <select name="trinhdo_id" id="trinhdo_id" class="form-select" required>
                        <option value="">-- Chọn trình độ --</option>
                        @foreach ($trinhdos as $td)
                        <option value="{{ $td->id }}">{{ $td->ten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="khoahoc_id">Khóa học:</label>
                    <select name="khoahoc_id" id="khoahoc_id" class="form-select" required>
                        <option value="">-- Chọn khóa học --</option>
                        @foreach ($khoahocs as $kh)
                        <option value="{{ $kh->id }}">{{ $kh->ten }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Thêm Lớp Học</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Tìm kiếm theo ajax
            $("#search").on("keyup", function() {
                let tu_khoa = $(this).val();

                if (tu_khoa.length === 0) {
                    // Nếu ô tìm kiếm trống, redirect về trang danh sách lớp học
                    window.location.href = "{{ route('staff.lophoc') }}";
                } else {
                    $.ajax({
                        url: "{{ route('staff.lophoc.search') }}",
                        type: "GET",
                        data: {
                            tu_khoa: tu_khoa
                        },
                        dataType: 'html', // Thêm dòng này để chỉ rõ kiểu dữ liệu mong muốn
                        success: function(response) {
                            console.log("Phản hồi thành công:", response); // In phản hồi ra console
                            $(".course-grid").html(response); // Cập nhật đúng thẻ .course-grid
                        },
                        error: function(xhr, status, error) {
                            console.error("Lỗi AJAX:", status, error, xhr.responseText); // In lỗi chi tiết ra console
                            $(".course-grid").html('<div class="text-center text-danger">Lỗi tải dữ liệu</div>');
                        }
                    });
                }
            });
        });
    </script>
</body>

@endsection