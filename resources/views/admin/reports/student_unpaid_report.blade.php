@extends('index') {{-- Đảm bảo bạn extend layout chính của admin --}}

@section('title-content')
<title>Báo Cáo Học Viên Chưa Đóng Học Phí</title>
@endsection

@section('main-content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .report-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .report-card h3 {
        color: #007bff;
        /* Màu đỏ cho báo cáo nợ */
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .report-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .filter-section {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .form-group {
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group label {
        font-weight: bold;
        color: #333;
        white-space: nowrap;
    }

    .form-control {
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1rem;
        box-sizing: border-box;
        max-width: 200px;
        height: 40px;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .table-students {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table-students th,
    .table-students td {
        border: 1px solid #dee2e6;
        padding: 12px 15px;
        text-align: left;
        vertical-align: middle;
    }

    .table-students th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
    }

    .table-students tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table-students tbody tr:hover {
        background-color: #e9ecef;
    }

    .no-data-message {
        background-color: #f0f8ff;
        color: #0056b3;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        font-size: 1.2em;
        border: 1px solid #cce5ff;
        margin-top: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .no-data-message i {
        font-size: 2em;
        margin-bottom: 15px;
        color: #007bff;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: bold;
        font-size: 0.85em;
    }

    .status-unpaid {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-partial {
        background-color: #fff3cd;
        color: #856404;
    }
</style>

<div class="report-card">
    <h3><i class="fas fa-exclamation-triangle"></i> Báo Cáo Học Viên Chưa Đóng Học Phí</h3>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Phần lọc theo lớp học --}}
    <div class="filter-section">
        <form action="{{ route('reports.unpaid-students') }}" method="GET" class="d-flex align-items-center gap-3 w-100">
            <div class="form-group flex-grow-1">
                <label for="class_id">Chọn lớp học:</label>
                <select class="form-control" id="class_id" name="class_id" style="height:40px">
                    <option value="">-- Tất cả lớp học --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ (old('class_id', $selectedClass->id ?? '') == $class->id) ? 'selected' : '' }}>
                        {{ $class->malophoc }} - {{ $class->tenlophoc }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Lọc
                </button>
                @if($unpaidStudents->count() > 0)
                <a href="{{ route('reports.unpaid_students.export', ['class_id' => $selectedClass->id ?? null]) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
                @endif

            </div>

        </form>
    </div>

    {{-- Danh sách học viên chưa đóng học phí --}}
    @if($unpaidStudents->isEmpty())
    <div class="no-data-message">
        <i class="fas fa-check-circle"></i>
        <p>Không có học viên nào chưa đóng học phí trong {{ $selectedClass ? 'lớp ' . $selectedClass->tenlophoc : 'tất cả các lớp' }}.</p>
    </div>
    @else
    <h4 class="mb-3 mt-4">Danh sách học viên chưa đóng học phí {{ $selectedClass ? 'trong lớp: ' . $selectedClass->malophoc . ' - ' . $selectedClass->tenlophoc : '' }}</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-students">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã học viên</th>
                    <th>Họ và tên</th>
                    <th>Mã lớp</th>
                    <th>Tên lớp</th>
                    <th>Tổng học phí</th>
                    <th>Đã thanh toán</th>
                    <th>Còn lại</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unpaidStudents as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student['mahocvien'] ?? 'N/A' }}</td>
                    <td>{{ $student['tenhocvien'] ?? 'N/A' }}</td>
                    <td>{{ $student['malophoc'] ?? 'N/A' }}</td>
                    <td>{{ $student['tenlophoc'] ?? 'N/A' }}</td>
                    <td>{{ number_format($student['total_tuition'] ?? 0, 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($student['amount_paid'] ?? 0, 0, ',', '.') }} VNĐ</td>
                    <td class="text-danger font-weight-bold">{{ number_format($student['remaining_balance'] ?? 0, 0, ',', '.') }} VNĐ</td>
                    <td>
                        <span class="status-badge status-{{ $student['payment_status'] == 'Thanh toán một phần' ? 'partial' : 'unpaid' }}">
                            {{ $student['payment_status'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection