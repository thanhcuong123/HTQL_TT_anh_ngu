<div id="popup-trinhdo" class="popup">


    <div class="popup-content">
        <h3>Thêm trình độ</h3>
        <form action="{{ route('trinhdo.store') }}" method="POST">
            @csrf
            <label for="ma">Mã trình độ</label>
            <input type="text" name="ma_hienthi" id="ma_hienthi" value="{{ $newMa }}" class="form-control" disabled>
            <input type="hidden" name="ma" value="{{ $newMa }}">
            <label for="ten">Tên trình độ </label>
            <input type="text" name="ten" id="ten" required placeholder="Ví dụ A1,B1....">
            <label for="kynang_id">Kỹ năng liên quan</label>
            <select name="kynang_id" id="kynang_id" class="form-control" required>
                <option value="">-- Chọn kỹ năng --</option>
                @foreach ($dsKyNang as $kynang)
                <option value="{{ $kynang->id }}">{{ $kynang->ten }}</option>
                @endforeach
            </select>
            <label for="namhoc_id">Năm học</label>
            <select name="namhoc_id" id="namhoc_id" class="form-control" required>
                <option value="">-- Chọn năm học --</option>
                @foreach ($dsNamHoc as $namhoc)
                <option value="{{ $namhoc->id }}">{{ $namhoc->nam }}</option>
                @endforeach
            </select>

            <label for="hoc_phi">Học phí</label>
            <input type="text" name="hoc_phi" id="hoc_phi" required placeholder="Nhập học phí" class="form-control" oninput="formatCurrency(this)">
            <div id="hoc_phi_display" style="margin-top: 5px; font-weight: bold;"></div>
            <label for="mota">Mô tả trình độ</label>
            <div id="editor-container" style="height: 200px;"></div>
            <input type="hidden" name="mota" id="mota">

            <div class="popup-buttons">
                <button type="submit" class="btn btn-success">Lưu</button>
                <button type="button" class="btn btn-secondary" onclick="close()">Hủy</button>
            </div>




        </form>
        <script>
            function formatCurrency(input) {
                // Xóa ký tự không phải số
                let value = input.value.replace(/[^0-9]/g, '');

                // Định dạng số với dấu phẩy
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                // Cập nhật giá trị của trường nhập liệu
                input.value = value;
                // Thêm ký hiệu VNĐ
                const currencyDisplay = value ? value + ' VNĐ' : '';
                document.getElementById('hoc_phi_display').innerText = currencyDisplay;
            }
        </script>
    </div>
</div>