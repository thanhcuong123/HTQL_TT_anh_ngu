//thempopup

$(".btn-them-khoahoc").on("click", function () {
    moPopup();
});
function moPopup() {
    $("#popup-them-khoahoc").addClass("open");
}

function dongPopup() {
    $("#popup-them-khoahoc").removeClass("open");
}

var quill = new Quill("#editor-container", {
    theme: "snow",
});
$("form").on("submit", function () {
    var mota = quill.root.innerHTML;
    $("#mota").val(mota);
});

var quillSua = new Quill("#editor-container-sua", {
    theme: "snow",
});

$(document).ready(function () {
    // Mở popup sửa khi click nút sửa
    $(document).on("click", ".btn-sua-khoahoc", function () {
        var ma = $(this).data("id");
        var ten = $(this).data("ten");
        var mota = $(this).data("mota");
        var thoiluong = $(this).data("thoiluong");
        var sobuoi = $(this).data("sobuoi");

        // Điền dữ liệu vào form sửa
        $("#ma_hienthi_sua").val(ma);
        $("#ma_sua").val(ma);
        $("#ten_sua").val(ten);
        quillSua.root.innerHTML = mota;
        $("#thoiluong_sua").val(thoiluong);
        $("#sobuoi_sua").val(sobuoi);

        // Thay đổi action form sửa với route update (có thể theo chuẩn RESTful)
        $("#form-sua-khoahoc").attr("action", "/admin/khoahoc/update/" + ma);

        // Mở popup sửa
        $("#popup-sua-khoahoc").addClass("open");
    });

    // Khi submit form sửa, cập nhật mô tả trước submit
    $("#form-sua-khoahoc").on("submit", function () {
        var motaSua = quillSua.root.innerHTML;
        $("#mota_sua").val(motaSua);
    });
});

// Hàm đóng popup sửa
function dongPopupSua() {
    $("#popup-sua-khoahoc").removeClass("open");
}
// trinh do
//thempopup
