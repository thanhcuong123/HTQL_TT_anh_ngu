@extends('users_layout') {{-- Hoặc layout chính của bạn --}}

@section('main-content')
<div class="container py-5">
    <h1>Kết quả tìm kiếm</h1>

    {{-- Hiển thị thông tin tìm kiếm --}}
    @if ($keyword_searched)
    <p class="lead">Tìm kiếm cho: <strong>"{{ $keyword_searched }}"</strong></p>
    @endif
    <!-- @if ($khoahoc_id_selected && !isset($selectedKhoaHoc))
    <p class="lead">Trong khóa học: <strong>{{ \App\Models\KhoaHoc::find($khoahoc_id_selected)->ten ?? 'Không xác định' }}</strong></p>
    @endif -->

    <hr>

    {{-- PHẦN 1: Hiển thị nếu chỉ chọn Khóa học từ dropdown (không có từ khóa) --}}
    @if (isset($selectedKhoaHoc) && !$keyword_searched)
    <div class="mb-5">
        @php
        $trinhDo = optional($selectedKhoaHoc->lopHocs->first())->trinhDo;
        @endphp

        <!-- <h2 class="mb-4">
            Thông tin khóa học:
            <span class="search-highlight-target">
                {{ $selectedKhoaHoc->ten }}
                @if($trinhDo)
                - {{ $trinhDo->ten }}
                @endif
            </span>
        </h2> -->

        <!-- <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm h-100 class-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                @if ($selectedKhoaHoc->hinhanh)
                                <img class="img-fluid rounded" src="{{ asset('storage/' . $selectedKhoaHoc->hinhanh) }}" alt="Hình ảnh khóa học {{ $selectedKhoaHoc->ten }}" style="max-height: 180px; object-fit: cover; width: 100%;">
                                @else
                                <img class="img-fluid rounded" src="{{ asset('users/Edukate/img/no-image.jpg') }}" alt="Không có ảnh" style="max-height: 180px; object-fit: cover; width: 100%;">
                                @endif
                            </div>
                            <div class="col-md-9">
                                @php
                                $trinhDo = optional($selectedKhoaHoc->lopHocs->first())->trinhDo;
                                @endphp
                                <h5 class="card-title text-primary search-highlight-target"> {{ $selectedKhoaHoc->ten }}
                                    @if($trinhDo)
                                    - {{ $trinhDo->ten }}
                                    @endif
                                </h5>
                                <p class="card-text mb-2">
                                    <strong>Mô tả:</strong> <span class="search-highlight-target">{{ strip_tags($selectedKhoaHoc->mota ?? 'Đang cập nhật') }}</span>
                                </p>
                                <a class="btn btn-primary btn-sm mt-2" href="{{ route('courses_detail', $selectedKhoaHoc->id) }}">Xem chi tiết khóa học</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <hr>

        <div class="mb-5">
            <h2 class="mb-4">Các lớp học hiện có của khóa học: <span class="search-highlight-target">{{ $selectedKhoaHoc->ten }}</span></h2>
            @if ($selectedKhoaHoc->lophocs->count() > 0)
            <div class="row">
                @foreach ($selectedKhoaHoc->lophocs as $lopHoc)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 class-card">
                        <div class="card-body">
                            <h5 class="card-title text-primary search-highlight-target">{{ $lopHoc->tenlophoc }}</h5>
                            @if ($lopHoc->hinhanh)
                            <img class="img-fluid rounded mb-3" src="{{ asset('storage/' . $lopHoc->hinhanh) }}" alt="Hình ảnh lớp học {{ $lopHoc->tenlophoc }}" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @else
                            <img class="img-fluid rounded mb-3" src="{{ asset('img/default-class-image.jpg') }}" alt="Không có ảnh lớp học" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @endif
                            <p class="card-text mb-1">
                                <strong>Mã lớp:</strong> <span class="search-highlight-target">{{ $lopHoc->malophoc }}</span>
                            </p>
                            <p class="card-text mb-1">
                                <strong>Trình độ:</strong> <span class="search-highlight-target">{{ $lopHoc->trinhDo->ten ?? 'N/A' }}</span>
                            </p>
                            <p class="card-text mb-1">
                                <strong>Sĩ số:</strong> {{ $lopHoc->soluonghocvienhientai ?? 0 }}/{{ $lopHoc->soluonghocvientoida ?? 'N/A' }}
                            </p>
                            <p class="card-text mb-1">
                                <strong>Trạng thái:</strong> <span class="search-highlight-target">{{ $lopHoc->trangthai ?? 'N/A' }}</span>
                            </p>
                            <a href="{{ route('class.show', $lopHoc->id) }}" class="btn btn-primary btn-sm mt-2">Xem chi tiết lớp</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p>Hiện tại không có lớp học nào cho khóa học này. Vui lòng quay lại sau!</p>
            @endif
        </div>
        @else {{-- PHẦN 2: Hiển thị kết quả tổng hợp (có từ khóa hoặc không chọn khóa học cụ thể) --}}
        {{-- Phần kết quả Khóa học --}}
        <!-- @if ($khoaHocResults->count() > 0)
        <div class="mb-5">
            <h2 class="mb-4">Các khóa học liên quan</h2>
            <div class="row">
                @foreach($khoaHocResults as $course)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 class-card">
                        <div class="card-body">
                            <h5 class="card-title text-primary search-highlight-target">{{ $course->ten }}</h5>
                            @if($course->hinhanh)
                            <img class="img-fluid rounded mb-3" src="{{ asset('storage/' . $course->hinhanh) }}" alt="Hình ảnh khóa học {{ $course->ten }}" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @else
                            <img class="img-fluid rounded mb-3" src="{{ asset('users/Edukate/img/no-image.jpg') }}" alt="Không có ảnh" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @endif
                            <p class="card-text mb-1 search-highlight-target">
                                {{ Str::limit(strip_tags($course->mota ?? 'Không có mô tả.'), 150) }}
                            </p>
                            <a class="btn btn-primary btn-sm mt-2" href="{{ route('courses_detail', $course->id) }}">Xem chi tiết khóa học</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <hr>
        @endif -->

        {{-- Phần kết quả Lớp học --}}
        @if ($lopHocResults->count() > 0)
        <div class="mb-5">
            <h2 class="mb-4">Các lớp học liên quan</h2>
            <div class="row">
                @foreach ($lopHocResults as $lopHoc)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 class-card">
                        <div class="card-body">
                            <h5 class="card-title text-primary search-highlight-target">{{ $lopHoc->tenlophoc }}</h5>
                            @if ($lopHoc->hinhanh)
                            <img class="img-fluid rounded mb-3" src="{{ asset('storage/' . $lopHoc->hinhanh) }}" alt="Hình ảnh lớp học {{ $lopHoc->tenlophoc }}" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @else
                            <img class="img-fluid rounded mb-3" src="{{ asset('img/default-class-image.jpg') }}" alt="Không có ảnh lớp học" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @endif
                            <p class="card-text mb-1">
                                <strong>Mã lớp:</strong> <span class="search-highlight-target">{{ $lopHoc->malophoc }}</span>
                            </p>
                            <p class="card-text mb-1">
                                <strong>Khóa học:</strong> <span class="search-highlight-target">{{ $lopHoc->khoaHoc->ma ?? 'N/A' }}</span>
                            </p>
                            <p class="card-text mb-1">
                                <strong>Trình độ:</strong> <span class="search-highlight-target">{{ $lopHoc->trinhDo->ten ?? 'N/A' }}</span>
                            </p>
                            <p class="card-text mb-1">
                                <strong>Sĩ số:</strong> {{ $lopHoc->soluonghocvienhientai ?? 0 }}/{{ $lopHoc->soluonghocvientoida ?? 'N/A' }}
                            </p>
                            <p class="card-text mb-1">
                                <strong>Trạng thái:</strong> <span class="search-highlight-target">{{ $lopHoc->trangthai ?? 'N/A' }}</span>
                            </p>
                            <a href="{{ route('class.show', $lopHoc->id) }}" class="btn btn-primary btn-sm mt-2">Xem chi tiết lớp</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <hr>
        @endif

        {{-- Phần kết quả Trình độ (hiện tại chưa có logic hiển thị chi tiết, chỉ có trong lớp học) --}}
        {{-- Nếu bạn muốn hiển thị riêng, hãy thêm cấu trúc box tương tự và thêm class 'search-highlight-target' --}}

        @if ($khoaHocResults->isEmpty() && $lopHocResults->isEmpty())
        <div class="alert alert-info">Không tìm thấy kết quả nào phù hợp với từ khóa của bạn.</div>
        @endif
        @endif


        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <div class="bg-light d-flex flex-column justify-content-center px-5" style="height: 450px;">
                        <div class="d-flex align-items-center mb-5">
                            <div class="btn-icon bg-primary mr-4">
                                <i class="fa fa-2x fa-map-marker-alt text-white"></i>
                            </div>
                            <div class="mt-n1">
                                <h4>Địa chỉ</h4>
                                <p class="m-0">3/2 - Ninh kiều - Cần thơ</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="btn-icon bg-secondary mr-4">
                                <i class="fa fa-2x fa-phone-alt text-white"></i>
                            </div>
                            <div class="mt-n1">
                                <h4>Liên hệ</h4>
                                <p class="m-0">0702892014</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="btn-icon bg-warning mr-4">
                                <i class="fa fa-2x fa-envelope text-white"></i>
                            </div>
                            <div class="mt-n1">
                                <h4>Email</h4>
                                <p class="m-0">thanhcuongstudent@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="section-title position-relative mb-4">
                        <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Đăng ký tư vấn khóa học</h6>
                        <h1 class="display-4">Gửi yêu cầu tư vấn</h1>
                    </div>
                    <div class="contact-form">
                        {{-- Form tư vấn khóa học mới --}}
                        <form action="{{ route('tuvan.store') }}" method="POST">
                            @csrf {{-- Thêm CSRF token để bảo mật form --}}

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <!-- <label for="ho_ten" class="form-label"> <b>Họ và tên *</b></label> -->
                                    <input type="text" class="form-control border-top-0 border-right-0 border-left-0 p-0" id="hoten" name="hoten" placeholder="Họ và tên *" required>
                                    @error('hoten')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <!-- <label for="email" class="form-label"><b>Email *</b></label> -->
                                    <input type="email" class="form-control border-top-0 border-right-0 border-left-0 p-0" id="email" name="email" placeholder="Email *" required>
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <!-- <label for="sdt" class="form-label"> <b>Số điện thoại *</b></label> -->
                                    <input type="tel" class="form-control border-top-0 border-right-0 border-left-0 p-0" id="sdt" name="sdt" placeholder="Số điện thoại *" required>
                                    @error('sdt')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <!-- <label for="do_tuoi" class="form-label"><b>Độ tuổi</b></label> -->
                                    <input type="number" class="form-control border-top-0 border-right-0 border-left-0 p-0" id="dotuoi" name="dotuoi" placeholder="Độ tuổi *" min="1" max="100">
                                    @error('dotuoi')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <label for="khoa_hoc_id" class="form-label"> <b>Khóa học quan tâm *</b></label> -->
                                <select class="form-control border-top-0 border-right-0 border-left-0 p-0" id="khoahoc_id" name="khoahoc_id" required>
                                    <option value="">Chọn khóa học bạn quan tâm *</option>
                                    {{-- Vòng lặp để hiển thị các khóa học từ database --}}
                                    @foreach($courses as $khoaHoc)
                                    <option value="{{ $khoaHoc->id }}">{{ $khoaHoc->ma }}</option>
                                    @endforeach
                                </select>
                                @error('khoahoc_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <!-- <label for="loi_nhac" class="form-label"><strong>Lời nhắn / Câu hỏi của bạn</strong></label> -->
                                <textarea class="form-control border-top-0 border-right-0 border-left-0 p-0" rows="5" id="loinhan" name="loinhan" placeholder="Bạn có câu hỏi nào khác hoặc muốn chia sẻ gì không?"></textarea>
                                @error('loinhan')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <button class="btn btn-primary py-3 px-5" type="submit">Gửi yêu cầu tư vấn</button>
                            </div>
                        </form>
                        {{-- End Form tư vấn khóa học mới --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ... (CSS hiện có của bạn) ... */
        .class-card {
            /* Bây giờ áp dụng cho cả khóa học và lớp học */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, border-color 0.3s ease-in-out;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            background-color: #ffffff;
            border-radius: 0.25rem;
            overflow: hidden;
        }

        .class-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border-color: #007bff;
        }

        /* Các kiểu dáng Bootstrap cho card đã được giữ nguyên */
        .card-title {
            font-weight: 600;
        }

        .card-body strong {
            color: #343a40;
        }

        /* Badge info (nếu còn dùng) */
        .badge-info {
            background-color: #17a2b8 !important;
            color: white;
            padding: 0.4em 0.7em;
            font-size: 85%;
        }

        /* Kiểu dáng cho các từ khóa được tô màu */
        mark {

            /* Màu vàng Gold */
            color: #333333;
            /* Màu chữ xám đậm */
            padding: 0 3px;
            /* Đệm nhỏ để chữ không bị dính vào nền */
            border-radius: 3px;
            /* Bo góc nhẹ */
            font-weight: bold;
            /* Chữ đậm hơn để nổi bật */
        }

        /* mark a {
            color: inherit;
            text-decoration: none;
        } */

        /* Đảm bảo các link trong mô tả không bị ảnh hưởng bởi màu highlight nếu có */
    </style>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const keywordSearched = "{{ addslashes(request('keyword')) }}"; // Lấy từ khóa đã tìm kiếm từ URL

            // Đảm bảo chỉ thực hiện khi có từ khóa
            if (keywordSearched) {
                // Tách từ khóa thành các từ riêng lẻ để tô màu
                // Chuyển sang chữ thường để so khớp không phân biệt hoa/thường
                const searchTerms = keywordSearched.toLowerCase().split(/\s+/).filter(Boolean); // filter(Boolean) loại bỏ các chuỗi rỗng

                // Lấy tất cả các phần tử có class 'search-highlight-target'
                const highlightElements = document.querySelectorAll('.search-highlight-target');

                highlightElements.forEach(element => {
                    let originalHtml = element.innerHTML;
                    let highlightedHtml = originalHtml;

                    // Duyệt qua từng từ khóa tìm kiếm
                    searchTerms.forEach(term => {
                        // Tạo một biểu thức chính quy để tìm tất cả các lần xuất hiện của từ khóa
                        // 'gi' nghĩa là global (tìm tất cả) và case-insensitive (không phân biệt hoa/thường)
                        // term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') để escape các ký tự đặc biệt trong từ khóa
                        const regex = new RegExp(term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');

                        // Thay thế các từ khớp bằng thẻ <mark>
                        highlightedHtml = highlightedHtml.replace(regex, (match) => {
                            return `<mark>${match}</mark>`;
                        });
                    });

                    // Gán lại nội dung đã được tô màu vào phần tử
                    element.innerHTML = highlightedHtml;
                });
            }
        });
    </script>
</div>
@endsection