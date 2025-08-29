@extends('student.student_index') {{-- Đảm bảo bạn đang extend layout chính của học viên --}}

@section('title-content')
<title>Cập Nhật Thông Tin Cá Nhân</title>
@endsection

@section('student-content')
{{-- Tailwind CSS CDN --}}
<script src="https://cdn.tailwindcss.com"></script>
{{-- Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .update-card {
        background-color: #ffffff;
        border-radius: 1.5rem;
        /* rounded-xl */
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        /* shadow-xl */
        padding: 2.5rem;
        /* p-10 */
        margin-bottom: 2rem;
        /* mb-8 */
    }

    .update-header {
        border-bottom: 2px solid #e5e7eb;
        /* border-b-2 border-gray-200 */
        padding-bottom: 1.5rem;
        /* pb-6 */
        margin-bottom: 2rem;
        /* mb-8 */
    }

    .update-title {
        color: #1a202c;
        /* text-gray-900 */
        font-size: 2.25rem;
        /* text-4xl */
        font-weight: 700;
        /* font-bold */
        display: flex;
        align-items: center;
        gap: 1rem;
        /* gap-4 */
    }

    .form-group {
        margin-bottom: 1.5rem;
        /* mb-6 */
    }

    .form-label {
        display: block;
        font-weight: 600;
        /* font-semibold */
        color: #4a5568;
        /* text-gray-700 */
        margin-bottom: 0.5rem;
        /* mb-2 */
        font-size: 1rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        /* py-3 px-4 */
        border: 1px solid #d1d5db;
        /* border-gray-300 */
        border-radius: 0.5rem;
        /* rounded-lg */
        font-size: 1rem;
        color: #374151;
        /* text-gray-700 */
        background-color: #f9fafb;
        /* bg-gray-50 */
        transition: all 0.2s ease-in-out;
    }

    .form-input:focus {
        border-color: #3b82f6;
        /* border-blue-500 */
        ring: 3px;
        ring-color: rgba(59, 130, 246, 0.3);
        /* ring-blue-300 */
        outline: none;
    }

    .form-input.is-invalid {
        border-color: #ef4444;
        /* border-red-500 */
    }

    .invalid-feedback {
        color: #ef4444;
        /* text-red-500 */
        font-size: 0.875rem;
        /* text-sm */
        margin-top: 0.5rem;
        /* mt-2 */
    }

    .profile-image-preview {
        width: 120px;
        height: 120px;
        border-radius: 9999px;
        /* rounded-full */
        object-fit: cover;
        border: 3px solid #3b82f6;
        /* border-3 border-blue-500 */
        margin-bottom: 1rem;
        /* mb-4 */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #3b82f6;
        /* bg-blue-600 */
        color: #ffffff;
        /* text-white */
        padding: 0.75rem 1.5rem;
        /* px-6 py-3 */
        border-radius: 0.5rem;
        /* rounded-md */
        font-weight: 600;
        /* font-semibold */
        transition: background-color 0.2s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        /* hover:bg-blue-700 */
    }

    .btn-secondary {
        background-color: #6b7280;
        /* bg-gray-500 */
        color: #ffffff;
        /* text-white */
        padding: 0.75rem 1.5rem;
        /* px-6 py-3 */
        border-radius: 0.5rem;
        /* rounded-md */
        font-weight: 600;
        /* font-semibold */
        transition: background-color 0.2s ease-in-out;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        /* hover:bg-gray-600 */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .update-card {
            padding: 1.5rem;
            /* p-6 */
        }

        .update-title {
            font-size: 1.75rem;
            /* text-3xl */
            flex-direction: column;
            text-align: center;
        }

        .profile-image-preview {
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

<div class="update-card">
    <div class="update-header flex flex-col md:flex-row items-center justify-center md:justify-start">
        <img id="imagePreview" class="profile-image-preview mr-0 md:mr-8"
            src="{{ $hocvien->hinhanh ? asset('storage/' . $hocvien->hinhanh) : 'https://placehold.co/120x120/a7f3d0/10b981?text=Avatar' }}"
            alt="Ảnh đại diện">
        <div>
            <h1 class="update-title">
                <i class="fas fa-edit text-blue-600"></i>
                Cập Nhật Thông Tin Cá Nhân
            </h1>
            <p class="text-xl text-gray-600 mt-2 text-center md:text-left">
                Chỉnh sửa thông tin của bạn
            </p>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Thành công!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Sử dụng phương thức PUT cho cập nhật --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <!-- <div class="form-group">
                <label for="ten" class="form-label">Họ tên:</label>
                <input type="text" id="ten" name="ten"
                    class="form-input @error('ten') is-invalid @enderror"
                    value="{{ old('ten', $hocvien->ten) }}" required>
                @error('ten')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> -->

            <div class="form-group">
                <label for="sdt" class="form-label">Số điện thoại:</label>
                <input type="text" id="sdt" name="sdt"
                    class="form-input @error('sdt') is-invalid @enderror"
                    value="{{ old('sdt', $hocvien->sdt) }}" required>
                @error('sdt')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="diachi" class="form-label">Địa chỉ:</label>
                <input type="text" id="diachi" name="diachi"
                    class="form-input @error('diachi') is-invalid @enderror"
                    value="{{ old('diachi', $hocvien->diachi) }}">
                @error('diachi')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ngaysinh" class="form-label">Ngày sinh:</label>
                <input type="date" id="ngaysinh" name="ngaysinh"
                    class="form-input @error('ngaysinh') is-invalid @enderror"
                    value="{{ old('ngaysinh', $hocvien->ngaysinh ? \Carbon\Carbon::parse($hocvien->ngaysinh)->format('Y-m-d') : '') }}">
                @error('ngaysinh')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="gioitinh" class="form-label">Giới tính:</label>
                <select id="gioitinh" name="gioitinh"
                    class="form-input @error('gioitinh') is-invalid @enderror">
                    <option value="">Chọn giới tính</option>
                    <option value="Nam" {{ old('gioitinh', $hocvien->gioitinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('gioitinh', $hocvien->gioitinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ old('gioitinh', $hocvien->gioitinh) == 'Khác' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gioitinh')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="hinhanh" class="form-label">Ảnh đại diện:</label>
                <input type="file" id="hinhanh" name="hinhanh"
                    class="form-input @error('hinhanh') is-invalid @enderror"
                    onchange="previewImage(event)">
                @error('hinhanh')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nếu bạn muốn cho phép cập nhật email, hãy bỏ bình luận phần này --}}
            {{-- <div class="form-group md:col-span-2">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email"
                       class="form-input @error('email') is-invalid @enderror"
                       value="{{ old('email', $hocvien->user->email ?? '') }}" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div> --}}
</div>

<div class="mt-8 flex justify-end space-x-4">
    <a href="{{ route('student.profile') }}" class="btn-secondary inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Hủy
    </a>
    <button type="submit" class="btn-primary inline-flex items-center">
        <i class="fas fa-save mr-2"></i> Lưu thay đổi
    </button>
</div>
</form>
</div>

<script>
    // Hàm xem trước ảnh khi người dùng chọn file
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection