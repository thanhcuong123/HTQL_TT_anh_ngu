@extends('staff.index') {{-- Đảm bảo đây là layout chính của admin của bạn --}}

@section('title-content')
<title>Quản Lý Tin Tức</title>
@endsection

@section('staff-content')

{{-- Import các thư viện cần thiết --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
{{-- Link đến CSS tùy chỉnh của bạn --}}
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Font Awesome cho các icon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

        <h3 class="card-title">Danh sách Tin tức</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-add-news">+ Thêm mới</button>
            <form class="search-form" action="{{ route('tintuc') }}" method="GET" style="position: relative;">
                <input type="search" id="search-news" name="tu_khoa" placeholder="Tìm kiếm tin tức" autocomplete="off" class="form-control" value="{{ request('tu_khoa') }}" />
                <div id="search-results-news" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; display: none;"></div>
            </form>
        </div>

        <form action="{{ route('tintuc') }}" method="GET" class="mb-3">
            <label for="per_page_news">Số tin tức mỗi trang:</label>
            <select name="per_page" id="per_page_news" onchange="this.form.submit()" class="form-select form-select-sm w-auto d-inline-block ms-2">
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
                        <th>Tiêu đề</th>
                        <th>Ảnh</th>
                        <th>Tác giả</th>
                        <th>Ngày đăng</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th class="col-action">Hành động</th>
                    </tr>
                </thead>
                <tbody id="kq-timkiem-news">


                    @if ($newsArticles->count() > 0)
                    @foreach($newsArticles as $news)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Str::limit($news->tieude, 60) }}</td>
                        <td>
                            @if($news->hinhanh)
                            <img src="{{ $news->hinhanh }}" alt="{{ $news->tieude }}" class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                            <span class="text-muted">Không ảnh</span>
                            @endif
                        </td>
                        <!-- <td>{{ $news->tacgia_name ?? 'N/A' }}</td> -->
                        <td> {{ $news->nhanVien->ten??'NA' }}</td>

                        <td>{{ \Carbon\Carbon::parse($news->ngaydang)->format('d/m/Y') }}</td>

                        <td>{{ Str::limit(strip_tags($news->noidung), 60) }}</td>


                        @php
                        $statuses = [
                        'da_dang' => ['label' => 'Đã đăng', 'class' => 'bg-success'],
                        'ban_nhap' => ['label' => 'Bản nháp', 'class' => 'bg-warning text-dark'],
                        'cho_duyet' => ['label' => 'Chờ duyệt', 'class' => 'bg-secondary'],
                        ];
                        $status = $statuses[$news->trang_thai] ?? ['label' => 'Không rõ', 'class' => 'bg-dark'];
                        @endphp

                        <td>
                            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </td>

                        <td class="col-action">
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-edit-news"
                                data-id="{{ $news->id }}"
                                data-tieude="{{ $news->tieude }}"
                                data-slug="{{ $news->slug }}"
                                data-noidung="{{ $news->noidung }}"
                                data-ngaydang="{{ $news->ngaydang }}"
                                data-hinhanh="{{ $news->hinhanh }}"
                                data-tacgia_id="{{ $news->tacgia_id }}"
                                data-trang_thai="{{ $news->trang_thai }}">Sửa</a>
                            <form action="{{ route('tintuc.destroy', $news->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tức này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có tin tức nào để hiển thị.</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            {{-- Phần phân trang, bạn cần truyền đối tượng Paginator từ controller --}}
            <div class="d-flex justify-content-end mt-3">
                {{-- {{ $newsArticles->appends(request()->all())->links() }} --}}
            </div>
        </div>

        {{-- Popup thêm tin tức --}}
        <div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewsModalLabel">Thêm Tin Tức Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addNewsForm" action="{{ route('staff.tintuc.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="add_tieude" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="add_tieude" name="tieude" required placeholder="Nhập tiêu đề tin tức">
                            </div>
                            <!-- <div class="mb-3">
                                <label for="add_slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="add_slug" name="slug" placeholder="Tự động tạo hoặc nhập slug">
                            </div> -->
                            <div class="mb-3">
                                <label for="add_noidung" class="form-label">Nội dung</label>
                                <div id="add_quill_editor" style="height: 200px;"></div>
                                <textarea name="noidung" id="add_noidung_hidden" style="display:none;"></textarea> {{-- Hidden textarea để lưu nội dung Quill --}}
                            </div>
                            <!-- <div class="mb-3">
                                <label for="add_ngaydang" class="form-label">Ngày đăng</label>
                                <input type="text" class="form-control" id="add_ngaydang" name="ngaydang" required placeholder="Chọn ngày đăng">
                            </div> -->
                            <div class="mb-3">
                                <label for="add_hinhanh" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" id="add_hinhanh" name="hinhanh" accept="image/*">
                            </div>


                            <button type="submit" class="btn btn-primary">Thêm Tin Tức</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Popup chỉnh sửa tin tức --}}
        <div class="modal fade" id="editNewsModal" tabindex="-1" aria-labelledby="editNewsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editNewsModalLabel">Chỉnh sửa Tin Tức</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editNewsForm" method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" id="edit_news_id" name="id">
                            <div class="mb-3">
                                <label for="edit_tieude" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="edit_tieude" name="tieude" required placeholder="Nhập tiêu đề tin tức">
                            </div>
                            <!-- <div class="mb-3">
                                <label for="edit_slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="edit_slug" name="slug" placeholder="Tự động tạo hoặc nhập slug">
                            </div> -->
                            <div class="mb-3">
                                <label for="edit_noidung" class="form-label">Nội dung</label>
                                <div id="edit_quill_editor" style="height: 200px;"></div>
                                <textarea name="noidung" id="edit_noidung_hidden" style="display:none;"></textarea> {{-- Hidden textarea để lưu nội dung Quill --}}
                            </div>
                            <!-- <div class="mb-3">
                                <label for="edit_ngaydang" class="form-label">Ngày đăng</label>
                                <input type="text" class="form-control" id="edit_ngaydang" name="ngaydang" required placeholder="Chọn ngày đăng">
                            </div> -->
                            <div class="mb-3">
                                <label for="edit_hinhanh" class="form-label">Hình ảnh hiện tại</label>
                                <div id="current_image_preview" class="mb-2">
                                    {{-- Image will be loaded here by JS --}}
                                </div>
                                <input type="file" class="form-control" id="edit_hinhanh" name="hinhanh" accept="image/*">
                                <small class="form-text text-muted">Để trống nếu không muốn thay đổi ảnh.</small>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="edit_tacgia_id" class="form-label">Tác giả</label>
                                <select class="form-select" id="edit_tacgia_id" name="tacgia_id" required>
                                    <option value="">Chọn tác giả</option>

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_trang_thai" class="form-label">Trạng thái</label>
                                <select class="form-select" id="edit_trang_thai" name="trang_thai" required>
                                    <option value="draft">Bản nháp</option>
                                    <option value="publish">Đã đăng</option>
                                </select>
                            </div> -->

                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Khởi tạo Quill editor cho form thêm mới
    var addQuill = new Quill('#add_quill_editor', {
        theme: 'snow',
        placeholder: 'Nhập nội dung tin tức...',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                ['link', 'image'],
                [{
                    'align': []
                }],
                ['clean']
            ]
        }
    });
    // Cập nhật nội dung từ Quill vào hidden textarea trước khi submit
    addQuill.on('text-change', function() {
        document.getElementById('add_noidung_hidden').value = addQuill.root.innerHTML;
    });

    // Khởi tạo Quill editor cho form chỉnh sửa
    var editQuill = new Quill('#edit_quill_editor', {
        theme: 'snow',
        placeholder: 'Nhập nội dung tin tức...',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                ['link', 'image'],
                [{
                    'align': []
                }],
                ['clean']
            ]
        }
    });
    // Cập nhật nội dung từ Quill vào hidden textarea trước khi submit
    editQuill.on('text-change', function() {
        document.getElementById('edit_noidung_hidden').value = editQuill.root.innerHTML;
    });

    // Khởi tạo Flatpickr cho ngày đăng
    flatpickr("#add_ngaydang", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
    });
    flatpickr("#edit_ngaydang", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
    });

    // Xử lý nút "Thêm mới"
    document.querySelector('.btn-add-news').addEventListener('click', function() {
        // Reset form và Quill editor trước khi mở modal
        document.getElementById('addNewsForm').reset();
        addQuill.setContents([]); // Xóa nội dung Quill
        document.getElementById('add_noidung_hidden').value = ''; // Xóa nội dung hidden textarea
        var addModal = new bootstrap.Modal(document.getElementById('addNewsModal'));
        addModal.show();
    });

    // Xử lý nút "Sửa"
    document.querySelectorAll('.btn-edit-news').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const tieude = this.dataset.tieude;
            const slug = this.dataset.slug;
            const noidung = this.dataset.noidung;
            const ngaydang = this.dataset.ngaydang;
            const hinhanh = this.dataset.hinhanh;
            const tacgia_id = this.dataset.tacgia_id;
            const trang_thai = this.dataset.trang_thai;

            const form = document.getElementById('editNewsForm');
            form.action = `/staff/tintuc/${id}`; // Cập nhật action cho form PUT

            // Điền dữ liệu vào form
            form.querySelector('#edit_news_id').value = id;
            form.querySelector('#edit_tieude').value = tieude;
            // form.querySelector('#edit_slug').value = slug;

            // Đặt nội dung cho Quill editor
            editQuill.root.innerHTML = noidung;
            document.getElementById('edit_noidung_hidden').value = noidung;

            // Đặt giá trị cho Flatpickr
            // flatpickr("#edit_ngaydang").setDate(ngaydang);

            // Hiển thị ảnh hiện tại
            const currentImagePreview = document.getElementById('current_image_preview');
            if (hinhanh && hinhanh !== 'null') { // Kiểm tra hinhanh có giá trị và không phải chuỗi 'null'
                currentImagePreview.innerHTML = `<img src="${hinhanh}" alt="Current Image" class="img-thumbnail" style="max-width: 150px; height: auto;">`;
            } else {
                currentImagePreview.innerHTML = `<span class="text-muted">Không có ảnh hiện tại.</span>`;
            }

            // form.querySelector('#edit_tacgia_id').value = tacgia_id;
            // form.querySelector('#edit_trang_thai').value = trang_thai;

            var editModal = new bootstrap.Modal(document.getElementById('editNewsModal'));
            editModal.show();
        });
    });

    // Tự động tạo slug khi nhập tiêu đề (cho form thêm mới)
    document.getElementById('add_tieude').addEventListener('keyup', function() {
        const title = this.value;
        const slugInput = document.getElementById('add_slug');
        slugInput.value = slugify(title);
    });

    // Tự động tạo slug khi nhập tiêu đề (cho form chỉnh sửa)
    document.getElementById('edit_tieude').addEventListener('keyup', function() {
        const title = this.value;
        const slugInput = document.getElementById('edit_slug');
        slugInput.value = slugify(title);
    });

    // Hàm slugify đơn giản
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\-]+/g, '') // Remove all non-word chars
            .replace(/\-\-+/g, '-') // Replace multiple - with single -
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, ''); // Trim - from end of text
    }

    // Xử lý tìm kiếm AJAX (nếu bạn muốn tìm kiếm mà không load lại trang)


    // Ẩn kết quả tìm kiếm khi click ra ngoài
    document.addEventListener('click', function(event) {
        const searchInput = document.getElementById('search-news');
        const searchResultsDiv = document.getElementById('search-results-news');
        if (!searchInput.contains(event.target) && !searchResultsDiv.contains(event.target)) {
            searchResultsDiv.style.display = 'none';
        }
    });
</script>
{{-- Link đến JS tùy chỉnh của bạn (nếu có) --}}
<script src="{{ asset('admin/luanvantemplate/dist/js/my.js') }}"></script>
@endsection