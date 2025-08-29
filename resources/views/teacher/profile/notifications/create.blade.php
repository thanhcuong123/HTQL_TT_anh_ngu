@extends('teacher.teacher_index') {{-- Đảm bảo bạn đang extend layout chính của giáo viên --}}

@section('title-content')
<title>Gửi Thông Báo Lớp Học</title>
@endsection

@section('teacher-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .notification-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .notification-card h3 {
        color: #007bff;
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .notification-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
        font-size: 1rem;
        color: #495057;
        background-color: #f8f9fa;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }

    .btn-submit {
        background-color: #007bff;
        color: #fff;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<div class="notification-card">
    <h3><i class="fas fa-bullhorn"></i> Gửi Thông Báo Lớp Học</h3>

    @if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('teacher.notifications.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="lophoc_id" class="form-label">Chọn lớp học để gửi thông báo:</label>
            <select id="lophoc_id" name="lophoc_id" style="height:50px"
                class="form-control @error('lophoc_id') is-invalid @enderror" required>
                <option value="">-- Chọn lớp học của bạn --</option>
                @forelse($assignedClasses as $lophoc)
                <option value="{{ $lophoc->id }}" {{ old('lophoc_id') == $lophoc->id ? 'selected' : '' }}>
                    {{ $lophoc->malophoc }} - {{ $lophoc->tenlophoc }}
                </option>
                @empty
                <option value="" disabled>Bạn chưa được phân công lớp học nào.</option>
                @endforelse
            </select>
            @error('lophoc_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="tieude" class="form-label">Tiêu đề thông báo:</label>
            <input type="text" id="tieude" name="tieude"
                class="form-control @error('tieude') is-invalid @enderror"
                value="{{ old('tieude') }}" required>
            @error('tieude')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="noidung" class="form-label">Nội dung thông báo:</label>
            <textarea id="noidung" name="noidung" rows="7"
                class="form-control @error('noidung') is-invalid @enderror"
                required>{{ old('noidung') }}</textarea>
            @error('noidung')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-left mt-6">
            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane mr-2"></i> Gửi Thông Báo
            </button>
        </div>
    </form>
</div>

@endsection