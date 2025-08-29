@extends('users_layout')

@section('main-content')


<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            {{-- Phần nội dung chính của khóa học --}}
            <div class="col-lg-8">
                <div class="mb-5">
                    <div class="section-title position-relative mb-5">
                        <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Chi tiết khóa học</h6>
                        <h1 class="display-4">
                            {{ $khoaHoc->ten }}
                            -
                            {{ $khoaHoc->lophocs->pluck('trinhDo.ten')->filter()->unique()->implode(', ') }}
                        </h1>

                    </div>
                    @if ($khoaHoc->hinhanh)
                    <img class="img-fluid rounded w-100 mb-4" src="{{ asset('storage/' . $khoaHoc->hinhanh) }}" alt="{{ $khoaHoc->tenkhoahoc }}">
                    @else
                    <img class="img-fluid rounded w-100 mb-4" src="{{ asset('img/default-course-image.jpg') }}" alt="Không có ảnh">
                    @endif
                    <p>{!! $khoaHoc->mota !!}</p>
                    @if ($khoaHoc->mota_chi_tiet)
                    <p>{!! nl2br(e($khoaHoc->mota_chi_tiet)) !!}</p>
                    @endif
                </div>

                ---


            </div>

            {{-- Thanh bên (Sidebar) --}}
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="bg-primary mb-5 py-3">
                    <h3 class="text-white py-3 px-4 m-0">Đặc điểm khóa học</h3>
                    @php
                    $firstLopHoc = $khoaHoc->lophocs->first();
                    $giangVienTen = $firstLopHoc && $firstLopHoc->giaoVien && $firstLopHoc->giaoVien->ten ? $firstLopHoc->giaoVien->ten : 'Đang cập nhật';
                    @endphp
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Giảng viên</h6>
                        <h6 class="text-white my-3">{{ $giangVienTen }}</h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Thời lượng</h6>
                        <h6 class="text-white my-3">{{ $khoaHoc->thoiluong ?? 'N/A' }}</h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Số buổi</h6>
                        <h6 class="text-white my-3">{{ $khoaHoc->sobuoi ?? 'N/A' }} buổi/tuần</h6>
                    </div>
                    @if ($trinhDos->count() > 0)
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Trình độ</h6>
                        <h6 class="text-white my-3">
                            @foreach ($trinhDos as $trinhDo)
                            {{ $trinhDo->ten }}@if (!$loop->last), @endif
                            @endforeach
                        </h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Kỹ năng</h6>
                        <h6 class="text-white my-3">
                            @php
                            $kyNangNames = $trinhDos->flatMap(function ($trinhDo) {
                            return $trinhDo->kyNangs->pluck('ten');
                            })->filter()->unique()->implode(', ');
                            @endphp
                            {{ $kyNangNames ?: 'Đang cập nhật' }}
                        </h6>
                    </div>
                    @else
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Trình độ</h6>
                        <h6 class="text-white my-3">Đang cập nhật</h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Kỹ năng</h6>
                        <h6 class="text-white my-3">Đang cập nhật</h6>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between px-4">
                        <h6 class="text-white my-3">Ngôn ngữ</h6>
                        <h6 class="text-white my-3">Tiếng Anh</h6>
                    </div>

                    <!-- hocphi -->
                    @php
                    $hocPhi = $hocPhiTheoTrinhDo[$trinhDo->id] ?? null;
                    @endphp

                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Học phí</h6>
                        <h6 class="text-white my-3">
                            @if(is_numeric($hocPhi))
                            {{ number_format($hocPhi, 0, ',', '.') }} VNĐ
                            @else
                            Chưa cập nhật
                            @endif
                        </h6>
                    </div>
                    <div class="py-3 px-4">
                        <a class="btn btn-block btn-secondary py-3 px-5" href="#form-tu-van">Đăng ký nhận tư vấn ngay</a>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="mb-3">Danh mục </h2>
                    <ul class="list-group list-group-flush">
                        @foreach ($khoaHocs as $khoaHocs)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            {{-- Liên kết đến trang danh sách lớp học đã lọc theo khóa học này --}}
                            <a href="{{ route('courses_detail', $khoaHocs->id)  }}" class="text-decoration-none h6 m-0 category-link">
                                {{ $khoaHocs->ma ?? '' }}
                                @php
                                // Lấy trình độ đầu tiên từ các lớp
                                $firstTrinhDo = $khoaHocs->lopHocs->first() ? $khoaHocs->lopHocs->first()->trinhdo : null;
                                @endphp
                                @if($firstTrinhDo)
                                - {{ $firstTrinhDo->ten }}
                                @endif
                            </a>
                            {{-- Hiển thị số lượng lớp học (nếu bạn dùng withCount trong Controller) --}}
                            <span class="badge badge-primary badge-pill">{{ $khoaHocs->lophocs_count ?? 0 }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>




            </div>
        </div>
        {{-- DANH SÁCH LỚP HỌC TRONG KHÓA HỌC --}}
        <div class="mb-5">
            <h2 class="mb-4">Các lớp học hiện có của khóa học: {{ $khoaHoc->ten }}</h2>
            @if ($khoaHoc->lophocs->count() > 0)
            <div class="row">
                @foreach ($khoaHoc->lophocs as $lopHoc)
                <div class="col-md-4 mb-4">
                    {{-- Đảm bảo class "class-card" được thêm vào div "card" --}}
                    <div class="card border-0 shadow-sm h-100 class-card">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $lopHoc->tenlophoc }} </h5>
                            <!-- <p class="card-text mb-1">
                                        <strong>Giáo viên:</strong> {{ $lopHoc->giaoVien->ten ?? 'Đang cập nhật' }}
                                    </p> -->
                            @if ($lopHoc->hinhanh) {{-- Giả định tên cột hình ảnh là 'hinh_anh_lop_hoc' --}}
                            <img class="img-fluid rounded mb-3" src="{{ asset('storage/' . $lopHoc->hinhanh) }}" alt="{{ $lopHoc->tenlophoc }}" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @else
                            {{-- Hình ảnh mặc định nếu không có ảnh lớp học --}}
                            <img class="img-fluid rounded mb-3" src="{{ asset('img/default-class-image.jpg') }}" alt="Không có ảnh lớp học" style="max-height: 150px; object-fit: cover; width: 100%;">
                            @endif
                            <!-- <p class="card-text mb-1">
                                        <strong>Phòng học:</strong> {{ $lopHoc->phongHoc->ten_phong_hoc ?? 'Đang cập nhật' }}
                                    </p> -->
                            <p class="card-text mb-1">
                                <strong>Lịch học:</strong> thứ {{ $lopHoc->lichoc ?? 'Đang cập nhật' }}
                            </p>
                            <!-- <p class="card-text mb-1">
                                        <strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($lopHoc->ngay_bat_dau)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($lopHoc->ngay_ket_thuc)->format('d/m/Y') }} ({{ $lopHoc->gio_hoc ?? 'N/A' }})
                                    </p> -->
                            <p class="card-text mb-1">
                                <strong>Sĩ số:</strong> {{ $lopHoc->soluonghocvienhientai ?? 0 }}/{{ $lopHoc->soluonghocvientoida?? 'N/A' }}
                            </p>
                            <p class="card-text mb-1">
                                <strong>Trạng thái:</strong> <span class="">{{ $lopHoc->trangthai ?? 'N/A' }}</span>
                            </p>
                            <a href="{{ route('class.show',[$lopHoc->id]) }}" class="btn btn-primary btn-sm mt-2">Xem chi tiết lớp</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p>Hiện tại không có lớp học nào cho khóa học này. Vui lòng quay lại sau!</p>
            @endif
        </div>
    </div>
</div>

<div id="form-tu-van">
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
</div>


{{-- Vị trí tốt nhất để đặt CSS tùy chỉnh của bạn --}}
<style>
    /* CSS cho hiệu ứng hover của thẻ lớp học */
    .class-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, border-color 0.3s ease-in-out;
        border: 1px solid #e0e0e0;
        /* Viền mặc định */
        cursor: pointer;
        /* Thể hiện rằng đây là một phần tử có thể tương tác */
        background-color: #ffffff;
        /* Đảm bảo nền trắng để bóng đổ nổi bật */
        border-radius: 0.25rem;
        /* Bo góc nhẹ nhàng như card Bootstrap */
        overflow: hidden;
        /* Quan trọng để bóng đổ không bị cắt */
    }

    .class-card:hover {
        transform: translateY(-8px);
        /* Nâng card lên 8px khi hover */
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        /* Bóng đổ mạnh hơn, rõ nét hơn */
        border-color: #007bff;
        /* Viền màu xanh nổi bật */
    }

    /* Các kiểu dáng Bootstrap cho card đã được giữ nguyên */
    .card-title {
        font-weight: 600;
        /* Làm tiêu đề nổi bật hơn */
    }

    .card-body strong {
        color: #343a40;
        /* Đảm bảo text strong dễ đọc */
    }

    .badge-info {
        background-color: #17a2b8 !important;
        /* Sử dụng !important nếu màu mặc định của Bootstrap đang override */
        color: white;
        padding: 0.4em 0.7em;
        font-size: 85%;
    }
</style>
@endsection