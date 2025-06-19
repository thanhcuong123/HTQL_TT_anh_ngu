function moPopup(popupId) {
    $("#" + popupId).css("display", "flex");
    setTimeout(function () {
        $("#" + popupId + " .sidebar-popup").addClass("open");
    }, 10);
}

// Hàm đóng popup
function dongPopup(popupId) {
    $("#" + popupId + " .sidebar-popup").removeClass("open");
    setTimeout(function () {
        $("#" + popupId).css("display", "none");
    }, 300);
}

// Mở popup "Thêm mới"
$(".btn-them-hocvien").on("click", function () {
    moPopup("addHocVienPopup");
    // Xóa dữ liệu cũ trong form thêm mới khi mở popup
    $("#addHocVienPopup form")[0].reset();
});

// Mở popup "Chỉnh sửa" và điền dữ liệu
$(".btn-sua-hocvien").on("click", function () {
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
    $("#editHocVienForm").attr("action", `/admin/hocvien/update/${data.id}`); // Sửa lại URL

    moPopup("editHocVienPopup");
});

// Đóng popup khi click nút đóng (x) trên cả hai popup
$(".popup-close").on("click", function () {
    let targetPopupId = $(this).data("target");
    dongPopup(targetPopupId);
});

// Đóng popup khi click ra ngoài popup
$(".popup-overlay").on("click", function (event) {
    if ($(event.target).hasClass("popup-overlay")) {
        dongPopup(event.target.id);
    }
});
