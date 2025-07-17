 @extends('users_layout')
 @section('main-content')

 <div class="container-fluid py-5">
     <div class="container py-5">
         <div class="row">
             <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
                 <div class="position-relative h-100">
                     <img class="position-absolute w-100 h-100" src="{{ asset('storage/river.jpg') }}" style="object-fit: cover;">
                 </div>
             </div>
             <div class="col-lg-7">
                 <div class="section-title position-relative mb-4">
                     <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Về chúng tôi</h6>
                     <h1 class="display-4">Lựa chọn đầu tiên cho giáo dục anh ngữ hàng đầu tại Việt Nam</h1>
                 </div>
                 <p>Trung tâm Anh ngữ của chúng tôi là nơi đào tạo tiếng Anh uy tín, chuyên nghiệp, phù hợp cho mọi lứa tuổi và trình độ. Với đội ngũ giáo viên giàu kinh nghiệm, chương trình học hiện đại cùng môi trường học tập thân thiện, trung tâm cam kết mang đến cho học viên nền tảng tiếng Anh vững chắc, tự tin giao tiếp và phát triển toàn diện kỹ năng ngôn ngữ.</p>
                 <p>Trung tâm còn cung cấp đa dạng các khóa học như tiếng Anh giao tiếp, luyện thi IELTS, TOEIC, tiếng Anh trẻ em và tiếng Anh doanh nghiệp, đáp ứng nhu cầu học tập của từng đối tượng. Cơ sở vật chất được đầu tư hiện đại, lớp học quy mô nhỏ giúp học viên dễ dàng tiếp thu và tương tác trực tiếp với giáo viên. Chúng tôi luôn đồng hành cùng học viên trên hành trình chinh phục tiếng Anh hiệu quả và bền vững.

                 </p>
                 <!-- <div class="row pt-3 mx-0">
                     <div class="col-3 px-0">
                         <div class="bg-success text-center p-4">
                             <h1 class="text-white" data-toggle="counter-up">123</h1>
                             <h6 class="text-uppercase text-white">Available<span class="d-block">Subjects</span></h6>
                         </div>
                     </div>
                     <div class="col-3 px-0">
                         <div class="bg-primary text-center p-4">
                             <h1 class="text-white" data-toggle="counter-up">1234</h1>
                             <h6 class="text-uppercase text-white">Online<span class="d-block">Courses</span></h6>
                         </div>
                     </div>
                     <div class="col-3 px-0">
                         <div class="bg-secondary text-center p-4">
                             <h1 class="text-white" data-toggle="counter-up">123</h1>
                             <h6 class="text-uppercase text-white">Skilled<span class="d-block">Instructors</span></h6>
                         </div>
                     </div>
                     <div class="col-3 px-0">
                         <div class="bg-warning text-center p-4">
                             <h1 class="text-white" data-toggle="counter-up">1234</h1>
                             <h6 class="text-uppercase text-white">Happy<span class="d-block">Students</span></h6>
                         </div>
                     </div>
                 </div> -->
             </div>
         </div>
     </div>
 </div>
 <!-- About End -->


 <!-- Feature Start -->
 <div class="container-fluid bg-image" style="margin: 90px 0;">
     <div class="container">
         <div class="row">
             <div class="col-lg-7 my-5 pt-5 pb-lg-5">
                 <div class="section-title position-relative mb-4">
                     <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Tại sao chọn chúng tôi?</h6>
                     <h1 class="display-4">Tại sao bạn nên bắt đầu học với chúng tôi?</h1>
                 </div>
                 <p class="mb-4 pb-2">Trung tâm Anh ngữ của chúng tôi là lựa chọn lý tưởng cho những ai mong muốn cải thiện và nâng cao trình độ tiếng Anh một cách hiệu quả. Với đội ngũ giáo viên giàu kinh nghiệm, tận tâm và luôn cập nhật phương pháp giảng dạy hiện đại, chúng tôi cam kết mang đến chất lượng đào tạo hàng đầu.</p>
                 <div class="d-flex mb-3">
                     <div class="btn-icon bg-primary mr-4">
                         <i class="fa fa-2x fa-graduation-cap text-white"></i>
                     </div>
                     <div class="mt-n1">
                         <h4>Giảng viên có tay nghề cao</h4>
                         <p>Với đội ngũ giáo viên giàu kinh nghiệm, tận tâm và luôn cập nhật phương pháp giảng dạy hiện đại, chúng tôi cam kết mang đến chất lượng đào tạo hàng đầu.</p>
                     </div>
                 </div>
                 <div class="d-flex mb-3">
                     <div class="btn-icon bg-secondary mr-4">
                         <i class="fa fa-2x fa-certificate text-white"></i>
                     </div>
                     <div class="mt-n1">
                         <h4>Chứng chỉ quốc tế.</h4>
                         <p>Chứng chỉ tiếng Anh quốc tế là minh chứng quan trọng cho năng lực sử dụng ngôn ngữ và được công nhận rộng rãi trên toàn thế giới.</p>
                     </div>
                 </div>
                 <div class="d-flex">
                     <div class="btn-icon bg-warning mr-4">
                         <i class="fa fa-2x fa-book-reader text-white"></i>
                     </div>
                     <div class="mt-n1">
                         <h4>Lớp học</h4>
                         <p class="m-0">Trung tâm chúng tôi cung cấp cho bạn các lớp học thoải mái và đầy đủ tiện nghi</p>
                     </div>
                 </div>
             </div>
             <div class="col-lg-5" style="min-height: 500px;">
                 <div class="position-relative h-100">
                     <img class="position-absolute w-100 h-100" src="{{ asset('users/Edukate/img/feature.jpg') }}" style="object-fit: cover;">
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Feature Start -->


 <!-- Courses Start -->
 @include('pages.courses')



 <div class="container-fluid py-5">
     <div class="container py-5">
         <div class="section-title text-center position-relative mb-5">
             <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Giảng viên</h6>
             <h1 class="display-4">Gặp gỡ giảng viên của chúng tôi</h1>
         </div>
         <div class="owl-carousel team-carousel position-relative" style="padding: 0 30px;">
             @forelse($teachers as $teacher)
             <div class="team-item">
                 {{-- Đường dẫn ảnh của giảng viên --}}
                 {{-- Giả sử trường 'image' trong database lưu tên file ảnh (ví dụ: 'team-1.jpg') --}}
                 <img class="img-fluid w-100" src="{{ asset('storage/teacher_images/' . $teacher->hinhanh) }}" alt="{{ $teacher->ten }}">
                 <div class="bg-light text-center p-4">
                     <h5 class="mb-3">{{ $teacher->ten }}</h5>
                     <p class="mb-2">{{ $teacher->chuyenmon->tenchuyenmon ?? '' }}</p> {{-- Giả sử có trường 'specialization' (chuyên môn) --}}
                     <!-- <div class="d-flex justify-content-center">
                         {{-- Các liên kết mạng xã hội (kiểm tra xem trường có tồn tại không) --}}
                         @if($teacher->twitter_url)
                         <a class="mx-1 p-1" href="{{ $teacher->twitter_url }}" target="_blank"><i class="fab fa-twitter"></i></a>
                         @endif
                         @if($teacher->facebook_url)
                         <a class="mx-1 p-1" href="{{ $teacher->facebook_url }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                         @endif
                         @if($teacher->linkedin_url)
                         <a class="mx-1 p-1" href="{{ $teacher->linkedin_url }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                         @endif
                         @if($teacher->instagram_url)
                         <a class="mx-1 p-1" href="{{ $teacher->instagram_url }}" target="_blank"><i class="fab fa-instagram"></i></a>
                         @endif
                         @if($teacher->youtube_url)
                         <a class="mx-1 p-1" href="{{ $teacher->youtube_url }}" target="_blank"><i class="fab fa-youtube"></i></a>
                         @endif
                     </div> -->
                     <div class="d-flex justify-content-center">
                         <a class="mx-1 p-1" href="#"><i class="fab fa-twitter"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-facebook-f"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-instagram"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-youtube"></i></a>
                     </div>
                 </div>
             </div>
             @empty
             <div class="col-12 text-center py-5">
                 <p>Không có giảng viên nào để hiển thị.</p>
             </div>
             @endforelse
         </div>
     </div>
 </div>
 <!-- Team Start -->
 <!-- <div class="container-fluid py-5">
     <div class="container py-5">
         <div class="section-title text-center position-relative mb-5">
             <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Giảng viên</h6>
             <h1 class="display-4">Gặp gỡ giảng viên của chúng tôi</h1>
         </div>
         <div class="owl-carousel team-carousel position-relative" style="padding: 0 30px;">
             <div class="team-item">
                 <img class="img-fluid w-100" src="img/team-1.jpg" alt="">
                 <div class="bg-light text-center p-4">
                     <h5 class="mb-3">Instructor Name</h5>
                     <p class="mb-2">Web Design & Development</p>
                     <div class="d-flex justify-content-center">
                         <a class="mx-1 p-1" href="#"><i class="fab fa-twitter"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-facebook-f"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-instagram"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-youtube"></i></a>
                     </div>
                 </div>
             </div>
             <div class="team-item">
                 <img class="img-fluid w-100" src="img/team-2.jpg" alt="">
                 <div class="bg-light text-center p-4">
                     <h5 class="mb-3">Instructor Name</h5>
                     <p class="mb-2">Web Design & Development</p>
                     <div class="d-flex justify-content-center">
                         <a class="mx-1 p-1" href="#"><i class="fab fa-twitter"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-facebook-f"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-instagram"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-youtube"></i></a>
                     </div>
                 </div>
             </div>
             <div class="team-item">
                 <img class="img-fluid w-100" src="img/team-3.jpg" alt="">
                 <div class="bg-light text-center p-4">
                     <h5 class="mb-3">Instructor Name</h5>
                     <p class="mb-2">Web Design & Development</p>
                     <div class="d-flex justify-content-center">
                         <a class="mx-1 p-1" href="#"><i class="fab fa-twitter"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-facebook-f"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-instagram"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-youtube"></i></a>
                     </div>
                 </div>
             </div>
             <div class="team-item">
                 <img class="img-fluid w-100" src="img/team-4.jpg" alt="">
                 <div class="bg-light text-center p-4">
                     <h5 class="mb-3">Instructor Name</h5>
                     <p class="mb-2">Web Design & Development</p>
                     <div class="d-flex justify-content-center">
                         <a class="mx-1 p-1" href="#"><i class="fab fa-twitter"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-facebook-f"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-instagram"></i></a>
                         <a class="mx-1 p-1" href="#"><i class="fab fa-youtube"></i></a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div> -->
 <!-- Team End -->


 <!-- Testimonial Start -->
 <div class="container-fluid bg-image py-5" style="margin: 90px 0;">
     <div class="container py-5">
         <div class="row align-items-center">
             <div class="col-lg-5 mb-5 mb-lg-0">
                 <div class="section-title position-relative mb-4">
                     <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Lời chứng thực</h6>
                     <h1 class="display-4">Những gì học viên nói về chúng tôi</h1>
                 </div>
                 <!-- <p class="m-0">Dolor est dolores et nonumy sit labore dolores est sed rebum amet, justo duo ipsum sanctus dolore magna rebum sit et. Diam lorem ea sea at. Nonumy et at at sed justo est nonumy tempor. Vero sea ea eirmod, elitr ea amet diam ipsum at amet. Erat sed stet eos ipsum diam</p> -->
             </div>
             <div class="col-lg-7">
                 <div class="owl-carousel testimonial-carousel">
                     <div class="bg-white p-5">
                         <i class="fa fa-3x fa-quote-left text-primary mb-4"></i>
                         <p>Mình đã học tại trung tâm 6 tháng để luyện thi TOEIC và thật sự rất hài lòng. Giáo viên nhiệt tình, luôn sẵn sàng hỗ trợ ngoài giờ học, và đặc biệt là phương pháp giảng dạy rất dễ hiểu. Nhờ đó, mình đã đạt được 650 điểm như mong muốn."</p>
                         <div class="d-flex flex-shrink-0 align-items-center mt-4">
                             <img class="img-fluid mr-4" src="{{ asset('storage/image_student_say.jpg') }}" alt="">
                             <div>
                                 <h5>Danh Thanh Cường</h5>
                                 <span>Khóa luyện thi TOEIC</span>
                             </div>
                         </div>
                     </div>
                     <div class="bg-white p-5">
                         <i class="fa fa-3x fa-quote-left text-primary mb-4"></i>
                         <p>Mình đã học tại trung tâm 6 tháng để luyện thi IELTS và thật sự rất hài lòng. Giáo viên nhiệt tình, luôn sẵn sàng hỗ trợ ngoài giờ học, và đặc biệt là phương pháp giảng dạy rất dễ hiểu. Nhờ đó, mình đã đạt được 7.0 IELTS như mong muốn."</p>
                         <div class="d-flex flex-shrink-0 align-items-center mt-4">
                             <img class="img-fluid mr-4" src="{{ asset('storage/avata.png') }}" alt="">
                             <div>
                                 <h5>Nguyễn Nhật Nam</h5>
                                 <span>Khóa luyện thi IELTS</span>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Testimonial Start -->


 <!-- Contact Start -->
 <!-- <div class="container-fluid py-5">
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
                     <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Đăng kí học </h6>
                     <h1 class="display-4">Nhập thông tin </h1>
                 </div>
                 <div class="contact-form">
                     <form>
                         <div class="row">
                             <div class="col-6 form-group">
                                 <input type="text" class="form-control border-top-0 border-right-0 border-left-0 p-0" placeholder="Your Name" required="required">
                             </div>
                             <div class="col-6 form-group">
                                 <input type="email" class="form-control border-top-0 border-right-0 border-left-0 p-0" placeholder="Your Email" required="required">
                             </div>
                         </div>
                         <div class="form-group">
                             <input type="text" class="form-control border-top-0 border-right-0 border-left-0 p-0" placeholder="Subject" required="required">
                         </div>
                         <div class="form-group">
                             <textarea class="form-control border-top-0 border-right-0 border-left-0 p-0" rows="5" placeholder="Message" required="required"></textarea>
                         </div>
                         <div>
                             <button class="btn btn-primary py-3 px-5" type="submit">Send Message</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </div> -->
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
 @endsection