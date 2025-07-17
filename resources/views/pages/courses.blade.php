    <div class="container-fluid px-0 py-5">
        <div class="row mx-0 justify-content-center pt-5">
            <div class="col-lg-6">
                <div class="section-title text-center position-relative mb-4">
                    <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Các khóa học của chúng tôi</h6>
                    <h1 class="display-4">Kiểm tra các khóa học mới phát hành của chúng tôi</h1>
                </div>
            </div>
        </div>
        <div class="owl-carousel courses-carousel">
            @forelse($courses as $course)
            <div class="courses-item position-relative">
                {{-- Đảm bảo đường dẫn ảnh đúng. Nếu ảnh lưu trong storage, cần dùng asset/storage --}}
                @if($course->hinhanh) {{-- Đảm bảo có ảnh trước khi hiển thị --}}
                <img class="img-fluid" src="{{ asset('storage/' . $course->hinhanh) }}" alt="Hình ảnh khóa học {{ $course->ten }}">
                @else
                {{-- Tùy chọn: Hiển thị ảnh placeholder nếu không có ảnh --}}
                <img class="img-fluid" src="{{ asset('users/Edukate/img/no-image.jpg') }}" alt="Không có ảnh">
                @endif
                <div class="courses-text">
                    <h4 class="text-center text-white px-3">{{ $course->ten }}</h4>
                    <div class="border-top w-100 mt-3">
                        <!-- <div class="d-flex justify-content-between p-4">
                            {{-- Giả sử bạn có mối quan hệ teacher_id trong bảng courses --}}
                            <span class="text-white"><i class="fa fa-user mr-2"></i>
                                @if($course->teacher)
                                {{ $course->teacher->name }} {{-- Giả sử teacher model có trường 'name' --}}
                                @else
                                Chưa xác định
                                @endif
                            </span>
                            <span class="text-white"><i class="fa fa-star mr-2"></i>{{ number_format($course->rating, 1) }} <small>({{ $course->reviews_count }})</small></span>
                        </div> -->
                    </div>
                    <div class="w-100 bg-white text-center p-4">
                        <a class="btn btn-primary" href="{{ route('courses_detail', $course->id)  }}">Chi tiết khóa học</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p>Không có khóa học nào để hiển thị.</p>
            </div>
            @endforelse
        </div>
        <!-- <div class="row justify-content-center bg-image mx-0 mb-5">
            <div class="col-lg-6 py-5">
                <div class="bg-white p-5 my-5">
                    <h1 class="text-center mb-4">30% Off For New Students</h1>
                    <form>
                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control bg-light border-0" placeholder="Your Name" style="padding: 30px 20px;">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="email" class="form-control bg-light border-0" placeholder="Your Email" style="padding: 30px 20px;">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="custom-select bg-light border-0 px-3" style="height: 60px;">
                                        <option selected>Select A courses</option>
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button class="btn btn-primary btn-block" type="submit" style="height: 60px;">Sign Up Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
    </div>