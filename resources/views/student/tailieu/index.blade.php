@extends('student.student_index') {{-- Đảm bảo bạn đang extend layout chính của học viên --}}

@section('title-content')
<title>Tài Liệu Học Tập Của Tôi</title>
@endsection

@section('student-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .materials-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .materials-card h3 {
        color: #007bff;
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .materials-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .table-materials {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table-materials th,
    .table-materials td {
        border: 1px solid #dee2e6;
        padding: 12px 15px;
        text-align: left;
        vertical-align: middle;
    }

    .table-materials th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
    }

    .table-materials tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table-materials tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-download {
        background-color: #17a2b8;
        color: #fff;
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.9em;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-download:hover {
        background-color: #138496;
    }

    .no-materials-message {
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

    .no-materials-message i {
        font-size: 2em;
        margin-bottom: 15px;
        color: #007bff;
    }
</style>

<div class="materials-card">
    <h3><i class="fas fa-folder-open"></i> Tài Liệu Học Tập Của Tôi</h3>

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

    {{-- Danh sách tài liệu --}}
    <h4 class="mb-3 mt-4">Tài Liệu Liên Quan Đến Lớp Học Của Bạn</h4>
    @if($materials->isEmpty())
    <div class="no-materials-message">
        <i class="fas fa-box-open"></i>
        <p>Hiện chưa có tài liệu nào được chia sẻ cho các lớp học của bạn.</p>
        <p>Vui lòng kiểm tra lại sau hoặc liên hệ giáo viên để biết thêm thông tin.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-materials">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên tài liệu</th>
                    <th>Lớp liên quan</th>
                    <th>Giáo viên tải lên</th>
                    <th>Loại file</th>
                    <th>Kích thước</th>
                    <th>Ngày tải lên</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $index => $material)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $material->tentailieu }}</td>
                    <td>{{ $material->lophoc->tenlophoc ?? 'N/A' }}</td>
                    <td>{{ $material->giaovien->ten ?? 'N/A' }}</td>
                    <td>{{ strtoupper(pathinfo($material->duongdanfile, PATHINFO_EXTENSION)) }}</td>
                    <td>{{ round($material->kichthuocfile / 1024, 2) }} KB</td>
                    <td>{{ \Carbon\Carbon::parse($material->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $material->mota ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('student.materials.download', $material->id) }}" class="btn-download">
                            <i class="fas fa-download"></i> Tải xuống
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection