@extends('users_layout')

@section('main-content')

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row mx-0 justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center position-relative mb-5">
                    <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Các khóa học của trung tâm anh ngữ River</h6>
                    <h1 class="display-4">Các khóa học mới của chúng tôi</h1>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- Loop through the courses passed from the controller --}}
            @forelse ($khoahocss as $khoaHoc)
            <div class="col-lg-4 col-md-6 pb-4">
                {{-- Link to the course detail page (assuming 'courses_detail' is your route name) --}}
                <a class="courses-list-item position-relative d-block overflow-hidden mb-2" href="{{ route('courses_detail', $khoaHoc->id) }}">
                    @if ($khoaHoc->hinhanh)
                    <img class="img-fluid" src="{{ asset('storage/' . $khoaHoc->hinhanh) }}" alt="{{ $khoaHoc->ten }}">
                    @else
                    {{-- Default image if no image is available for the course --}}
                    <img class="img-fluid" src="{{ asset('img/default-course-image.jpg') }}" alt="Không có ảnh">
                    @endif
                    <div class="courses-text">
                        {{-- Course Name --}}
                        <h4 class="text-center text-white px-3">{{ $khoaHoc->ten }}</h4>
                        <div class="border-top w-100 mt-3">
                            <div class="d-flex justify-content-between p-4">
                                {{-- Display instructor name from the first associated class (if any) --}}
                                @php
                                $giangVien = $khoaHoc->lophocs->first()->giaoVien->ten ?? 'Đang cập nhật';
                                // You might also want to calculate average rating from associated classes or a dedicated rating table
                                $averageRating = number_format($khoaHoc->trung_binh_xep_hang ?? 0, 1); // Assuming a column or calculated attribute
                                $reviewCount = $khoaHoc->luot_danh_gia ?? 0; // Assuming a column for review count
                                @endphp
                                <span class="text-white"><i class="fa fa-user mr-2"></i>{{ $giangVien }}</span>
                                <span class="text-white"><i class="fa fa-star mr-2"></i>{{ $averageRating }}
                                    <small>({{ $reviewCount }})</small></span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            {{-- Message if no courses are available --}}
            <div class="col-12 text-center py-5">
                <p class="lead">Hiện tại chưa có khóa học nào được hiển thị. Vui lòng quay lại sau!</p>
            </div>
            @endforelse

            {{-- Pagination Links --}}
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-lg justify-content-center mb-0">
                        {{ $khoahocss->links('pagination::bootstrap-4') }} {{-- Use Laravel's built-in pagination links --}}
                    </ul>
                </nav>
            </div>
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
                                    <option value="{{ $khoaHoc->id }}">{{ $khoaHoc->ten }}</option>
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


{{-- Custom CSS if needed (keep it in a separate file for larger projects) --}}
<!-- <style>
    .courses-list-item {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
        /* Slightly rounded corners for consistency */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Subtle shadow */
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .courses-list-item img {
        width: 100%;
        height: 200px;
        /* Fixed height for course images */
        object-fit: cover;
        /* Ensures image covers the area without distortion */
    }

    .courses-list-item .courses-text {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        /* Semi-transparent overlay */
        padding-top: 15px;
        transition: opacity 0.3s ease-in-out;
        opacity: 0;
        visibility: hidden;
    }

    .courses-list-item:hover .courses-text {
        opacity: 1;
        visibility: visible;
    }

    .courses-list-item:hover {
        transform: translateY(-5px);
        /* Slight lift on hover */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        /* Enhanced shadow on hover */
    }

    .courses-list-item h4 {
        margin-bottom: 0.5rem;
    }

    .courses-list-item .border-top {
        border-color: rgba(255, 255, 255, 0.2) !important;
    }

    /* Style for pagination links */
    .pagination .page-item .page-link {
        color: #007bff;
        /* Primary color for links */
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
    }
</style> -->

@endsection