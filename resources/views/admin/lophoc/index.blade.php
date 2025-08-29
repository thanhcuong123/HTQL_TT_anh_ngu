@extends('index') {{-- Đảm bảo bạn extend layout chính của admin --}}

@section('title-content')
<title>Quản Lý Lớp Học</title>
@endsection

@section('main-content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    /* Styles for the new popup */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .popup-overlay.open {
        opacity: 1;
        visibility: visible;
    }

    .sidebar-popup {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 500px;
        transform: translateY(-20px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .popup-overlay.open .sidebar-popup {
        transform: translateY(0);
        opacity: 1;
    }

    .popup-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .popup-header h4 {
        margin: 0;
        font-size: 1.5em;
        color: #333;
    }

    .popup-close {
        background: none;
        border: none;
        font-size: 2em;
        color: #999;
        cursor: pointer;
        transition: color 0.2s;
    }

    .popup-close:hover {
        color: #555;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .btn-primary,
    .btn-secondary {
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .gap-3 {
        gap: 1rem;
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .w-auto {
        width: auto;
    }

    .d-inline-block {
        display: inline-block;
    }

    .ms-2 {
        margin-left: 0.5rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .text-center {
        text-align: center;
    }

    .w-100 {
        width: 100%;
    }

    .py-5 {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .text-muted {
        color: #6c757d;
    }
</style>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        <button type="button" class="btn btn-primary" id="btnOpenAddLopHoc" data-bs-toggle="modal" data-bs-target="#addLopHocModal">
                            + Thêm Lớp Học
                        </button>

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
                                    Khóa {{ $kh->ma }}
                                    <!-- {{ optional($kh->lophocs->first())->trinhdo->ten ?? 'N/A' }}
                                    - (
                                    {{ optional($kh->namhoc)->nam ?? 'N/A'}}) -->
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('lophoc.index') }}" class="btn btn-sm btn-info" style="width:100px;margin-left:10px"><i class="bi bi-eye"></i>xóa lọc</a>
                    </form>
                    <form class="search-form" action="" method="GET" style="position: relative;">
                        <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm" autocomplete="off" class="form-control" />
                        <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                        <a href="{{ route('lophoc.index') }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i>xóa lọc</a>
                    </form>
                </div>
                <div class="course-grid">
                    @if ($dslophoc->count() > 0)
                    @foreach ($dslophoc as $lh)
                    <a href="{{ route('lophoc.show',$lh->id )}}" style="text-decoration: none; color: inherit;">
                        <div class="course-card">
                            <div class="class-name">{{ $lh->tenlophoc }}</div>
                            <div class="course-details">
                                <div class="detail-row">
                                    <span class="label">Mã lớp học:</span>
                                    <span class="value">{!! $lh->malophoc !!}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Trình độ :</span>
                                    <!-- <span class="value">{{ optional($kh->lophocs->first())->trinhdo->ten ?? 'N/A' }} -->
                                    <span class="value">{!! $lh->trinhdo->ten !!}</span>
                                    </span>
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

    {{-- Popup chọn khóa học --}}
    <div class="popup-overlay" id="selectKhoaHocPopup">
        <div class="sidebar-popup">
            <div class="popup-header">
                <h4>Chọn Khóa Học & Trình Độ</h4>
                <button type="button" class="popup-close" data-target="selectKhoaHocPopup">&times;</button>
            </div>

            <div class="mb-3">
                <label for="select_khoahoc_id">Khóa học & Trình độ:</label>
                <select id="select_khoahoc_id" class="form-control" style="height:60px">
                    <option value="">-- Chọn --</option>
                    @foreach ($khoahocs as $kh)
                    @php
                    $uniqueTrinhDos = $kh->lopHocs->pluck('trinhDo')->unique('id')->filter();
                    @endphp
                    @foreach ($uniqueTrinhDos as $trinhDo)
                    <option value="{{ $kh->id }}_{{ $trinhDo->id }}">
                        {{ $kh->ma }} - {{ $trinhDo->ten }}
                    </option>
                    @endforeach
                    @endforeach
                </select>
            </div>
            <form id="autoCreateLopHocForm" action="{{ route('lophoc.autoCreate') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="khoahoc_id" id="hidden_khoahoc_id">
                <input type="hidden" name="trinhdo_id" id="hidden_trinhdo_id">
                <input type="hidden" name="so_lop" id="hidden_so_lop">
                <input type="hidden" name="soluonghocvientoida" id="hidden_soluonghocvientoida">

            </form>
            <div class="mb-3">
                <label for="so_lop_can_tao">Số lượng lớp cần thêm:</label>
                <input type="number" id="so_lop_can_tao" class="form-control" min="1" max="20" value="1">
            </div>
            <div class="mb-3">
                <label for="soluonghocvientoida">Số lượng học viên tối đa:</label>
                <input type="number" id="soluonghocvientoida" class="form-control" min="1" max="1000" value="20" required>
            </div>

            <button type="button" class="btn btn-primary" id="continueAddLopHoc">Tiếp Tục</button>
            <button type="button" class="btn btn-secondary" id="cancelSelectKhoaHoc">Hủy</button>
        </div>
    </div>


    {{-- Popup thêm lớp học --}}
    <!-- Popup Thêm Lớp Học -->
    <div class="modal fade" id="addLopHocModal" tabindex="-1" aria-labelledby="addLopHocModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="addLopHocModalLabel">Thêm Lớp Học Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>

                <div class="modal-body">
                    <form id="addLopHocForm" action="{{ route('lophoc.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">

                                <!-- Tên lớp -->

                                <div class="mb-3">
                                    <label for="khoahoc_id" class="form-label">Chọn Khóa Học</label>
                                    <!-- Chọn Khóa Học (modal) -->
                                    <select name="khoahoc_id" id="khoahoc_id_modal" class="form-control" required style="height:45px">
                                        <option value="">-- Chọn khóa học --</option>
                                        @foreach ($khoahocs as $kh)
                                        <option value="{{ $kh->id }}"
                                            data-ngaybatdau="{{ $kh->ngaybatdau }}"
                                            data-ngayketthuc="{{ $kh->ngayketthuc }}">
                                            {{ $kh->ma }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div id="ngayHocInfo" class="mb-3" style="display: none;">
                                        <p>
                                            <strong>Ngày bắt đầu:</strong> <span id="text_ngaybatdau"></span><br>
                                            <strong>Ngày kết thúc:</strong> <span id="text_ngayketthuc"></span>
                                        </p>
                                    </div>
                                </div>
                                <!-- Ngày bắt đầu + kết thúc: ban đầu ẩn -->



                                <!-- Chọn trình độ -->

                                <div class="mb-3">
                                    <label for="ten_lop" class="form-label">Tên Lớp</label>
                                    <input type="text" class="form-control" name="ten_lop" id="ten_lop" placeholder="Nhập tên lớp học" required>
                                </div>
                                <!-- Số lượng học viên tối đa -->
                                <div class="mb-3">
                                    <label for="soluonghocvientoida" class="form-label">Số lượng học viên tối đa</label>
                                    <input type="number" class="form-control" name="soluonghocvientoida" id="soluonghocvientoida" min="1" placeholder="Nhập số lượng học viên của lớp " required>
                                </div>

                                <!-- Số lượng lớp cần tạo -->
                                <div class="mb-3">
                                    <label for="soluonglop" class="form-label">Số lượng lớp cần tạo</label>
                                    <input type="number" class="form-control" name="soluonglop" id="soluonglop" min="1" placeholder="Nhập số lớp cần tạo" required>
                                </div>

                                <!-- Học phí -->

                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="trinhdo_id" class="form-label">Chọn Trình Độ</label>
                                    <select id="trinhdo_id" name="trinhdo_id" class="form-control" style="height: 45px;">
                                        <option value="">-- Chọn trình độ --</option>
                                        @foreach($trinhdos as $td)
                                        <option value="{{ $td->id }}">{{ $td->ten }}</option>
                                        @endforeach
                                    </select>
                                    <div id="hocphiInfo" class="mb-3" style="display: none;">
                                        <p>
                                            <strong>Năm học:</strong> <span id="ten_namhoc_display"></span><br>
                                            <strong>Học phí:</strong> <span id="hocphi_display"></span>
                                        </p>
                                    </div>
                                    <input type="hidden" name="namhoc_id" id="namhoc_id">
                                </div>
                                <!-- <input type="text" name="hocphi" id="hocphi" class="form-control" readonly> -->




                                <!-- Năm học -->
                                <!-- <div class="mb-3" style="display: none;">
                                    <label for="namhoc" class="form-label">Năm học</label>
                                    <select name="namhoc_id" id="namhoc" class="form-control" required style="height:45px" readonly>
                                        <option value="">-- Chọn Năm học --</option>
                                        @foreach ($namhocs as $nam)
                                        <option value="{{ $nam->id }}">{{ $nam->nam }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="hocphi" class="form-label">Học Phí (VNĐ)</label>
                                    <input type="text" class="form-control" name="hocphi" id="hocphi" placeholder="Nhập học phí" value="{{ old('hocphi') }}" required oninput="formatCurrency(this)">
                                    <div id="hoc_phi_display" style="margin-top: 5px; font-weight: bold;"></div>
                                </div> -->


                                <!-- Hình ảnh -->
                                <div class="mb-3">
                                    <label for="hinhanh" class="form-label">Hình Ảnh</label>
                                    <input type="file" class="form-control" name="hinhanh" id="hinhanh" accept="image/*">
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-3">
                                    <label for="mota" class="form-label">Mô Tả</label>
                                    <textarea name="mota" id="mota" class="form-control" rows="4" placeholder="Nhập mô tả..."></textarea>
                                </div>
                                <!-- Ngày bắt đầu -->

                                <!-- Trong modal thêm lớp -->
                                <input type="hidden" name="ngaybatdau" id="hidden_ngaybatdau">
                                <input type="hidden" name="ngayketthuc" id="hidden_ngayketthuc">

                                <!-- <select name="namhoc_id" id="namhoc" class="form-control" required disabled> -->

                                <!-- Chọn khóa học -->

                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" id="submitBtn" class="btn btn-primary">Lưu Lại</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- Đảm bảo jQuery được load --}}
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> -->
    <script>
        $(function() {
            $('#trinhdo_id').on('change', function() {
                const trinhdoId = $(this).val();

                if (!trinhdoId) {
                    $('#hocphiInfo').hide();
                    $('#submitBtn').prop('disabled', true); // disable nút submit
                    return;
                }

                $.ajax({
                    url: `/get-dongia/${trinhdoId}`,
                    method: 'GET',
                    success: function(res) {
                        if (!res || !res.hocphi) {
                            $('#hocphi_display').text('');
                            $('#ten_namhoc_display').text('');
                            $('#namhoc_id').val('');
                            $('#hocphiInfo').hide();
                            $('#submitBtn').prop('disabled', true); // không cho submit
                            alert('Trình độ này chưa cập nhật đơn giá học phí, Vui lòng cập nhật đơn giá học phí trước hoặc chọn trình độ khác.');
                            return;
                        }

                        // Có dữ liệu học phí
                        const formattedHocPhi = parseInt(res.hocphi).toLocaleString('vi-VN') + ' VNĐ';

                        $('#hocphi_display').text(formattedHocPhi);
                        $('#ten_namhoc_display').text(res.ten_namhoc);
                        $('#namhoc_id').val(res.namhoc_id);

                        $('#hocphiInfo').show();
                        $('#submitBtn').prop('disabled', false); // cho phép submit
                    },
                    error: function() {
                        $('#hocphi_display').text('');
                        $('#ten_namhoc_display').text('');
                        $('#namhoc_id').val('');
                        $('#hocphiInfo').hide();
                        $('#submitBtn').prop('disabled', true); // không cho submit
                        alert('Trình độ này chưa cập nhật đơn giá học phí, Vui lòng cập nhật đơn giá học phí trước hoặc chọn trình độ khác.');
                    }
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const hocPhiInput = document.getElementById('hocphi');
            const hocPhiDisplay = document.getElementById('hoc_phi_display');

            // Khi load lại form (VD: lỗi validate), format luôn
            if (hocPhiInput.value) {
                hocPhiDisplay.innerText = formatCurrencyDisplay(hocPhiInput.value);
            }

            hocPhiInput.addEventListener('input', function() {
                let value = hocPhiInput.value.replace(/\D/g, ''); // Bỏ mọi ký tự không phải số
                if (value) {
                    hocPhiDisplay.innerText = formatCurrencyDisplay(value);
                } else {
                    hocPhiDisplay.innerText = '';
                }
            });

            function formatCurrencyDisplay(val) {
                return parseInt(val).toLocaleString('vi-VN') + ' VNĐ';
            }
        });
        //hiển thị ngày ẩn
        $(function() {
            const $select = $('#khoahoc_id_modal');
            const $info = $('#ngayHocInfo');

            $select.on('change', function() {
                const selected = $('option:selected', this);
                const ngayBD = selected.data('ngaybatdau') || '';
                const ngayKT = selected.data('ngayketthuc') || '';

                if (ngayBD && ngayKT) {
                    $('#text_ngaybatdau').text(ngayBD);
                    $('#text_ngayketthuc').text(ngayKT);
                    $('#hidden_ngaybatdau').val(ngayBD);
                    $('#hidden_ngayketthuc').val(ngayKT);
                    $info.show();
                } else {
                    $('#text_ngaybatdau').text('');
                    $('#text_ngayketthuc').text('');
                    $('#hidden_ngaybatdau').val('');
                    $('#hidden_ngayketthuc').val('');
                    $info.hide();
                }
            });
        });

        $('#khoahoc_id').on('change', function() {
            const ngayBD = $('option:selected', this).data('ngaybatdau');
            const ngayKT = $('option:selected', this).data('ngayketthuc');

            $('#text_ngaybatdau').text(ngayBD);
            $('#text_ngayketthuc').text(ngayKT);

            $('#hidden_ngaybatdau').val(ngayBD);
            $('#hidden_ngayketthuc').val(ngayKT);
        });
        $(function() {
            $('#khoahoc_id_modal').on('change', function() {
                const ngaybd = $('option:selected', this).data('ngaybatdau');
                const ngaykt = $('option:selected', this).data('ngayketthuc');

                $('#ngaybatdau').val(ngaybd || '');
                $('#ngayketthuc').val(ngaykt || '');

                console.log('Chọn KH modal:', ngaybd, ngaykt);
                if (ngaybd) {
                    const year = new Date(ngaybd).getFullYear();

                    $('#namhoc option').each(function() {
                        if ($(this).text().trim() === year.toString()) {
                            $(this).prop('selected', true);
                            return false; // break
                        }
                    });
                }
            });
        });
        $(document).ready(function() {

            $('#btnOpenAddLopHoc').on('click', function() {
                const myModal = new bootstrap.Modal(document.getElementById('addLopHocModal'));
                myModal.show();
            });



            // Hàm đóng popup
            function dongPopup(popupId) {
                $("#" + popupId).removeClass("open");
            }

            // Mở popup "Chọn Khóa Học" khi click nút "Thêm mới"
            $(".btn-them").on("click", function() {
                // Reset select_khoahoc_id khi mở popup chọn khóa học
                $('#select_khoahoc_id').val('');
                moPopup("selectKhoaHocPopup");
            });

            // Đóng popup khi click nút đóng (x)
            $(".popup-close").on("click", function() {
                let targetPopupId = $(this).data("target");
                dongPopup(targetPopupId);
                // Nếu đóng popup thêm lớp học, reset lại trường khóa học
                if (targetPopupId === 'addLopHocPopup') {
                    $('#add_khoahoc_id').val('').prop('disabled', false);
                }
            });

            // Đóng popup khi click ra ngoài popup
            $(".popup-overlay").on("click", function(event) {
                if ($(event.target).hasClass("popup-overlay")) {
                    let targetPopupId = event.target.id;
                    dongPopup(targetPopupId);
                    // Nếu đóng popup thêm lớp học, reset lại trường khóa học
                    if (targetPopupId === 'addLopHocPopup') {
                        $('#add_khoahoc_id').val('').prop('disabled', false);
                    }
                }
            });
            $('#continueAddLopHoc').click(function() {
                let val = $('#select_khoahoc_id').val();
                let soLop = $('#so_lop_can_tao').val();
                const soluonghocvientoida = $('#soluonghocvientoida').val();
                if (!val || !soLop) {
                    alert('Vui lòng chọn khóa học và số lượng lớp!');
                    return;
                }

                let [khoahocId, trinhdoId] = val.split('_');

                $('#hidden_khoahoc_id').val(khoahocId);
                $('#hidden_trinhdo_id').val(trinhdoId);
                $('#hidden_so_lop').val(soLop);
                $('#hidden_soluonghocvientoida').val(soluonghocvientoida);

                $('#autoCreateLopHocForm').submit();
            });

            // Xử lý nút "Tiếp Tục" trong popup chọn khóa học
            // $('#continueAddLopHoc').on('click', function() {
            //     const selectedKhoaHocId = $('#select_khoahoc_id').val();
            //     const selectedKhoaHocTen = $('#select_khoahoc_id option:selected').data('ten');

            //     if (selectedKhoaHocId) {
            //         // Đóng popup chọn khóa học
            //         dongPopup("selectKhoaHocPopup");

            //         // Đặt giá trị cho trường khóa học trong form thêm lớp học
            //         $('#add_khoahoc_id').val(selectedKhoaHocId);
            //         // Tùy chọn: Vô hiệu hóa trường để người dùng không thay đổi
            //         $('#add_khoahoc_id').prop('disabled', true);

            //         // Mở popup thêm lớp học
            //         moPopup("addLopHocPopup");
            //     } else {
            //         alert('Vui lòng chọn một khóa học để tiếp tục.');
            //     }
            // });

            // // Xử lý nút "Hủy" trong popup chọn khóa học
            // $('#cancelSelectKhoaHoc').on('click', function() {
            //     dongPopup("selectKhoaHocPopup");
            // });
            // $('#continueAddLopHoc').click(function() {
            //     let selected = $('#select_khoahoc_id').val();
            //     if (!selected) {
            //         alert('Hãy chọn khóa học!');
            //         return;
            //     }
            //     let parts = selected.split('_');
            //     let khoahocId = parts[0];
            //     let trinhdoId = parts[1];

            //     // Gán vào hidden input
            //     $('#hidden_khoahoc_id').val(khoahocId);
            //     $('#hidden_trinhdo_id').val(trinhdoId);

            //     // Mở popup
            //     $('#addLopHocPopup').fadeIn();
            // });

            // Tìm kiếm theo ajax
            $("#search").on("keyup", function() {
                let tu_khoa = $(this).val();

                if (tu_khoa.length === 0) {
                    // Nếu ô tìm kiếm trống, redirect về trang danh sách lớp học
                    window.location.href = "{{ route('lophoc.index') }}";
                } else {
                    $.ajax({
                        url: "{{ route('lophoc.search') }}",
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