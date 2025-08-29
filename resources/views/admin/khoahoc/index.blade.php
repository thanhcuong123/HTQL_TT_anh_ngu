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
            <!-- <button type="button" class="btn btn-primary btn-them-khoahoc">+ Thêm mới</button> -->
            <!-- <button type="button" class="btn btn-primary ">+ Thêm mới</button> -->
            <!-- <a href="{{ route('khoahoc.create') }} " class="btn btn-primary ">+ Thêm mới</a> -->
            <button type="button" class="btn btn-primary btn-khoahoc">+ Thêm Khóa Học</button>




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
                        <!-- <th>Tên khóa học</th> -->
                        <!-- <th>Năm học</th> -->
                        <!-- <th>Trình độ</th> -->
                        <th>Ngày khai giảng</th>
                        <th>Ngày kết thúc</th>
                        <!-- <th>Thời lượng</th> -->
                        <!-- <th>Số buổi</th> -->
                        <!-- <th>Học phí</th> -->
                        <!-- <th>Số lớp</th> -->
                        <!-- <th>Mô tả</th> -->
                        <!-- <th>Hình ảnh</th> -->
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @foreach($dsKhoaHoc as $kh)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kh->ma }}</td>
                        <!-- <td>{{ $kh->ten }}</td> -->
                        <td>{{ $kh->ngaybatdau ? \Carbon\Carbon::parse($kh->ngaybatdau)->format('d/m/Y') : '' }}</td>
                        <td>{{ $kh->ngayketthuc ? \Carbon\Carbon::parse($kh->ngayketthuc)->format('d/m/Y') : '' }}</td>
                        <!-- <td>{{ $kh->namHoc->nam ?? 'N/A' }}</td> -->
                        <!-- <td>
                            @if ($kh->lopHocs->count() > 0)
                            {{ $kh->lopHocs->first()->trinhDo->ten ?? 'N/A' }}
                            @else
                            N/A
                            @endif
                        </td>
                       

                        <td>{{ $kh->thoiluong }}</td>
                        <td>{{ $kh->sobuoi }}</td>
                        <td>
                            @if ($kh->hocphi)
                            {{ number_format($kh->hocphi, 0, ',', '.') }} VNĐ
                            @else
                            N/A
                            @endif
                        </td>

                        <td>{{$kh->solop??'_'}}</td>
                        <td>{!! Str::limit(strip_tags($kh->mota), 50) !!}</td>

                        <td>
                            @if($kh->hinhanh)
                            <img src="{{ asset('storage/' . $kh->hinhanh) }}" alt="{{ $kh->ten }}" style="max-width: 100px;">
                            @else
                            Không có ảnh
                            @endif
                        </td> -->

                        <td>

                            <button type="button"
                                class="btn btn-sm btn-warning btn-edit-khoahoc"
                                data-id="{{ $kh->id }}"
                                data-ma="{{ $kh->ma }}"
                                data-ngaybatdau="{{ $kh->ngaybatdau }}"
                                data-ngayketthuc="{{ $kh->ngayketthuc }}">
                                Sửa
                            </button>

                            <form action="{{ route('khoahoc.destroy', $kh->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn?')">Xóa</button>




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
<!-- Popup Thêm Khóa Học -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Thêm Khóa Học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="addCourseForm" action="{{ route('khoahoc.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="ma" class="form-label">Mã Khóa Học</label>
                        <input type="text" class="form-control" value="{{ $newMa }}" disabled>
                        <input type="hidden" name="kh_stt" value="{{ $newMa }}">
                        @error('kh_stt') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>


                    <div class="mb-3">
                        <label for="ngaybatdau" class="form-label">Ngày Bắt Đầu</label>
                        <input type="date" class="form-control" id="ngaybatdau" name="ngaybatdau" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label for="ngayketthuc" class="form-label">Ngày Kết Thúc</label>
                        <input type="date" class="form-control" id="ngayketthuc" name="ngayketthuc" required min="{{ date('Y-m-d') }}">
                        <div id="date-error" style="color: red; font-size: 0.9em;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm Khóa Học</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Popup Chỉnh Sửa Khóa Học -->


</div>
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Chỉnh Sửa Khóa Học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="editCourseForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="edit_ma" class="form-label">Mã Khóa Học</label>
                        <input type="text" id="edit_ma" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="edit_ngaybatdau" class="form-label">Ngày Bắt Đầu</label>
                        <input type="date" class="form-control" id="edit_ngaybatdau" name="ngaybatdau" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_ngayketthuc" class="form-label">Ngày Kết Thúc</label>
                        <input type="date" class="form-control" id="edit_ngayketthuc" name="ngayketthuc" required>
                        <div id="edit-date-error" style="color: red; font-size: 0.9em;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {

        // ======================
        // 1️⃣ Tìm kiếm AJAX
        // ======================
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

        // ======================
        // 2️⃣ Mở popup Thêm Khóa Học
        // ======================
        $(".btn-khoahoc").on("click", function() {
            const myModal = new bootstrap.Modal(document.getElementById('addCourseModal'));
            myModal.show();
        });

        // ======================
        // 3️⃣ Mở popup Sửa Khóa Học
        // ======================
        $('.btn-edit-khoahoc').each(function() {
            $(this).on('click', function() {
                const id = $(this).data('id');
                const ma = $(this).data('ma');
                const ngaybatdau = $(this).data('ngaybatdau');
                const ngayketthuc = $(this).data('ngayketthuc');

                $('#edit_ma').val(ma);
                $('#edit_ngaybatdau').val(ngaybatdau ? new Date(ngaybatdau).toISOString().split('T')[0] : '');
                $('#edit_ngayketthuc').val(ngayketthuc ? new Date(ngayketthuc).toISOString().split('T')[0] : '');

                $('#editCourseForm').attr('action', `/admin/khoahoc/update/${id}`);

                const myModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
                myModal.show();
            });
        });

        // ======================
        // 4️⃣ Kiểm tra ngày Thêm Mới
        // ======================
        const startInput = $('#ngaybatdau');
        const endInput = $('#ngayketthuc');
        const errorDiv = $('#date-error');

        function checkAddDates() {
            const start = new Date(startInput.val());
            const end = new Date(endInput.val());
            if (startInput.val() && endInput.val()) {
                const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                if (diffDays < 30) {
                    errorDiv.text('Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu ít nhất 30 ngày.');
                } else {
                    errorDiv.text('');
                }
            } else {
                errorDiv.text('');
            }
        }

        function checkEditDates() {
            const start = new Date(editStart.val());
            const end = new Date(editEnd.val());
            if (editStart.val() && editEnd.val()) {
                const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                if (diffDays < 30) {
                    editError.text('Ngày kết thúc phải lớn hơn   ngày bắt đầu ít nhất 30 ngày.');
                } else {
                    editError.text('');
                }
            } else {
                editError.text('');
            }
        }


        startInput.on('change', checkAddDates);
        endInput.on('change', checkAddDates);

        $('#addCourseForm').on('submit', function(e) {
            checkAddDates();
            if (errorDiv.text()) {
                e.preventDefault();
            }
        });

        // ======================
        // 5️⃣ Kiểm tra ngày Chỉnh Sửa
        // ======================
        const editStart = $('#edit_ngaybatdau');
        const editEnd = $('#edit_ngayketthuc');
        const editError = $('#edit-date-error');

        function checkEditDates() {
            const start = new Date(editStart.val());
            const end = new Date(editEnd.val());
            if (editStart.val() && editEnd.val()) {
                if (start > end) {
                    editError.text('Ngày bắt đầu không được lớn hơn ngày kết thúc.');
                } else {
                    editError.text('');
                }
            } else {
                editError.text('');
            }
        }

        editStart.on('change', checkEditDates);
        editEnd.on('change', checkEditDates);

        $('#editCourseForm').on('submit', function(e) {
            checkEditDates();
            if (editError.text()) {
                e.preventDefault();
            }
        });

    });
</script>

<!-- <script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script> -->
@endsection