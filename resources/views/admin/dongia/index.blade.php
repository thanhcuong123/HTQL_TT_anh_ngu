@extends('index')

@section('title-content')
<title>Quản lý Đơn giá học phí</title>
@endsection

@section('main-content')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
        @endif
        <!-- @if (session('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('errors') }}
        </div>
        @endif -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <h3 class="card-title">Danh sách đơn giá học phí</h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary btn-them-dongia">+ Thêm đơn giá mới</button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Trình độ</th>
                        <th>Năm </th>
                        <th>Học phí</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhsachdongia as $dongia)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dongia->trinhdo->ten }}</td>
                        <td>{{ $dongia->namhoc->nam }}</td>
                        <td>{{ number_format($dongia->hocphi, 0, ',', '.') }} đ</td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-sua-dongia"
                                data-id="{{ $dongia->id }}"
                                data-trinhdo_id="{{ $dongia->trinhdo_id }}"
                                data-namhoc_id="{{ $dongia->namhoc_id }}"
                                data-hocphi="{{ $dongia->hocphi }}">
                                Sửa
                            </a>

                            <form action="{{ route('dongia.destroy',$dongia->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa đơn giá này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <!-- Modal thêm đơn giá -->
    <div class="modal fade" id="addDonGiaModal" tabindex="-1" aria-labelledby="addDonGiaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm đơn giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('dongia.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="trinhdo_id" class="form-label">Trình độ</label>
                            <select name="trinhdo_id" class="form-select" required style="width: 100%;">
                                <option value="">-- Chọn Trình độ --</option>
                                @foreach($trinhdos as $trinhdo)
                                <option value="{{ $trinhdo->id }}">{{ $trinhdo->ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="namhoc_id" class="form-label">Năm học</label>

                            <!-- <select name="namhoc_id" class="form-select" required style="width: 100%;">
                                <option value="">-- Chọn Năm --</option>
                                @foreach($namhocs as $namhoc)
                                <option value="{{ $namhoc->id }}">{{ $namhoc->nam }}</option>
                                @endforeach
                            </select> -->
                            <select name="namhoc_id" id="namhoc_id" class="form-select" required style="width: 100%;">
                                @foreach($namhocs as $namhoc)
                                <option value=" {{ $namhoc->id }}"
                                    @if($namhoc->nam != date('Y')) disabled @endif>
                                    {{ $namhoc->nam }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="mb-3">
                            <label for="hocphi" class="form-label">Học phí</label>
                            <!-- <input type="number" name="hocphi" class="form-control" required> -->
                            <input type="text" name="hocphi" id="add_hocphi" class="form-control" required>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal sửa đơn giá -->
    <div class="modal fade" id="editDonGiaModal" tabindex="-1" aria-labelledby="editDonGiaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa đơn giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit-dongia" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="trinhdo_id" class="form-label">Trình độ</label>
                            <select name="trinhdo_id" id="edit_trinhdo_id" class="form-select" required style="width:100%">
                                @foreach($trinhdos as $trinhdo)
                                <option value="{{ $trinhdo->id }}">{{ $trinhdo->ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="namhoc_id" class="form-label">Năm học</label>
                            <!-- <select name="namhoc_id" id="edit_namhoc_id" class="form-select" required style="width: 100%;">
                                @foreach($namhocs as $namhoc)
                                <option value="{{ $namhoc->id }}">{{ $namhoc->nam }}</option>
                                @endforeach
                            </select> -->
                            <select name="namhoc_id" id="edit_namhoc_id" class="form-select" required style="width:100%">
                                @foreach($namhocs as $namhoc)
                                <option value="{{ $namhoc->id }}"
                                    @if($namhoc->nam != date('Y')) disabled @endif>
                                    {{ $namhoc->nam }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="mb-3">
                            <label for="hocphi" class="form-label">Học phí</label>
                            <!-- <input type="number" name="hocphi" id="edit_hocphi" class="form-control" required> -->
                            <input type="text" name="hocphi" id="edit_hocphi" class="form-control" required>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatCurrency(value) {
            return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function unformatCurrency(value) {
            return value.replace(/\./g, '');
        }

        // THÊM ĐƠN GIÁ
        const addForm = document.querySelector('#addDonGiaModal form');
        const addHocphiInput = document.getElementById('add_hocphi');

        if (addHocphiInput) {
            addHocphiInput.addEventListener('input', function() {
                this.value = formatCurrency(this.value);
            });

            if (addForm) {
                addForm.addEventListener('submit', function() {
                    addHocphiInput.value = unformatCurrency(addHocphiInput.value);
                });
            }
        }

        // SỬA ĐƠN GIÁ
        const editForm = document.getElementById('form-edit-dongia');
        const editHocphiInput = document.getElementById('edit_hocphi');
        const editModal = document.getElementById('editDonGiaModal');

        if (editHocphiInput) {
            editHocphiInput.addEventListener('input', function() {
                this.value = formatCurrency(this.value);
            });
        }

        document.querySelectorAll('.btn-sua-dongia').forEach(button => {
            button.addEventListener('click', function() {
                if (!editForm) return;

                const id = this.dataset.id;
                editForm.action = `/admin/dongia/update/${id}`;

                document.getElementById('edit_trinhdo_id').value = this.dataset.trinhdo_id;
                document.getElementById('edit_namhoc_id').value = this.dataset.namhoc_id;
                document.getElementById('edit_hocphi').value = formatCurrency(this.dataset.hocphi);

                editForm.addEventListener('submit', function handleEditSubmit() {
                    editHocphiInput.value = unformatCurrency(editHocphiInput.value);
                    editForm.removeEventListener('submit', handleEditSubmit);
                });

                const modal = new bootstrap.Modal(editModal);
                modal.show();
            });
        });

        // Nút Thêm
        const btnThem = document.querySelector('.btn-them-dongia');
        const addModal = document.getElementById('addDonGiaModal');
        if (btnThem && addModal) {
            btnThem.addEventListener('click', function() {
                const modal = new bootstrap.Modal(addModal);
                modal.show();
            });
        }
    });
</script>

@endsection