@extends('staff.index')

@section('title-content')
<title>Quản lý Tư vấn</title>
@endsection

@section('staff-content')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    /* Các style tùy chỉnh nếu cần */
    /* Thêm style cho select filter nếu cần */
    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        /* Khoảng cách giữa các phần tử lọc */
    }

    .highlight-today {
        background-color: #bbe2e4ff !important;
        /* Màu xanh nhạt */
    }
</style>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <h3 class="card-title">Danh sách yêu cầu tư vấn</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">

            {{-- Form lọc và phân trang --}}
            <form action="{{ route('staff.tuvan') }}" method="GET" class="mb-3 d-flex align-items-end gap-3 flex-wrap">
                <div class="filter-group">
                    {{-- Chọn số trang cần hiển thị --}}
                    <div>
                        <label for="per_page" class="form-label mb-0">Số mục/trang:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="form-select form-select-sm w-auto d-inline-block">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        </select>
                    </div>

                    {{-- Lọc theo trạng thái tư vấn (MỚI THÊM) --}}
                    <div>
                        <label for="trangthai_filter" class="form-label mb-0">Trạng thái:</label>
                        <select name="trangthai_filter" id="trangthai_filter" onchange="this.form.submit()" class="form-select form-select-sm w-auto d-inline-block">
                            <option value="">-- Tất cả --</option>
                            <option value="đang chờ xử lý" {{ request('trangthai_filter') == 'đang chờ xử lý' ? 'selected' : '' }}>Đang chờ xử lý</option>
                            <option value="đã liên hệ" {{ request('trangthai_filter') == 'đã liên hệ' ? 'selected' : '' }}>Đã liên hệ</option>
                            <option value="liên hệ sau" {{ request('trangthai_filter') == 'liên hệ sau' ? 'selected' : '' }}>Hẹn liên hệ sau</option>
                            <option value="liên hệ không thành công" {{ request('trangthai_filter') == 'liên hệ không thành công' ? 'selected' : '' }}>Liên hệ không thành công</option>
                            <option value="đã hủy" {{ request('trangthai_filter') == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    {{-- Giữ lại các tham số tìm kiếm nếu có --}}
                    @if(request('tu_khoa'))
                    <input type="hidden" name="tu_khoa" value="{{ request('tu_khoa') }}">
                    @endif
                </div>
            </form>

            {{-- Form tìm kiếm --}}
            <form class="search-form" action="{{ route('staff.tuvan') }}" method="GET" style="position: relative;">
                <input type="search" id="search" name="tu_khoa" placeholder="Tìm kiếm theo tên, email, SĐT hoặc lời nhắn" autocomplete="off" class="form-control" value="{{ request('tu_khoa') }}" />
                <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100;"></div>
                {{-- Giữ lại các tham số phân trang và lọc trạng thái nếu có --}}
                @if(request('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                @if(request('trangthai_filter'))
                <input type="hidden" name="trangthai_filter" value="{{ request('trangthai_filter') }}">
                @endif
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Độ tuổi</th>
                        <th>Khóa học quan tâm</th>
                        <th>Lời nhắn</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Ngày đăng kí</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem">
                    @forelse($dsTuVan as $tv)
                    <tr class="{{ \Carbon\Carbon::parse($tv->created_at)->isToday() ? 'highlight-today': '' }}">

                        <td>{{ $loop->iteration + ($dsTuVan->currentPage() - 1) * $dsTuVan->perPage() }}</td>
                        <td>{{ $tv->hoten }}</td>
                        <td>{{ $tv->email }}</td>
                        <td>{{ $tv->sdt }}</td>
                        <td>{{ $tv->dotuoi }}</td>
                        <td>
                            {{ $tv->khoaHoc?->ma ?? 'Không rõ mã' }} -
                            {{ $tv->trinhdo?->ten ?? 'Không rõ trình độ' }}
                        </td>


                        <!-- <td>{{ $tv->khoaHoc?->ma }} - {{ $tv->khoaHoc?->lophocs?->first()?->trinhDo?->ten??'ko rõ'}}</td> -->



                        <td>{{ Str::limit($tv->loinhan, 50, '...') }}</td>
                        <!-- <td>
                            <span class="badge {{
                                $tv->trangthai == 'đang chờ xử lý' ? 'bg-warning text-dark' :
                                ($tv->trangthai == 'đã liên hệ' ? 'bg-success' :
                                ($tv->trangthai == 'liên hệ không thành công' ? 'bg-danger' :
                                'bg-secondary'))
                            }}">
                                {{ $tv->trangthai }}
                            </span>
                        </td> -->
                        <td><strong>{{ ucfirst($tv->trangthai) }}</strong></td>

                        <td>{{ Str::limit($tv->ghichu, 50, '...') }}</td>
                        <td>{{ $tv->created_at->format('d/m/Y H:i') }}</td>
                        <td class="col-action">

                            <a href="javascript:void(0);"
                                class="btn btn-sm btn-warning btn-sua-tuvan"
                                data-id="{{ $tv->id }}"
                                data-hoten="{{ $tv->hoten }}"
                                data-email="{{ $tv->email }}"
                                data-sdt="{{ $tv->sdt }}"
                                data-dotuoi="{{ $tv->dotuoi }}"

                                data-khoahoc="{{ ($tv->khoahoc->ma ?? '') . ' - ' . ($tv->trinhdo->ten ?? '') }}"

                                data-loinhan="{{ $tv->loinhan }}"
                                data-trangthai="{{ $tv->trangthai }}"
                                data-ghichu="{{ $tv->ghichu }}">
                                Xem
                            </a>

                            <form action="{{ route('staff.tuvan.destroy', $tv->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa yêu cầu tư vấn này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">Không có yêu cầu tư vấn nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $dsTuVan->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTuVanModal" tabindex="-1" aria-labelledby="editTuVanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTuVanModalLabel">Chi tiết yêu cầu tư vấn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTuVanForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <p><b>Họ và tên:</b> <span id="modal-hoten"></span></p>
                        <p><b>Email:</b> <span id="modal-email"></span></p>
                        <p><b>Số điện thoại:</b> <span id="modal-sdt"></span></p>
                        <p><b>Khóa học:</b> <span id="modal-khoahoc"></span></p>
                        <!-- <p><b>Trình độ:</b> <span id="modal-trinhdo"></span></p> -->
                        <p><b>Lời nhắn:</b> <span id="modal-loinhan"></span></p>

                        <div class="mb-3">
                            <label for="trangthai" class="form-label">Trạng thái</label>
                            <select class="form-control" id="trangthai" name="trangthai" required>
                                <option value="đang chờ xử lý">đang chờ xử lý</option>
                                <option value="đã liên hệ">đã liên hệ</option>
                                <option value="liên hệ sau">Hẹn liên hệ sau</option>
                                <option value="liên hệ không thành công">liên hệ không thành công</option>
                                <option value="đã hủy">đã hủy</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ghichu" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="ghichu" name="ghichu" placeholder="Thêm ghi chú về yêu cầu tư vấn"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-sua-tuvan').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const hoten = this.dataset.hoten;
            const email = this.dataset.email;
            const sdt = this.dataset.sdt;
            const dotuoi = this.dataset.dotuoi;
            const khoahoc = this.dataset.khoahoc;
            // const trinhdo = this.dataset.trinhdo;
            const loinhan = this.dataset.loinhan;
            const trangthai = this.dataset.trangthai;
            const ghichu = this.dataset.ghichu;

            const form = document.getElementById('editTuVanForm');
            form.action = `/staff/tuvan/update/${id}`; // Cập nhật route PUT tương ứng

            // Hiển thị thông tin yêu cầu tư vấn trong modal
            document.getElementById('modal-hoten').textContent = hoten;
            document.getElementById('modal-email').textContent = email;
            document.getElementById('modal-sdt').textContent = sdt;
            document.getElementById('modal-khoahoc').textContent = khoahoc;
            // document.getElementById('modal-trinhdo').textContent = trinhdo;

            document.getElementById('modal-loinhan').textContent = loinhan;

            // Đặt giá trị cho các trường input/select trong form
            form.querySelector('#trangthai').value = trangthai;
            form.querySelector('#ghichu').value = ghichu;

            const editModal = new bootstrap.Modal(document.getElementById('editTuVanModal'));
            editModal.show();
        });
    });
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.closest('form').submit();
        }, 500); // Tự động submit sau 500ms khi người dùng ngừng gõ
    });
</script>

<!-- <script src=" {{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script> -->
@endsection