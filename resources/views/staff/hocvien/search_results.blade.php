@if(count($dshocvien) > 0)
@foreach($dshocvien as $kh)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $kh->mahocvien }}</td>
    <td>{{ $kh->ten }}</td>
    <td>{{ $kh->user->email}}</td>
    <td>{{ $kh->sdt }}</td>
    <td>{{ $kh->diachi }}</td>
    <td>{{ $kh->ngaysinh}}</td>
    <td>{{ $kh->gioitinh }}</td>
    <td>{{ $kh->ngaydangki }}</td>
    <td>{{ $kh->trangthai }}</td>
    <td class="col-action">
        <!-- <a href="" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a> -->
        <a href="javascript:void(0);"
            class="btn btn-sm btn-warning btn-sua-hocvien"
            data-id="{{ $kh->id }}"
            data-ma="{{ $kh->mahocvien }}"
            data-ten="{{ $kh->ten }}"
            data-email="{{ $kh->user->email ?? '' }}"
            data-sdt="{{ $kh->sdt }}"
            data-diachi="{{ $kh->diachi }}"
            data-ngaysinh="{{ $kh->ngaysinh }}"
            data-gioitinh="{{ $kh->gioitinh }}"
            data-ngaydangki="{{ $kh->ngaydangki }}"
            data-trangthai="{{ $kh->trangthai }}">
            Sửa
        </a>

        <form action="" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
        </form>
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="12" class="text-center text-danger">
        Không tìm thấy kết quả nào phù hợp với từ khóa: <strong>{{ request('tu_khoa') }}</strong>
    </td>
</tr>
@endif

<script>
    function moPopup(popupId) {
        $("#" + popupId).css("display", "flex");
        setTimeout(function() {
            $("#" + popupId + " .sidebar-popup").addClass("open");
        }, 10);
    }

    // Hàm đóng popup
    function dongPopup(popupId) {
        $("#" + popupId + " .sidebar-popup").removeClass("open");
        setTimeout(function() {
            $("#" + popupId).css("display", "none");
        }, 300);
    }

    // Mở popup "Thêm mới"
    $(".btn-them-hocvien").on("click", function() {
        moPopup("addHocVienPopup");
        // Xóa dữ liệu cũ trong form thêm mới khi mở popup
        $("#addHocVienPopup form")[0].reset();
    });

    // Mở popup "Chỉnh sửa" và điền dữ liệu
    $(".btn-sua-hocvien").on("click", function() {
        let data = $(this).data(); // Lấy tất cả data-* attributes

        // Điền dữ liệu vào form chỉnh sửa
        $("#edit_hoc_vien_id").val(data.id);
        $("#edit_ma_hoc_vien").val(data.ma);
        $("#edit_ten_hoc_vien").val(data.ten);
        $("#edit_email_user").val(data.email);
        $("#edit_sdt_hoc_vien").val(data.sdt);
        $("#edit_diachi_hoc_vien").val(data.diachi);
        $("#edit_ngaysinh_hoc_vien").val(data.ngaysinh);
        $("#edit_gioitinh_hoc_vien").val(data.gioitinh);
        $("#edit_ngaydangki_hoc_vien").val(data.ngaydangki);
        $("#edit_trangthai_hoc_vien").val(data.trangthai);

        // Cập nhật action của form chỉnh sửa
        // Sử dụng hàm route() của Laravel để tạo URL chính xác
        $("#editHocVienForm").attr("action", `/staff/hocvien/update/${data.id}`); // Sửa lại URL

        moPopup("editHocVienPopup");
    });

    // Đóng popup khi click nút đóng (x) trên cả hai popup
    $(".popup-close").on("click", function() {
        let targetPopupId = $(this).data("target");
        dongPopup(targetPopupId);
    });

    // Đóng popup khi click ra ngoài popup
    $(".popup-overlay").on("click", function(event) {
        if ($(event.target).hasClass("popup-overlay")) {
            dongPopup(event.target.id);
        }
    });
</script>