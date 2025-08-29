@extends('index')

@section('title-content')
<title>Chỉnh sửa Khóa học</title>
@endsection

@section('main-content')

{{-- Dùng lại Quill & style --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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

        <h3 class="card-title mb-4">Chỉnh sửa Khóa học</h3>

        <form action="{{ route('khoahoc.update', $khoahoc->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Cột Trái --}}
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">Mã Khóa Học:</label>
                        <input type="text" class="form-control" value="{{ $khoahoc->ma }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tên Khóa Học:</label>
                        <input type="text" name="kh_ten" class="form-control" required value="{{ old('kh_ten', $khoahoc->ten) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Năm Học:</label>
                        <select name="nam" id="namhoc" class="form-select" required style="width:100%">
                            <option value="">-- Chọn Năm --</option>
                            @foreach($nams as $nam)
                            <option value="{{ $nam->id }}" data-nam="{{ $nam->nam }}" {{ $khoahoc->namhoc_id == $nam->id ? 'selected' : '' }}>
                                {{ $nam->nam }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trình Độ:</label>
                        <select name="ma_td" class="form-select" required style="width:100%">
                            <option value="">-- Chọn Trình Độ --</option>
                            @foreach($trinhDos as $trinhDo)
                            <option value="{{ $trinhDo->id }}"
                                {{ optional($khoahoc->lopHocs->first())->trinhdo_id == $trinhDo->id ? 'selected' : '' }}>
                                {{ $trinhDo->ten }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày Khai Giảng:</label>
                        <input type="date" name="kh_ngaykg" id="kh_ngaykg" class="form-control"
                            value="{{ old('kh_ngaykg', $khoahoc->ngaybatdau) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày Kết Thúc:</label>
                        <input type="date" name="kh_ngaykt" id="kh_ngaykt" class="form-control"
                            value="{{ old('kh_ngaykt', $khoahoc->ngayketthuc) }}">
                    </div>

                </div>

                {{-- Cột Phải --}}
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">Thời Lượng:</label>
                        <input type="text" id="thoiluong" name="thoiluong" class="form-control" readonly
                            value="{{ old('thoiluong', $khoahoc->thoiluong) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số Buổi:</label>
                        <input type="number" name="sobuoi" class="form-control" min="1"
                            value="{{ old('sobuoi', $khoahoc->sobuoi) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Học Phí:</label>
                        <input type="text" id="dg_hocphi" name="dg_hocphi" class="form-control"
                            value="{{ old('dg_hocphi', optional($dongia)->hocphi) }}">

                        <div id="hoc_phi_display" style="margin-top:5px; font-weight:bold;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình Ảnh:</label>
                        <input type="file" name="hinhanh" class="form-control">
                        @if($khoahoc->hinhanh)
                        <img src="{{ asset('storage/' . $khoahoc->hinhanh) }}" alt="" style="max-width: 120px; margin-top: 5px;">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả:</label>
                        <textarea name="mota" class="form-control" rows="4">{{ old('mota', $khoahoc->mota) }}</textarea>
                    </div>

                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Cập nhật</button>
            <a href="{{ route('khoahoc.index') }}" class="btn btn-secondary mt-3 ms-2">Quay lại</a>
        </form>
    </div>
</div>

{{-- Script tái dùng --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hocPhiInput = document.getElementById('dg_hocphi');
        const hocPhiDisplay = document.getElementById('hoc_phi_display');

        if (hocPhiInput.value) {
            hocPhiDisplay.innerText = parseInt(hocPhiInput.value).toLocaleString('vi-VN') + ' VNĐ';
        }

        hocPhiInput.addEventListener('input', function() {
            let value = hocPhiInput.value.replace(/\D/g, '');
            if (value) {
                hocPhiDisplay.innerText = parseInt(value).toLocaleString('vi-VN') + ' VNĐ';
            } else {
                hocPhiDisplay.innerText = '';
            }
        });

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
                ngayBatDau.reportValidity();
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