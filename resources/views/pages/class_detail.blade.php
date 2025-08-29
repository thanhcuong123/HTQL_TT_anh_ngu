@extends('users_layout') {{-- Đảm bảo bạn đang mở rộng layout chính của người dùng --}}

@section('main-content')

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            {{-- Phần nội dung chính của lớp học --}}
            <div class="col-lg-8">
                <div class="mb-5">
                    <div class="section-title position-relative mb-5">
                        <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Chi tiết lớp học</h6>
                        <h1 class="display-4">{{ $lopHoc->tenlophoc }}</h1>
                    </div>
                    @if ($lopHoc->hinhanh)
                    <img class="img-fluid rounded w-100 mb-4" src="{{ asset('storage/' . $lopHoc->hinhanh) }}" alt="{{ $lopHoc->tenlophoc }}">
                    @else
                    <img class="img-fluid rounded w-100 mb-4" src="{{ asset('img/default-class-image.jpg') }}" alt="Không có ảnh lớp học">
                    @endif
                    {{-- Mô tả lớp học (nếu có) --}}
                    <b> Mô tả lớp học </b>
                    @if ($lopHoc->mota)
                    <p>{!! nl2br(e($lopHoc->mota)) !!}</p>
                    @else
                    <p>Hiện chưa có mô tả chi tiết cho lớp học này.</p>
                    @endif
                </div>

                <hr>
            </div>


            {{-- Thanh bên (Sidebar) --}}
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="bg-primary mb-5 py-3">
                    <h3 class="text-white py-3 px-4 m-0">Thông tin lớp học</h3>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Mã lớp</h6>
                        <h6 class="text-white my-3">{{ $lopHoc->malophoc }}</h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Khóa học</h6>
                        <h6 class="text-white my-3">
                            Khóa {{ $lopHoc->khoaHoc->ma ?? 'Đang cập nhật' }}
                            -
                            {{ $lopHoc->trinhDo->ten ?? 'Chưa có trình độ' }}
                        </h6>

                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Trình độ</h6>
                        <h6 class="text-white my-3">{{ $lopHoc->trinhDo->ten ?? 'Đang cập nhật' }}</h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3"> Ngày khai giảng</h6>
                        <h6 class="text-white my-3">{{ $lopHoc->ngaybatdau ?? 'Đang cập nhật' }}</h6>
                    </div>
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3"> Ngày bế giảng</h6>
                        <h6 class="text-white my-3">{{ $lopHoc->ngayketthuc ?? 'Đang cập nhật' }}</h6>
                    </div>

                    <!-- <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Giáo viên</h6>
                        <h6 class="text-white my-3">
                            @if ($giaoViens->count() > 0)
                            {{ $giaoViens->pluck('ten')->implode(', ') }}
                            @else
                            Đang cập nhật
                            @endif
                        </h6>
                    </div> -->
                    <!-- <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Phòng học</h6>
                        <h6 class="text-white my-3">
                            @if ($phongHocs->count() > 0)
                            {{ $phongHocs->pluck('tenphong')->implode(', ') }}
                            @else
                            Đang cập nhật
                            @endif
                        </h6>
                    </div> -->
                    <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Sĩ số</h6>
                        <h6 class="text-white my-3">{{ $lopHoc->soluonghocvienhientai ?? 0 }}/{{ $lopHoc->soluonghocvientoida ?? 'Đang cập nhật' }}</h6>
                    </div>
                    <!-- <div class="d-flex justify-content-between border-bottom px-4">
                        <h6 class="text-white my-3">Thời gian học</h6>
                        <h6 class="text-white my-3">
                            {{ \Carbon\Carbon::parse($lopHoc->ngaybatdau)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($lopHoc->ngayketthuc)->format('d/m/Y') }} ({{ $lopHoc->gio_hoc ?? 'N/A' }})
                        </h6>
                    </div> -->
                    <!-- <div class="d-flex justify-content-between px-4">
                        <h6 class="text-white my-3">Lịch học</h6>
                        <h6 class="text-white my-3">{{ $lopHoc->lichoc ?? 'Đang cập nhật' }}</h6>
                    </div> -->
                    <div class="d-flex justify-content-between px-4">
                        <h6 class="text-white my-3">Trạng thái</h6>
                        <h6 class="text-white my-3">{{ $lopHoc->trangthai ?? 'Đang cập nhật'}}</h6>
                    </div>
                    <div class="d-flex justify-content-between px-4">
                        <h6 class="text-white my-3">Học phí</h6>
                        <h6 class="text-white my-3">
                            {{ $hocPhi ? number_format($hocPhi, 0, ',', '.') . ' VNĐ' : 'Đang cập nhật' }}
                        </h6>
                    </div>


                    <div class="py-3 px-4">
                        <a class="btn btn-block btn-secondary py-3 px-5" href="#form-tu-van">Đăng ký lớp học này</a>
                    </div>
                </div>

                {{-- Các lớp học khác của cùng khóa học (tương tự phần "Danh mục" khóa học) --}}
                @if ($lopHoc->khoaHoc && $lopHoc->khoaHoc->lophocs->count() > 1)
                <div class="mb-5">
                    <h3 class="mb-3">Các lớp học khác trong khóa: </h3>
                    {{ $lopHoc->khoaHoc->ten ?? 'này' }}
                    <ul class="list-group list-group-flush">
                        @php
                        // Lọc ra các lớp học khác, không bao gồm lớp học hiện tại
                        $otherLopHocsInCourse = $lopHoc->khoaHoc->lophocs->reject(function ($otherLopHoc) use ($lopHoc) {
                        return $otherLopHoc->id === $lopHoc->id;
                        });
                        @endphp

                        @if ($otherLopHocsInCourse->count() > 0)
                        @foreach ($otherLopHocsInCourse as $otherLopHoc)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <a href="{{ route('class.show', $otherLopHoc->id) }}" class="text-decoration-none h6 m-0 category-link"> {{-- Đảm bảo route name là 'lophoc.show' như đã định nghĩa --}}
                                {{ $otherLopHoc->tenlophoc }} ({{ $otherLopHoc->malophoc }})
                            </a>
                            {{-- Có thể thêm thông tin khác như sĩ số, trạng thái --}}
                            <span class="badge badge-info badge-pill">{{ $otherLopHoc->soluonghocvienhientai ?? 0 }}/{{ $otherLopHoc->soluonghocvientoida ?? 'Đang cập nhật' }}</span>
                        </li>
                        @endforeach
                        @else
                        <li class="list-group-item px-0 text-muted">
                            Không có lớp học tương tự trong khóa học này.
                        </li>
                        @endif
                    </ul>
                </div>
                @else
                {{-- Nếu không có khóa học hoặc chỉ có duy nhất lớp học hiện tại trong khóa --}}
                <div class="mb-5">
                    <h2 class="mb-3">Các lớp học khác trong khóa học này</h2>
                    <p class="text-muted">Hiện không có lớp học tương tự trong khóa học này.</p>
                </div>
                @endif

            </div>
        </div>
        {{-- Nút quay lại --}}
        <div class="">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
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
                                    <!-- @foreach($courses as $khoaHoc)
                                    <option value="{{ $khoaHoc->id }}"> Khóa {{ $khoaHoc->ma }}- {{ $khoaHoc->trinhdo_ten }}</option>
                                    @endforeach -->
                                    <!-- @foreach ($khoahocss as $khoaHoc)
                                    <option value="{{ $khoaHoc->khoahoc_id }}">
                                        Khóa {{ $khoaHoc->khoahoc_ten }} - {{ $khoaHoc->trinhdo_ten }}
                                    </option>
                                    @endforeach -->
                                    @foreach ($khoahocss as $khoaHoc)
                                    <option value="{{ $khoaHoc->khoahoc_id }}-{{ $khoaHoc->trinhdo_id }}">
                                        Khóa {{ $khoaHoc->khoahoc_ten }} - {{ $khoaHoc->trinhdo_ten }}
                                    </option>
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
    /* CSS cho hiệu ứng hover của thẻ lớp học (có thể tái sử dụng từ khóa học) */
    .class-card {
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

    .badge-info {
        background-color: #17a2b8 !important;
        color: white;
        padding: 0.4em 0.7em;
        font-size: 85%;
    }
</style>
@endsection

{{-- Bạn có thể thêm các script riêng cho trang này nếu cần --}}
@push('scripts')
{{-- Ví dụ: Nếu bạn muốn sử dụng collapse của Bootstrap (dành cho accordion buổi học) --}}
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> --}}
@endpush