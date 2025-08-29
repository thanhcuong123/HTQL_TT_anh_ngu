@extends('student.student_index') {{-- Đảm bảo bạn đang extend layout chính của học viên --}}

@section('title-content')
<title>Thông Tin Cá Nhân</title>
@endsection

@section('student-content')
{{-- Tailwind CSS CDN --}}
<script src="https://cdn.tailwindcss.com"></script>
{{-- Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* Custom styles for a more polished look */
    .profile-card {
        background-color: #ffffff;
        border-radius: 1.5rem;
        /* rounded-xl */
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        /* shadow-xl */
        padding: 2.5rem;
        /* p-10 */
        margin-bottom: 2rem;
        /* mb-8 */
        transition: all 0.3s ease-in-out;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px -8px rgba(0, 0, 0, 0.15), 0 10px 15px -7px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        border-bottom: 2px solid #e5e7eb;
        /* border-b-2 border-gray-200 */
        padding-bottom: 1.5rem;
        /* pb-6 */
        margin-bottom: 2rem;
        /* mb-8 */
    }

    .profile-title {
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

    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        /* Responsive grid */
        gap: 1.5rem;
        /* gap-6 */
    }

    .info-item {
        background-color: #f9fafb;
        /* bg-gray-50 */
        border-radius: 0.75rem;
        /* rounded-lg */
        padding: 1.25rem;
        /* p-5 */
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        /* gap-2 */
        border: 1px solid #e5e7eb;
        /* border border-gray-200 */
    }

    .info-item-label {
        font-weight: 600;
        /* font-semibold */
        color: #4a5568;
        /* text-gray-700 */
        font-size: 0.95rem;
        /* text-sm */
        display: flex;
        align-items: center;
        gap: 0.5rem;
        /* gap-2 */
    }

    .info-item-value {
        font-size: 1.125rem;
        /* text-lg */
        color: #2d3748;
        /* text-gray-800 */
        font-weight: 500;
        /* font-medium */
    }

    .profile-image {
        width: 150px;
        /* w-36 */
        height: 150px;
        /* h-36 */
        border-radius: 9999px;
        /* rounded-full */
        object-fit: cover;
        border: 4px solid #3b82f6;
        /* border-4 border-blue-500 */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-card {
            padding: 1.5rem;
            /* p-6 */
        }

        .profile-title {
            font-size: 1.75rem;
            /* text-3xl */
            flex-direction: column;
            text-align: center;
        }

        .profile-image {
            width: 120px;
            height: 120px;
        }

        .profile-header {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}

</div>
@endif
<div class="profile-card">
    <div class="profile-header flex items-center justify-center md:justify-start">
        <img class="profile-image mr-0 md:mr-8 mb-6 md:mb-0"
            src="{{ $hocvien->hinhanh ?? 'https://placehold.co/150x150/a7f3d0/10b981?text=Avatar' }}"
            alt="Ảnh đại diện">
        <div>
            <h1 class="profile-title">
                <i class="fas fa-user-circle text-blue-600"></i>
                Thông Tin Cá Nhân
            </h1>
            <p class="text-xl text-gray-600 mt-2 text-center md:text-left">
                Chào mừng, <span class="font-bold text-blue-700">{{ $hocvien->ten ?? 'Học viên' }}</span>!
            </p>
        </div>
    </div>

    <div class="profile-info-grid">
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-id-badge text-blue-500"></i> Mã học viên:</span>
            <span class="info-item-value">{{ $hocvien->mahocvien ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-envelope text-blue-500"></i> Email:</span>
            <span class="info-item-value">{{ $hocvien->user->email ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-phone-alt text-blue-500"></i> Số điện thoại:</span>
            <span class="info-item-value">{{ $hocvien->sdt ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-map-marker-alt text-blue-500"></i> Địa chỉ:</span>
            <span class="info-item-value">{{ $hocvien->diachi ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-birthday-cake text-blue-500"></i> Ngày sinh:</span>
            <span class="info-item-value">{{ $hocvien->ngaysinh ? \Carbon\Carbon::parse($hocvien->ngaysinh)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-venus-mars text-blue-500"></i> Giới tính:</span>
            <span class="info-item-value">{{ $hocvien->gioitinh ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-calendar-plus text-blue-500"></i> Ngày đăng ký:</span>
            <span class="info-item-value">{{ $hocvien->ngaydangki ? \Carbon\Carbon::parse($hocvien->ngaydangki)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-item-label"><i class="fas fa-user-check text-blue-500"></i> Trạng thái:</span>
            <span class="info-item-value">{{ $hocvien->trangthai ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="mt-8 text-right">
        <a href="{{ route('student.profile.edit') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
            <!-- <i class="fas fa-arrow-left mr-3"></i> -->
            Cập nhật thông tin cá nhân
        </a>
    </div>
</div>

@endsection