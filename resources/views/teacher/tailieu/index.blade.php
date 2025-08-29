@extends('teacher.teacher_index') {{-- Đảm bảo bạn extend layout chính của giáo viên --}}

@section('title-content')
<title>Quản Lý Tài Liệu Học Tập</title>
@endsection

@section('teacher-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .material-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .material-card h3 {
        color: #007bff;
        margin-bottom: 25px;
        font-size: 2em;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .material-card h3 i {
        margin-right: 15px;
        font-size: 0.9em;
    }

    .form-upload-section {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
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
    }

    .btn-primary:hover {
        background-color: #0056b3;
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

    .btn-download,
    .btn-delete {
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

    .btn-download {
        background-color: #17a2b8;
        color: #fff;
    }

    .btn-download:hover {
        background-color: #138496;
    }

    .btn-delete {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-delete:hover {
        background-color: #c82333;
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

<div class="material-card">
    <h3><i class="fas fa-folder-open"></i> Quản Lý Tài Liệu Học Tập</h3>

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

    {{-- Form tải lên tài liệu --}}
    <h4 class="mb-3">Tải Lên Tài Liệu Mới</h4>
    <div class="form-upload-section">
        <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tentailieu">Tên tài liệu:</label>
                        <input type="text" class="form-control" id="tentailieu" name="tentailieu" value="{{ old('tentaileu') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="file_tai_lieu">Chọn file:</label>
                        <input type="file" class="form-control" id="file_tai_lieu" name="file_tai_lieu" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lophoc_id">Chọn lớp học :</label>
                        <select class="form-control" id="lophoc_id" name="lophoc_id" style="height:45px" required>
                            <option value="">-- Chọn lớp học --</option>
                            @foreach($teacherClasses as $class)
                            <option value="{{ $class->id }}" {{ old('lophoc_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->malophoc }} - {{ $class->tenlophoc }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mota">Mô tả:</label> {{-- ĐÃ SỬA: từ 'mo_ta' thành 'mota' --}}
                        <textarea class="form-control" id="mota" name="mota" rows="3">{{ old('mota') }}</textarea> {{-- ĐÃ SỬA: từ 'mo_ta' thành 'mota' --}}
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i> Tải lên
                </button>
            </div>
        </form>
    </div>

    {{-- Danh sách tài liệu đã tải lên --}}
    <h4 class="mb-3 mt-4">Các Tài Liệu Đã Tải Lên</h4>
    @if($materials->isEmpty())
    <div class="no-materials-message">
        <i class="fas fa-box-open"></i>
        <p>Bạn chưa tải lên tài liệu nào.</p>
        <p>Hãy sử dụng form trên để bắt đầu chia sẻ tài liệu với học viên!</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-materials">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên tài liệu</th>
                    <th>Lớp liên quan</th>
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
                    <td>{{ $material->tentailieu }}</td> {{-- ĐÃ SỬA: từ 'ten_tai_lieu' thành 'tentaileu' --}}
                    <td>{{ $material->lopHoc->tenlophoc ?? 'Chung' }}</td>
                    <td>{{ strtoupper(pathinfo($material->duongdanfile, PATHINFO_EXTENSION)) }}</td> {{-- ĐÃ SỬA: từ 'duong_dan_file' thành 'duongdanfile' --}}
                    <td>{{ round($material->kichthuocfile / 1024, 2) }} KB</td> {{-- ĐÃ SỬA: từ 'kich_thuoc_file' thành 'kichthuocfile' --}}
                    <td>{{ \Carbon\Carbon::parse($material->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $material->mota ?? 'N/A' }}</td> {{-- ĐÃ SỬA: từ 'mo_ta' thành 'mota' --}}
                    <td>
                        <a href="{{ $material->duongdanfile }}" class="btn-download" download="{{ $material->tentailieu }}.{{ pathinfo($material->duongdanfile, PATHINFO_EXTENSION) }}"> {{-- ĐÃ SỬA lỗi PATHINFO_ATINFO_EXTENSION --}}
                            <i class="fas fa-download"></i> Tải xuống
                        </a>
                        <form action="{{ route('teacher.materials.destroy', $material->id) }}" method="POST" style="display:inline-block; margin-left: 5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa tài liệu này không?');">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection