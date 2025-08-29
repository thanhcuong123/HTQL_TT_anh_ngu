@extends('student.student_index') {{-- Đảm bảo bạn đang extend layout chính của học viên --}}

@section('title-content')
<title>Học Phí & Thanh Toán</title>
@endsection

@section('student-content')
{{-- Tailwind CSS CDN --}}
<script src="https://cdn.tailwindcss.com"></script>
{{-- Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .payment-card {
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

    .payment-header {
        border-bottom: 2px solid #e5e7eb;
        /* border-b-2 border-gray-200 */
        padding-bottom: 1.5rem;
        /* pb-6 */
        margin-bottom: 2rem;
        /* mb-8 */
    }

    .payment-title {
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

    /* Updated styling for individual payment class cards */
    .class-payment-item {
        background-color: #ffffff;
        /* White background */
        border-radius: 1rem;
        /* rounded-xl */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        /* Softer shadow */
        padding: 1.5rem;
        /* p-6 */
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        /* gap-3 */
        border: 1px solid #e5e7eb;
        /* Subtle border */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .class-payment-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .class-payment-item h4 {
        font-size: 1.375rem;
        /* text-2xl reduced slightly */
        font-weight: 700;
        /* font-bold */
        color: #2563eb;
        /* text-blue-700 */
        border-bottom: 1px solid #bfdbfe;
        /* border-blue-200 */
        padding-bottom: 0.75rem;
        /* pb-3 */
        margin-bottom: 0.75rem;
        /* mb-3 */
    }

    .payment-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
    }

    .payment-detail-label {
        font-weight: 500;
        /* font-medium */
        color: #4b5563;
        /* text-gray-700 */
    }

    .payment-detail-value {
        font-weight: 600;
        /* font-semibold */
        color: #1f2937;
        /* text-gray-800 */
    }

    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        /* px-3 py-1.5 */
        border-radius: 9999px;
        /* rounded-full */
        font-size: 0.875rem;
        /* text-sm */
        font-weight: 600;
        /* font-semibold */
        text-align: center;
    }

    .status-badge.paid {
        background-color: #d1fae5;
        /* bg-green-100 */
        color: #065f46;
        /* text-green-800 */
    }

    .status-badge.partial {
        background-color: #fffacd;
        /* bg-yellow-100 */
        color: #92400e;
        /* text-yellow-800 */
    }

    .status-badge.unpaid {
        background-color: #fee2e2;
        /* bg-red-100 */
        color: #991b1b;
        /* text-red-800 */
    }

    .status-badge.free {
        background-color: #e0f2fe;
        /* bg-blue-100 */
        color: #1e40af;
        /* text-blue-800 */
    }

    /* New styles for payment instructions */
    .payment-instructions-box {
        background-color: #e0f2fe;
        /* bg-blue-100 */
        border: 1px solid #93c5fd;
        /* border-blue-300 */
        border-radius: 0.75rem;
        /* rounded-lg */
        padding: 1.5rem;
        /* p-6 */
        margin-top: 2rem;
        /* mt-8, increased margin for separation */
        color: #1e40af;
        /* text-blue-800 */
        font-size: 1rem;
    }

    .payment-instructions-box h5 {
        font-size: 1.25rem;
        /* text-xl */
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #1a202c;
        /* text-gray-900 */
    }

    .payment-instructions-box p {
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }

    .payment-instructions-box p strong {
        color: #1a202c;
        /* text-gray-900 */
    }

    .alert-message {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d1fae5;
        /* bg-green-100 */
        color: #065f46;
        /* text-green-800 */
        border: 1px solid #34d399;
        /* border-green-400 */
    }

    .alert-error {
        background-color: #fee2e2;
        /* bg-red-100 */
        color: #991b1b;
        /* text-red-800 */
        border: 1px solid #f87171;
        /* border-red-400 */
    }

    .alert-info {
        background-color: #e0f2fe;
        /* bg-blue-100 */
        color: #1e40af;
        /* text-blue-800 */
        border: 1px solid #60a5fa;
        /* border-blue-400 */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .payment-card {
            padding: 1.5rem;
            /* p-6 */
        }

        .payment-title {
            font-size: 1.75rem;
            /* text-3xl */
            flex-direction: column;
            text-align: center;
        }

        .class-payment-item {
            padding: 1rem;
            /* p-4 */
        }

        .class-payment-item h4 {
            font-size: 1.25rem;
            /* text-xl */
        }

        .payment-detail-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .payment-detail-label {
            margin-bottom: 0.25rem;
        }
    }
</style>

<div class="payment-card">
    <div class="payment-header">
        <h3 class="payment-title">
            <i class="fas fa-money-bill-wave text-blue-600"></i>
            Học Phí & Thanh Toán
        </h3>
        <!-- <p class="text-xl text-gray-600 mt-2 text-center md:text-left">
            Quản lý học phí các lớp học của bạn
        </p> -->
    </div>

    @if (session('success'))
    <div class="alert-message alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert-message alert-error">
        {{ session('error') }}
    </div>
    @endif

    @if (session('info'))
    <div class="alert-message alert-info">
        {{ session('info') }}
    </div>
    @endif

    @if($classPayments->isEmpty())
    <div class="no-schedule-message text-center py-10">
        <i class="fas fa-exclamation-circle text-blue-500 text-4xl mb-4"></i>
        <p class="text-xl text-gray-700">Bạn hiện chưa đăng ký lớp học nào.</p>
        <p class="text-lg text-gray-600 mt-2">Vui lòng đăng ký lớp học để xem thông tin học phí.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        @foreach($classPayments as $data)
        <div class="class-payment-item">
            <h4>{{ $data['lophoc']->malophoc ?? 'N/A' }} - {{ $data['lophoc']->tenlophoc ?? 'N/A' }}</h4>
            <div class="payment-detail-row">
                <span class="payment-detail-label">Tổng học phí:</span>
                <span class="payment-detail-value">{{ number_format($data['total_tuition'], 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="payment-detail-row">
                <span class="payment-detail-label">Đã thanh toán:</span>
                <span class="payment-detail-value text-green-600">{{ number_format($data['amount_paid'], 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="payment-detail-row">
                <span class="payment-detail-label">Còn lại:</span>
                <span class="payment-detail-value text-red-600">{{ number_format($data['remaining_balance'], 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="payment-detail-row">
                <span class="payment-detail-label">Trạng thái:</span>
                <span class="status-badge
                            @if($data['payment_status'] == 'Đã thanh toán') paid
                            @elseif($data['payment_status'] == 'Thanh toán một phần') partial
                            @elseif($data['payment_status'] == 'Miễn phí / Không xác định') free
                            @else unpaid
                            @endif
                        ">
                    {{ $data['payment_status'] }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Payment Instructions Box - Moved outside the foreach loop --}}
    <div class="payment-instructions-box">
        <h5><i class="fas fa-info-circle"></i> Hướng dẫn thanh toán:</h5>
        <p>Vui lòng chuyển khoản học phí vào tài khoản sau:</p>
        <p><strong>Ngân hàng:</strong> Ngân hàng ABC</p>
        <p><strong>Số tài khoản:</strong> 1234 5678 9101 112</p>
        <p><strong>Chủ tài khoản:</strong> Trung Tâm Anh Ngữ River</p>
        <p><strong>Nội dung chuyển khoản:</strong> HP_[Mã lớp học]_[Mã học viên]</p>
        <p class="mt-2 text-red-700"><strong>Hạn thanh toán:</strong> Trước ngày khai giảng lớp học.</p>
        <p class="mt-2 text-sm text-gray-700">Vui lòng liên hệ trung tâm để xác nhận sau khi chuyển khoản.</p>
    </div>
    @endif
</div>

@endsection