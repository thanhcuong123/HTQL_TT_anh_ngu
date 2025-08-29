@extends('index')

@section('title-content')
<title>Quản lý Khóa học</title>
@endsection

@section('main-content')

<!-- <link rel="stylesheet" href="{{ asset('admin/luanvantemplate/dist/css/hocvien.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('admin/custom/khoahoc.css') }}"> {{-- CSS riêng --}} -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<div class="card">
    <div class="card-body">

        {{-- Thông báo --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <h3 class="card-title mb-4">Tạo Khóa học mới</h3>

        <form action="{{ route('khoahoc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Cột Trái --}}
                <div class="col-md-6">
                    {{-- Mã Khóa Học --}}
                    <div class="mb-3">
                        <label for="kh_stt" class="form-label">Mã Khóa Học:</label>
                        <input type="text" class="form-control" value="{{ $newMa }}" disabled>
                        <input type="hidden" name="kh_stt" value="{{ $newMa }}">
                        @error('kh_stt') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tên Khóa Học --}}
                    <div class="mb-3">
                        <label for="kh_ten" class="form-label">Tên Khóa Học:</label>
                        <input type="text" id="kh_ten" name="kh_ten" class="form-control" placeholder="VD: IELTS 6.5" required value="{{ old('kh_ten') }}">
                        @error('kh_ten') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">

                        <label for="nam" class="form-label">Năm Học:</label>
                        <select id="namhoc" name="nam" class="form-select" required style="width:100%">
                            <option value="">-- Chọn Năm --</option>
                            @foreach($nams as $nam)
                            <option value="{{ $nam->id }}" data-nam="{{ $nam->nam }}" {{ old('nam') == $nam->id ? 'selected' : '' }}>
                                {{ $nam->nam }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ma_td" class="form-label">Trình Độ:</label>
                        <select id="ma_td" name="ma_td" class="form-select" required style="width:100%">
                            <option value="">-- Chọn Trình Độ --</option>
                            @foreach($trinhDos as $trinhDo)
                            <option value="{{ $trinhDo->id }}" {{ old('ma_td') == $trinhDo->id ? 'selected' : '' }}>
                                {{ $trinhDo->ten }} ({{ $trinhDo->ma }})
                            </option>
                            @endforeach
                        </select>
                        @error('ma_td') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="kh_ngaykg" class="form-label">Ngày Khai Giảng:</label>
                        <input type="date" id="kh_ngaykg" name="kh_ngaykg" class="form-control" required value="{{ old('kh_ngaykg') }}">
                        @error('kh_ngaykg') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Ngày Kết Thúc --}}
                    <div class="mb-3">
                        <label for="kh_ngaykt" class="form-label">Ngày Kết Thúc:</label>
                        <input type="date" id="kh_ngaykt" name="kh_ngaykt" class="form-control" value="{{ old('kh_ngaykt') }}">
                        @error('kh_ngaykt') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    {{-- Hình Ảnh --}}


                </div>

                {{-- Cột Phải --}}
                <div class="col-md-6">
                    {{-- Ngày Khai Giảng --}}


                    {{-- Trình Độ --}}


                    {{-- Năm Học --}}
                    <!-- <div class="mb-3">
                        <label for="nam" class="form-label">Năm Học:</label>
                        <select id="nam" name="nam" class="form-select" required style="width:100%">
                            <option value="">-- Chọn Năm --</option>
                            @foreach($nams as $nam)
                            <option value="{{ $nam->id }}" {{ old('nam') == $nam->id ? 'selected' : '' }}>
                                {{ $nam->nam }}
                            </option>
                            @endforeach
                        </select>
                        @error('nam') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div> -->
                    <div class="mb-3">
                        <label for="thoiluong" class="form-label">Thời Lượng:</label>
                        <input type="text" id="thoiluong" name="thoiluong" class="form-control" placeholder="VD: 12 tuần" value="{{ old('thoiluong') }}" readonly>
                        @error('thoiluong') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Số Buổi --}}
                    <div class="mb-3">
                        <label for="sobuoi" class="form-label">Số Buổi: (buổi /tuần )</label>
                        <input type="number" id="sobuoi" name="sobuoi" class="form-control" min="1" placeholder="VD: 36" value="{{ old('sobuoi') }}" required>
                        @error('sobuoi') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>


                    {{-- Học Phí --}}
                    <div class="mb-3">
                        <label for="dg_hocphi" class="form-label">Đơn Giá Học Phí:</label>
                        <input type="text" id="dg_hocphi" name="dg_hocphi" class="form-control" required placeholder="VD: 2.000.000" value="{{ old('dg_hocphi') }}" oninput="formatCurrency(this)">


                        <div id="hoc_phi_display" style="margin-top: 5px; font-weight: bold;"></div>
                        @error('dg_hocphi') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="hinhanh" class="form-label">Hình Ảnh:</label>
                        <input type="file" id="hinhanh" name="hinhanh" class="form-control" accept="image/*">
                        @error('hinhanh') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Thời lượng --}}

                    {{-- Mô tả --}}
                    <div class="mb-3">
                        <label for="mota" class="form-label">Mô Tả:</label>

                        <textarea id="mota" name="mota" class="form-control" rows="4" placeholder="VD: Khóa luyện thi IELTS...">{{ old('mota') }}</textarea>
                        @error('mota') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                </div>

            </div>

            {{-- Thông tin lớp học --}}
            <hr class="my-4">
            <h5 class="card-title">Thông tin Lớp học</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="number_of_classes" class="form-label">Số Lượng Lớp cần mở trong khóa học:</label>
                        <input type="number" id="number_of_classes" name="number_of_classes" class="form-control" min="1" max="20" required>
                        @error('number_of_classes') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
                <!-- value="{{ old('number_of_classes', 1) }}" -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="l_slmax" class="form-label">Sĩ Số Tối Đa:</label>
                        <input type="number" id="l_slmax" name="l_slmax" class="form-control" min="1" max="100" required value="{{ old('l_slmax', 30) }}">
                        @error('l_slmax') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Tạo Khóa Học</button>
            <a href="{{ route('khoahoc.index') }}" class="btn btn-secondary mt-3 ms-2">Quay lại</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hocPhiInput = document.getElementById('dg_hocphi');
        const hocPhiDisplay = document.getElementById('hoc_phi_display');

        // Khi load lại form (VD: lỗi validate), format luôn
        if (hocPhiInput.value) {
            hocPhiDisplay.innerText = formatCurrencyDisplay(hocPhiInput.value);
        }

        hocPhiInput.addEventListener('input', function() {
            let value = hocPhiInput.value.replace(/\D/g, ''); // Bỏ mọi ký tự không phải số
            if (value) {
                hocPhiDisplay.innerText = formatCurrencyDisplay(value);
            } else {
                hocPhiDisplay.innerText = '';
            }
        });

        function formatCurrencyDisplay(val) {
            return parseInt(val).toLocaleString('vi-VN') + ' VNĐ';
        }
    });
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('.alert-success').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });



    document.addEventListener('DOMContentLoaded', function() {
        const ngayBatDau = document.getElementById('kh_ngaykg');
        const ngayKetThuc = document.getElementById('kh_ngaykt');
        const thoiluong = document.getElementById('thoiluong');
        const namHoc = document.getElementById('namhoc');

        function parseYearRange(yearRange) {
            const parts = yearRange.split('-');
            return {
                start: parseInt(parts[0]),
                end: parseInt(parts[1])
            };
        }

        function checkNgayVaNamHoc() {
            const startDate = new Date(ngayBatDau.value);
            const endDate = new Date(ngayKetThuc.value);
            const namHocOption = namHoc.options[namHoc.selectedIndex];
            const namHocValue = namHocOption ? namHocOption.dataset.nam : '';

            let valid = true;
            let msg = '';

            if (ngayBatDau.value && ngayKetThuc.value) {
                if (startDate > endDate) {
                    msg = 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.';
                    valid = false;
                }
            }

            if (namHocValue && ngayBatDau.value && ngayKetThuc.value) {
                const namRange = parseYearRange(namHocValue);
                const startYear = startDate.getFullYear();
                const endYear = endDate.getFullYear();

                if (startYear < namRange.start || startYear > namRange.end || endYear < namRange.start || endYear > namRange.end) {
                    msg = 'Ngày bắt đầu và kết thúc phải nằm trong năm học đã chọn.';
                    valid = false;
                }
            }

            if (!valid) {
                ngayBatDau.setCustomValidity(msg);
                ngayKetThuc.setCustomValidity(msg);
                thoiluong.value = '';
                ngayBatDau.reportValidity(); // Gọi ra lỗi ngay
                ngayKetThuc.reportValidity();
            } else {
                ngayBatDau.setCustomValidity('');
                ngayKetThuc.setCustomValidity('');

                if (ngayBatDau.value && ngayKetThuc.value) {
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const weeks = Math.ceil(diffDays / 7);
                    thoiluong.value = weeks + ' tuần';
                } else {
                    thoiluong.value = '';
                }
            }
        }

        ngayBatDau.addEventListener('change', checkNgayVaNamHoc);
        ngayKetThuc.addEventListener('change', checkNgayVaNamHoc);
        namHoc.addEventListener('change', checkNgayVaNamHoc);
    });
</script>
@endsection