<div id="popup-sua-trinhdo" class="popup">
    <div class="popup-close" onclick="dongPopupSua()">&times;</div>

    <div class="popup-content">
        <h3>Sửa trình độ</h3>
        <form id="form-sua-trinhdo" method="POST" action="{{ route('trinhdo.update', 'trinhdo_id_placeholder') }}">
            @csrf
            @method('PUT') <!-- hoặc PATCH -->
            <label for="ma_hienthi_sua">Mã trình độ</label>
            <input type="text" name="ma_hienthi_sua" id="ma_hienthi_sua" class="form-control" disabled>
            <input type="hidden" name="ma_sua" id="ma_sua">

            <label for="ten_sua">Tên trình độ</label>
            <input type="text" name="ten_sua" id="ten_sua" required placeholder="Ví dụ Tiếng anh giao tiếp" class="form-control">

            <label for="kynang_id_sua">Kỹ năng liên quan</label>
            <label for="kynang_id_sua">Kỹ năng liên quan</label>
            <select name="kynang_id_sua" id="kynang_id_sua" class="form-control" required>
                <option value="">-- Chọn kỹ năng --</option>
                @foreach ($dsKyNang as $kynang)
                <option value="{{ $kynang->id }}"
                    @if(isset($trinhdo) && $trinhdo->kynang_id == $kynang->id) selected @endif>
                    {{ $kynang->ten }}
                </option>
                @endforeach
            </select>

            <label for="namhoc_id_sua">Năm học</label>
            <select name="namhoc_id_sua" id="namhoc_id_sua" class="form-control" required>
                <option value="">-- Chọn năm học --</option>
                @foreach ($dsNamHoc as $namhoc)
                <option value="{{ $namhoc->id }}"
                    @if(isset($trinhdo->dongia) && $trinhdo->dongia->namhoc_id == $namhoc->id) selected @endif>
                    {{ $namhoc->nam }}
                </option>
                @endforeach
            </select>

            <label for="hoc_phi_sua">Học phí</label>
            <input type="text" name="hoc_phi_sua" id="hoc_phi_sua" required placeholder="Nhập học phí" class="form-control" oninput="formatCurrency(this)">
            <div id="hoc_phi_display_sua" style="margin-top: 5px; font-weight: bold;"></div>

            <label for="mota_sua">Mô tả trình độ</label>
            <div id="editor-container-sua" style="height: 200px;"></div>
            <input type="hidden" name="mota_sua" id="mota_sua">

            <div class="popup-buttons">
                <button type="submit" class="btn btn-success">Lưu</button>
                <button type="button" class="btn btn-secondary" onclick="dongPopupSua()">Hủy</button>
            </div>
        </form>
    </div>
</div>
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
        document.getElementById('hoc_phi_display_sua').innerText = currencyDisplay;
    }

    function openPopupSua(trinhdo) {
        // Điền dữ liệu vào các trường
        document.getElementById('ma_hienthi_sua').value = trinhdo.ma;
        document.getElementById('ma_sua').value = trinhdo.ma;
        document.getElementById('ten_sua').value = trinhdo.ten;
        document.getElementById('kynang_id_sua').value = trinhdo.kynang_id; // Đảm bảo rằng bạn có giá trị này
        document.getElementById('namhoc_id_sua').value = trinhdo.namhoc_id; // Nếu bạn đã định dạng trước đó, hãy đảm bảo định dạng lại
        document.getElementById('hoc_phi_sua').value = trinhdo.hoc_phi ? trinhdo.hoc_phi.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''; // Định dạng học phí
        document.getElementById('mota_sua').value = trinhdo.mota; // Nếu bạn sử dụng editor, hãy cập nhật nội dung editor

        // Mở popup
        document.getElementById('popup-sua-trinhdo').style.display = 'block';
    }

    function dongPopupSua() {
        document.getElementById('popup-sua-trinhdo').style.display = 'none';
    }
</script>