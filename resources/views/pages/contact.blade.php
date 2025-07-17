@extends('users_layout')
@section('main-content')



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
                            <p class="m-0">30/2 Ninh Kiều, Cần Thơ</p>
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
                            <p class="m-0">tanhcuongstudent@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="section-title position-relative mb-4">
                    <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Trợ Giúp?</h6>
                    <h1 class="display-4">Để lại thông tin</h1>
                </div>
                <div class="contact-form">
                    <form>
                        <div class="row">
                            <div class="col-6 form-group">
                                <input type="text" class="form-control border-top-0 border-right-0 border-left-0 p-0" placeholder="Họ tên" required="required">
                            </div>
                            <div class="col-6 form-group">
                                <input type="email" class="form-control border-top-0 border-right-0 border-left-0 p-0" placeholder="Email của bạn" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control border-top-0 border-right-0 border-left-0 p-0" placeholder="Subject" required="required">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control border-top-0 border-right-0 border-left-0 p-0" rows="5" placeholder="Lời nhắn" required="required"></textarea>
                        </div>
                        <div>
                            <button class="btn btn-primary py-3 px-5" type="submit">Gửi </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
        <br>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.9381920974915!2d105.76128312685167!3d10.021959372652894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0893ea51d4097%3A0x7652808f2e66680c!2zQU5IIE5H4buuIFJJVkVSIC0gQ-G6pk4gVEjGoA!5e0!3m2!1svi!2s!4v1751552211141!5m2!1svi!2s" width="100%" height="450" margin-top="10px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    </div>
</div>


@endsection