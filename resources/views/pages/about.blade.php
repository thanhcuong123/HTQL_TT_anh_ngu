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
                <p>Trung tâm còn cung cấp đa dạng các khóa học như tiếng Anh giao tiếp, luyện thi IELTS, TOEIC, tiếng Anh trẻ em và tiếng Anh doanh nghiệp, đáp ứng nhu cầu học tập của từng đối tượng. Cơ sở vật chất được đầu tư hiện đại, lớp học quy mô nhỏ giúp học viên dễ dàng tiếp thu và tương tác trực tiếp với giáo viên. Chúng tôi luôn đồng hành cùng học viên trên hành trình chinh phục tiếng Anh hiệu quả và bền vững.</p>
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
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-7 mb-5 mb-lg-0">
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
@endsection