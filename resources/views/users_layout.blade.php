<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title> Trung tâm anh ngữ ABC</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <link href="{{ asset('users/Edukate/img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('users/Edukate/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('users/Edukate/lib/owlcarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    {{-- Hoặc nếu bạn muốn theme màu xanh: <link href="{{ asset('users/Edukate/lib/owlcarousel/assets/owl.theme.green.min.css') }}" rel="stylesheet"> --}}

    <link href="{{ asset('users/Edukate/css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- <link href="{{ asset('path/to/font-awesome/css/all.min.css') }}" rel="stylesheet"> -->
</head>
<style>
    .custom-banner {
        background-image: url('storage/avata.png    ');
        background-size: cover;
        background-position: center;
        height: 500px;
        margin-bottom: 90px;
    }
</style>

<body>
    <!-- Topbar Start -->
    @include('pages.layout.hearder')
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function() {
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                // Sử dụng Bootstrap class để đóng (ẩn) alert
                alertBox.classList.remove('show');
                alertBox.classList.add('fade');
                // hoặc xóa hẳn khỏi DOM
                // alertBox.remove();
            }
        }, 5000); // 5000ms = 5 giây
    </script>
    @endif
    <!-- Header Start -->
    <!-- <div class="jumbotron jumbotron-fluid position-relative overlay-bottom" style="margin-bottom: 90px;">
        <div class="container text-center my-5 py-5">

            <h1 class="text-white display-1 mb-5">Chào mừng đến với trung tâm anh ngữ River</h1>
            <h1 class="text-white mt-4 mb-4">Bạn đang tìm khóa học nào của chúng tôi? </h1>
            <div class="mx-auto mb-5" style="width: 100%; max-width: 600px;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-light bg-white text-body px-4 dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Courses</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Courses 1</a>
                            <a class="dropdown-item" href="#">Courses 2</a>
                            <a class="dropdown-item" href="#">Courses 3</a>
                        </div>
                    </div>
                    <input type="text" class="form-control border-light" style="padding: 30px 25px;" placeholder="Keyword">
                    <div class="input-group-append">
                        <button class="btn btn-secondary px-4 px-lg-5">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <div class="jumbotron jumbotron-fluid position-relative overlay-bottom" style="margin-bottom: 90px;">
        <div class="container text-center my-5 py-5">
            <h1 class="text-white display-1 mb-5">Chào mừng đến với trung tâm anh ngữ River</h1>
            <h1 class="text-white mt-4 mb-4">Bạn đang tìm khóa học nào của chúng tôi? </h1>
            <div class="mx-auto mb-5" style="width: 100%; max-width: 900px;">
                <form action="{{route('search') }}" method="GET" class="input-group">
                    <!-- <div class="input-group-prepend">
                        {{-- Select box cho Khóa học --}}
                        <select class="btn btn-outline-light bg-white text-body px-4" name="khoahoc_id">
                            <option value="">Tất cả khóa học</option>
                            @foreach ($khoahocss as $khoaHoc)
                            <option value="{{ $khoaHoc->khoahoc_id }}">
                                Khóa {{ $khoaHoc->khoahoc_ten }} - Trình độ {{ $khoaHoc->trinhdo_ten }}
                            </option>
                            @endforeach

                        </select>

                    </div> -->

                    {{-- Input tìm kiếm theo từ khóa --}}
                    <input type="text" class="form-control border-light" style="padding: 30px 25px;" placeholder="Tìm kiếm theo từ khóa..." name="keyword" id="searchKeyword" value="{{ request('keyword') }}"> <button class="btn btn-outline-secondary" type="button" id="voiceSearchBtn">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <div class="input-group-append">
                        <button class="btn btn-secondary px-4 px-lg-5" type="submit">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Header End -->

    @yield('main-content')
    <!-- About Start -->

    <!-- Contact End -->

    @include('pages.layout.footer')
    <!-- Footer Start -->

    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary rounded-0 btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('users/Edukate/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('users/Edukate/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('users/Edukate/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('users/Edukate/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('users/Edukate/js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy các phần tử DOM cần thiết
            const voiceSearchBtn = document.getElementById('voiceSearchBtn');
            const searchKeywordInput = document.getElementById('searchKeyword'); // Ô input tìm kiếm

            // Kiểm tra xem trình duyệt có hỗ trợ Web Speech API không
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.webkitSpeechRecognition || window.SpeechRecognition;
                const recognition = new SpeechRecognition();

                recognition.continuous = false; // Ngừng ghi âm sau khi phát hiện tạm dừng nói
                recognition.interimResults = false; // Chỉ trả về kết quả cuối cùng
                recognition.lang = 'vi-VN'; // Đặt ngôn ngữ tiếng Việt để nhận dạng tốt hơn

                let isListening = false; // Biến trạng thái để kiểm soát việc đang nghe hay không

                // Xử lý sự kiện khi click vào nút micro
                voiceSearchBtn.addEventListener('click', () => {
                    if (isListening) {
                        recognition.stop(); // Dừng ghi âm nếu đang nghe
                        voiceSearchBtn.innerHTML = '<i class="fas fa-microphone"></i>'; // Đổi lại icon micro
                        isListening = false;
                    } else {
                        recognition.start(); // Bắt đầu ghi âm
                        voiceSearchBtn.innerHTML = '<i class="fas fa-microphone-slash"></i> Đang nghe...'; // Đổi icon/text khi đang nghe
                        isListening = true;
                    }
                });

                // Xử lý kết quả nhận dạng giọng nói
                recognition.onresult = (event) => {
                    const last = event.results.length - 1;
                    const transcript = event.results[last][0].transcript;
                    searchKeywordInput.value = transcript; // Đặt kết quả vào ô input tìm kiếm

                    // Tùy chọn: Tự động submit form sau khi có kết quả
                    voiceSearchBtn.innerHTML = '<i class="fas fa-microphone"></i>'; // Đổi lại icon micro
                    isListening = false;
                    // Tìm form cha và submit nó
                    searchKeywordInput.closest('form').submit();
                };

                // Xử lý lỗi khi nhận dạng giọng nói
                recognition.onerror = (event) => {
                    console.error('Lỗi nhận dạng giọng nói:', event.error);
                    searchKeywordInput.placeholder = 'Có lỗi xảy ra khi nhận dạng giọng nói.';
                    voiceSearchBtn.innerHTML = '<i class="fas fa-microphone"></i>';
                    isListening = false;
                };

                // Xử lý khi quá trình nhận dạng kết thúc
                recognition.onend = () => {
                    // Chỉ thay đổi trạng thái nếu không phải do lỗi mà quá trình đã kết thúc tự nhiên
                    if (isListening) {
                        voiceSearchBtn.innerHTML = '<i class="fas fa-microphone"></i>';
                        isListening = false;
                    }
                };

            } else {
                // Ẩn nút nếu trình duyệt không hỗ trợ Web Speech API
                voiceSearchBtn.style.display = 'none';
                console.warn('Trình duyệt của bạn không hỗ trợ Web Speech API. Hãy thử Chrome hoặc Edge.');
            }
        });
    </script>
</body>

</html>