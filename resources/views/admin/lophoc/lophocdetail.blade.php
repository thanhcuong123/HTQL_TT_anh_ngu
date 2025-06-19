@extends('index')

@section('main-content')
<link rel="stylesheet" href="{{ asset('admin/luanvantemplate/dist/css/hocvien.css') }}">

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="{{ asset('admin/luanvantemplate/dist/css/my.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    /* CSS của bạn ở đây */
    .section {
        display: none;
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        width: 100%;
    }

    h3 {
        margin-top: 10px;
    }

    .section.active {
        display: block;
    }

    .section-header-container {
        display: flex;
        margin-bottom: 20px;
    }

    .section-header {
        cursor: pointer;
        background-color: #1f262d;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-align: center;
        transition: background-color 0.3s;
        margin-right: 15px;
    }

    .section-header.active {
        background-color: #27a9e3;
    }

    .section-header:hover {
        background-color: #777777;
    }

    .containerr {
        width: 100%;
        max-width: 1450px;
        margin-left: 100px;
    }

    .section .mb-3 {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
        margin-left: 120px;
        gap: 12px;
    }

    .section .mb-3 label.form-label {
        flex: 0 0 150px;
        font-weight: bold;
        color: #333;
    }

    .section .mb-3 input.form-control {
        flex: 1;
        padding: 8px 12px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        max-width: 500px;
        width: 100%;
        background-color: white;
    }

    #addGiaoVienFormContainer {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        /* Center horizontally */
        transform: translate(-50%, -50%);
        /* Center horizontally and vertically */
        background: white;
        border: 1px solid #ddd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 20px;
        border-radius: 8px;
        z-index: 1050;
        width: 550px;
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;

    }

    #addGiaoVienFormContainer.show {
        display: block;
        opacity: 1;
        transform: translate(-50%, -50%);

    }

    .close-btn {
        position: absolute;
        /* Đặt vị trí tuyệt đối */
        top: 10px;
        /* Cách lề trên 10px */
        right: 15px;
        /* Cách lề phải 15px */
        font-size: 1.5rem;
        /* Tăng kích thước dấu X */
        font-weight: bold;
        /* Làm đậm dấu X */
        cursor: pointer;
        /* Biến đổi con trỏ thành bàn tay khi rê chuột */
        background: none;
        /* Bỏ nền của nút */
        border: none;
        /* Bỏ viền của nút */
        color: #555;
        /* Màu sắc của dấu X */
        padding: 0;
        /* Bỏ padding mặc định của button */
    }

    .close-btn:hover {
        color: #000;
        /* Đổi màu khi rê chuột */
    }

    .form-actions {
        text-align: right;
        margin-top: 20px;
        padding-right: 20px;
        /* Điều chỉnh để nút không sát mép quá */
    }

    /* Lớp phủ mờ */
    .overlay {
        display: none;
        /* Ẩn lớp phủ mặc định */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Màu nền mờ */
        z-index: 1049;
        /* Đặt z-index thấp hơn form */
    }



    .custom {
        display: none;
        /* Ẩn lớp phủ mặc định */
        position: fixed;
        top: 0;
        left: 0;

        background-color: rgba(0, 0, 0, 0.5);
        /* Màu nền mờ */
        z-index: 1049;
        /* Đặt z-index thấp hơn form */
    }

    .custom-modal {
        position: fixed;
        inset: 0;
        /* top: 0; right: 0; bottom: 0; left: 0 */
        z-index: 1050;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.5);
        /* backdrop-filter: blur(2px); */
        /* làm mờ nền đẹp hơn */
    }

    .custom-modal-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        width: 80%;
        max-width: 900px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-out;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        font-weight: bold;
        color: #999;
        border: none;
        background: none;
        cursor: pointer;
        transition: color 0.2s;
    }

    .close-btn:hover {
        color: #333;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    #addHocVienFormContainer {
        display: none;
        /* Đảm bảo form không hiển thị mặc định */
    }

    /* Improved form container styles */
    #addLichHocFormContainer .custom-modal-content form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        /* Equal spacing between each form group */
        max-width: 100%;
    }

    /* Style for each form group */
    #addLichHocFormContainer .mb-3 {
        display: flex;
        flex-direction: column;
    }

    /* Uniform label style */
    #addLichHocFormContainer label.form-label {
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 1rem;
    }

    /* Style selects and inputs consistently */
    #addLichHocFormContainer select.form-select,
    #addLichHocFormContainer input.form-control,
    #addLichHocFormContainer input[type="date"] {
        padding: 10px 14px;
        font-size: 1rem;
        border-radius: 8px;
        border: 1.5px solid #ccc;
        transition: border-color 0.3s ease;
        max-width: 100%;
        box-sizing: border-box;
        background-color: #fff;
        color: #333;
    }

    #addLichHocFormContainer select.form-select:focus,
    #addLichHocFormContainer input.form-control:focus,
    #addLichHocFormContainer input[type="date"]:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 6px rgba(99, 102, 241, 0.5);
    }

    /* Form actions aligned right with spacing */
    #addLichHocFormContainer .form-actions {
        margin-top: 10px;
        display: flex;
        justify-content: flex-end;
    }

    #addLichHocFormContainer .form-actions button {
        min-width: 100px;
        padding: 10px 16px;
        font-size: 1rem;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        transition: background-color 0.3s ease;
    }

    #addLichHocFormContainer .form-actions button.btn-success {
        background-color: #6366f1;
        color: white;
    }

    #addLichHocFormContainer .form-actions button.btn-success:hover,
    #addLichHocFormContainer .form-actions button.btn-success:focus {
        background-color: #4f46e5;
        outline: none;
        box-shadow: 0 0 8px rgba(79, 70, 229, 0.7);
    }

    /* Responsive adjustment - keep full width on small screens */
    @media (max-width: 600px) {
        #addLichHocFormContainer .custom-modal-content {
            width: 90%;
            padding: 20px;
        }

        #addLichHocFormContainer select.form-select,
        #addLichHocFormContainer input.form-control,
        #addLichHocFormContainer input[type="date"] {
            font-size: 0.9rem;
            padding: 8px 12px;
        }
    }
</style>
<div class="containerr">
    <h3>Thông tin chi tiết lớp học: {{ $lophoc->tenlophoc }}</h3>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <div class="section-header-container">
        <div class="section-header active" id="infoLopHoc">Thông tin chi tiết lớp học</div>
        <div class="section-header" id="infoGiaoVien">Thông tin giáo viên</div>
        <div class="section-header" id="infoHocVien">Thông tin học viên</div>
        <div class="section-header" id="infoThoiKhoaBieu">Lịch học</div>
    </div>

    <div class="section" id="sectionLopHoc">
        <div class="mb-3">
            <label for="malophoc" class="form-label"><strong>Mã lớp học:</strong></label>
            <input type="text" id="malophoc" name="malophoc" class="form-control" value="{{ $lophoc->malophoc }}" readonly>
        </div>
        <div class="mb-3">
            <label for="tenkhoahoc" class="form-label"><strong>Tên khóa học:</strong></label>
            <input type="text" id="tenkhoahoc" name="tenkhoahoc" class="form-control" value="{{ $lophoc->khoahoc->ten }}" readonly>
        </div>
        <div class="mb-3">
            <label for="trinhdo" class="form-label"><strong>Trình độ:</strong></label>
            <input type="text" id="trinhdo" name="trinhdo" class="form-control" value="{{ $lophoc->trinhdo->ten }}" readonly>
        </div>
        <div class="mb-3">
            <label for="trinhdo" class="form-label"><strong>Kỹ năng:</strong></label>
            <input type="text" id="trinhdo" name="trinhdo" class="form-control" value="{{ $lophoc->trinhdo->kynang->ten ?? '' }}" readonly>
        </div>
        <div class="mb-3">
            <label for="ngaybatdau" class="form-label"><strong>Ngày bắt đầu:</strong></label>
            <input type="date" id="ngaybatdau" name="ngaybatdau" class="form-control" value="{{ $lophoc->ngaybatdau }}" readonly>
        </div>
        <div class="mb-3">
            <label for="ngayketthuc" class="form-label"><strong>Ngày kết thúc:</strong></label>
            <input type="date" id="ngayketthuc" name="ngayketthuc" class="form-control" value="{{ $lophoc->ngayketthuc }}" readonly>
        </div>
        <div class="mb-3">
            <label for="soluonghocvientoida" class="form-label"><strong>Số lượng học viên tối đa:</strong></label>
            <input type="text" id="soluonghocvientoida" name="soluonghocvientoida" class="form-control" value="{{ $lophoc->soluonghocvientoida }}" readonly>
        </div>
        <div class="mb-3">
            <label for="soluonghocvienhientai" class="form-label"><strong>Số lượng học viên hiện tại:</strong></label>
            <input type="text" id="soluonghocvienhientai" name="soluonghocvienhientai" class="form-control" value="{{ $lophoc->soluonghocvienhientai }}" readonly>
        </div>
        <div class="mb-3">
            <label for="trangthai" class="form-label"><strong>Trạng thái:</strong></label>
            <input type="text" id="trangthai" name="trangthai" class="form-control" value="{{ $lophoc->trangthai }}" readonly>
        </div>
        <div class="mb-3">
            <label for="lichhoc" class="form-label"><strong>Ngày học:</strong></label>
            <input type="text" id="lichhoc" name="lichhoc" class="form-control" value="{{ $lophoc->lichoc }}" readonly>
        </div>
    </div>

    <div class="section" id="sectionGiaoVien">
        @if (!$giaovien)
        <p style="color:red;text-align:center;">Chưa có giáo viên nào phụ trách lớp này!</p>
        <button id="btnShowAddGiaoVien" class="btn btn-primary">Thêm giáo viên vào phụ trách</button>
        @else
        <div class="mb-3">
            <label for="tengiaovien" class="form-label"><strong>Tên giáo viên:</strong></label>
            <input type="text" id="tengiaovien" name="tengiaovien" class="form-control" value="{{ $giaovien->ten }}" readonly>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label"><strong>Email:</strong></label>
            <input type="text" id="email" name="email" class="form-control" value="{{ $giaovien->user->email }}" readonly>
        </div>
        <div class="mb-3">
            <label for="sdt" class="form-label"><strong>Số điện thoại:</strong></label>
            <input type="text" id="sdt" name="sdt" class="form-control" value="{{ $giaovien->sdt}}" readonly>
        </div>
        <button id="btnShowUpdateGiaoVien" class="btn btn-primary">Thay đổi giáo viên phụ trách</button>
        @endif
    </div>

    <!-- Lớp phủ mờ -->

    {{-- Form thêm/cập nhật giáo viên phụ trách (ẩn) --}}
    <div id="addGiaoVienFormContainer" class="custom">
        <button class="close-btn" title="Đóng">&times;</button>
        <h5 id="formTitle" style="margin-bottom:20px">Chọn giáo viên phụ trách</h5>
        {{-- Action sẽ được cập nhật bằng JS --}}
        <form id="giaovienForm" action="" method="POST">
            @csrf
            {{-- Nếu là cập nhật, thêm @method('PUT') --}}
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="mb-3">
                <label for="giaovien_id" class="form-label">Giáo viên:</label>
                <select id="giaovien_id" name="giaovien_id" class="form-select" style="width:380px" required>
                    <option value="">-- Chọn giáo viên --</option>
                    @foreach ($allgiaovien as $gv)
                    <option value="{{ $gv->id }}" {{ ($giaovien && $gv->id == $giaovien->id) ? 'selected' : '' }}>
                        {{ $gv->ten }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>

    <div class="section" id="sectionHocVien">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5></h5>
            <button class="btn btn-success" id="btnShowAddHocVien">+ Thêm học viên</button>
        </div>

        @if ($hocvien->isEmpty())
        <p style="color: red; text-align: center;">Chưa có học viên nào trong lớp: {{ $lophoc->tenlophoc  }}</p>
        @else
        <table class="table table-bordered">
            <thead style="background-color: #66CCFF;">
                <tr>
                    <th style="width: 60px;">STT</th>
                    <th>Họ tên học viên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($hocvien as $index => $hv)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hv->ten }}</td>
                    <td>{{ $hv->user->email }}</td>
                    <td>{{ $hv->sdt }}</td>
                    <td>
                        {{-- Bạn có thể thêm các nút hành động ở đây, ví dụ: --}}
                        {{-- <a href="{{ route('hocvien.show', $hv->id) }}" class="btn btn-info btn-sm">Xem</a> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <div id="addHocVienFormContainer" class="custom-modal">
        <div class="custom-modal-content">
            <button class="close-btn" onclick="document.getElementById('addHocVienFormContainer').style.display='none'">&times;</button>

            <h5 class="mb-3">Chọn học viên từ danh sách đăng ký</h5>

            <form method="POST" action="{{ route('lophoc.addhocvien',$lophoc->id) }}">
                @csrf

                <div class="table-responsive" style="max-height:400px; overflow-y:auto;">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>STT</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hocvienn as $index => $hvdk)
                            <tr>
                                <td><input type="checkbox" name="hocvien_ids[]" value="{{ $hvdk->id }}"></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $hvdk->ten }}</td>
                                <td>{{ $hvdk->user->email }}</td>
                                <td>{{ $hvdk->sdt }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Thêm học viên</button>
                </div>
            </form>
        </div>
    </div>






    <div class="section" id="sectionThoiKhoaBieu">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5></h5>
            <button type="button" class="btn btn-primary" id="btnShowAddLichHoc">Thêm lịch học</button>
        </div>
        @if ($thoikhoabieu->isEmpty())
        <p style="color: red; text-align: center;">Chưa có Lịch học cho lớp: {{ $lophoc->tenlophoc }}</p>
        @else
        <table class="table">
            <thead>
                <tr>
                    <th>Giáo viên </th>
                    <th>Phòng học</th>
                    <th>Thứ</th>
                    <th>Ca học</th>
                    <th>Kỹ năng</th>
                    <!-- <th> Ngày học</th> -->
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($thoikhoabieu as $item)
                <tr>
                    <td>{{ $item->giaovien->ten }}</td>
                    <td>{{ $item->phonghoc->tenphong}}</td>
                    <td>{{ $item->thu->tenthu }}</td>
                    <td>{{ $item->cahoc->tenca }}</td>
                    <td>{{ $item->kynang->ten}}</td>
                    <!-- <td>{{$item->ngayhoc }}</td> -->
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Modal for adding class schedule -->
    <div id="addLichHocFormContainer" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <button class="close-btn" onclick="document.getElementById('addLichHocFormContainer').style.display='none'">&times;</button>
            <h5 class="mb-3">Thêm lịch học</h5>
            <form id="lichHocForm" action="{{ route('lophoc.addlichhoc',[$lophoc ->id]) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="giaovien_id" class="form-label">Giáo viên:</label>
                    <select id="giaovien_id" name="giaovien_id" class="form-select" required>
                        <option value="">-- Chọn giáo viên --</option>
                        @foreach ($allgiaovien as $gv)
                        <option value="{{ $gv->id }}">{{ $gv->ten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="phonghoc_id" class="form-label">Phòng học:</label>
                    <select id="phonghoc_id" name="phonghoc_id" class="form-select" required>
                        <option value="">-- Chọn phòng học --</option>
                        @foreach ($allphonghoc as $ph)
                        <option value="{{ $ph->id }}">{{ $ph->tenphong }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="thu_id" class="form-label">Thứ:</label>
                    <select id="thu_id" name="thu_id" class="form-select" required>
                        <option value="">-- Chọn thứ --</option>
                        @foreach ($allthu as $thu)
                        <option value="{{ $thu->id }}">{{ $thu->tenthu }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cahoc_id" class="form-label">Ca học:</label>
                    <select id="cahoc_id" name="cahoc_id" class="form-select" required>
                        <option value="">-- Chọn ca học --</option>
                        @foreach ($allcahoc as $ca)
                        <option value="{{ $ca->id }}">{{ $ca->tenca }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="kynang_id" class="form-label">Kỹ năng:</label>
                    <select id="kynang_id" name="kynang_id" class="form-select" required>
                        <option value="">-- Chọn kỹ năng --</option>
                        @foreach ($allkynang as $kn)
                        <option value="{{ $kn->id }}">{{ $kn->ten }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- <div class="mb-3">
                    <label for="ngayhoc" class="form-label">Ngày học:</label>
                    <input type="date" id="ngayhoc" name="ngayhoc" class="form-control" required>
                </div> -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </form>
        </div>
    </div>


</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chuyển tab
        const sections = document.querySelectorAll('.section');
        const headers = document.querySelectorAll('.section-header');
        headers.forEach(header => {
            header.addEventListener('click', function() {
                sections.forEach(s => s.classList.remove('active'));
                headers.forEach(h => h.classList.remove('active'));
                const id = 'section' + header.id.replace('info', '');
                document.getElementById(id).classList.add('active');
                header.classList.add('active');
            });
        });

        // Mở tab mặc định
        document.getElementById('infoLopHoc').click();

        // Lấy các phần tử form và nút
        const formContainer = document.getElementById('addGiaoVienFormContainer');
        const btnClose = formContainer.querySelector('.close-btn');
        const giaovienForm = document.getElementById('giaovienForm');
        const formTitle = document.getElementById('formTitle');
        const formMethod = document.getElementById('formMethod');
        const giaovienSelect = document.getElementById('giaovien_id');

        // Logic hiển thị form "Thêm giáo viên"
        const btnShowAdd = document.getElementById('btnShowAddGiaoVien');
        if (btnShowAdd) {
            btnShowAdd.addEventListener('click', () => {
                formTitle.textContent = 'Thêm giáo viên phụ trách mới';
                giaovienForm.action = "{{ route('lophoc.storegiaovien', $lophoc->id) }}";
                formMethod.value = 'POST'; // Đảm bảo là POST cho action thêm mới
                giaovienSelect.value = ""; // Đặt lại lựa chọn trống
                formContainer.classList.add('show');
            });
        }

        // Logic hiển thị form "Cập nhật giáo viên"
        const btnShowUpdate = document.getElementById('btnShowUpdateGiaoVien');
        if (btnShowUpdate) {
            btnShowUpdate.addEventListener('click', () => {
                formTitle.textContent = 'Cập nhật giáo viên phụ trách';
                giaovienForm.action = "{{ route('lophoc.updateGiaovien',$lophoc ->id) }}";
                formMethod.value = 'PUT'; // Đặt thành PUT cho action cập nhật
                // Giữ lại lựa chọn hiện tại hoặc đặt lại nếu muốn
                giaovienSelect.value = "{{ $giaovien->id ?? '' }}"; // Giữ lại giáo viên hiện tại nếu có
                formContainer.classList.add('show');
            });
        }


        // Đóng form
        btnClose.addEventListener('click', () => {
            formContainer.classList.remove('show');
        });

        // Đóng form khi click ra ngoài(tùy chọn)
        // window.addEventListener('click', function(event) {
        //     if (event.target == formContainer && formContainer.classList.contains('show')) {
        //         formContainer.classList.remove('show');
        //     }
        // });
        document.getElementById('btnShowAddHocVien').addEventListener('click', function() {
            document.getElementById('addHocVienFormContainer').style.display = 'flex';
        });

        document.getElementById('checkAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('input[name="hocvien_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btnShowAddLichHoc').addEventListener('click', function() {
            document.getElementById('addLichHocFormContainer').style.display = 'flex';
        });
        document.querySelector('#addLichHocFormContainer .close-btn').addEventListener('click', function() {
            document.getElementById('addLichHocFormContainer').style.display = 'none';
        });
        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('addLichHocFormContainer')) {
                document.getElementById('addLichHocFormContainer').style.display = 'none';
            }
        });
    });
</script>
@endsection