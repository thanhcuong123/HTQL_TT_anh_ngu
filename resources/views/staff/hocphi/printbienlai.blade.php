<!DOCTYPE html>
<html>

<head>
    <title>Biên lai thu học phí</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Sử dụng DejaVu Sans cho hỗ trợ tiếng Việt trong PDF */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            background-color: #f0f0f0;
            /* Chỉ để xem trên web, không in */
        }

        .receipt-container {
            width: 210mm;
            /* Kích thước A4 ngang */
            height: 148mm;
            /* Kích thước A5 ngang (hoặc một nửa A4 dọc) */
            margin: 20px auto;
            padding: 20px 30px;
            border: 1px solid #000;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .header-left {
            text-align: left;
            font-size: 11px;
            line-height: 1.4;
        }

        .header-right {
            text-align: right;
            font-size: 11px;
            line-height: 1.4;
        }

        .header-right p {
            margin: 0;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: -10px;
            /* Điều chỉnh vị trí tiêu đề */
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            font-style: italic;
            margin-bottom: 20px;
        }

        .info-line {
            display: flex;
            align-items: baseline;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            flex-shrink: 0;
            margin-right: 5px;
        }

        .info-value {
            flex-grow: 1;
            border-bottom: 1px dotted #000;
            /* Dòng chấm chấm */
            padding-bottom: 2px;
        }

        .info-value.no-border {
            border-bottom: none;
        }

        .date-signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            text-align: center;
            width: 100%;
        }

        .date-signature-block {
            width: 40%;
            /* Chiếm 40% chiều rộng của phần footer */
            text-align: center;
        }

        .date-text {
            font-style: italic;
            margin-bottom: 10px;
            /* Khoảng trống cho chữ ký */
        }

        .signer-name {
            font-weight: bold;
        }

        .note-section {
            font-size: 10px;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .right-side-text {
            position: absolute;
            right: 10px;
            top: 100px;
            /* Điều chỉnh vị trí */
            font-size: 9px;
            transform: rotate(90deg);
            transform-origin: 100% 100%;
            white-space: nowrap;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-left">
                <p style="font-weight: bold; margin-bottom: 2px;">TRUNG TÂM ANH NGỮ RIVER</p>
                <p style="margin: 0;">Địa chỉ: Mậu Thân, Ninh Kiều, Cần Thơ</p>
                <p style="margin: 0;">Website: www.anhnguriver.com</p>
                <p style="margin: 0;">số điện thoại: 0702892014</p>
            </div>
            <div class="header-right">
                <p>Mẫu số: 01-05/BLP</p>
                <p>Ký hiệu: AA/2012P</p>
                <p>Số: <span class="info-value no-border">________________</span></p> <!-- Placeholder for receipt number -->
            </div>
        </div>

        <!-- Main Title -->
        <div class="title">BIÊN LAI THU HỌC PHÍ</div>
        <div class="subtitle">Liên 1: Lưu</div>

        <!-- Information Section -->
        <div class="info-section">
            <div class="info-line">
                <span class="info-label">Họ tên người nộp tiền:</span>
                <span class="info-value">{{ $phieuThu->hocvien->ten ?? '________________________________________________' }}</span>
            </div>
            <div class="info-line">
                <span class="info-label">Địa chỉ:</span>
                <span class="info-value">{{ $phieuThu->hocvien->diachi ?? '________________________________________________' }}</span>
            </div>
            <div class="info-line">
                <span class="info-label">Lý do thu:</span>
                <span class="info-value">Thu học phí lớp {{ $phieuThu->lophoc->tenlophoc ?? '________________________________________________' }}</span>
            </div>
            <div class="info-line">
                <span class="info-label">Số tiền:</span>
                <span class="info-value">{{ number_format($phieuThu->sotien ?? 0, 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="info-line">
                <span class="info-label">Hình thức thanh toán:</span>
                <span class="info-value">
                    @if(isset($phieuThu->phuongthuc))
                    {{ $phieuThu->phuongthuc== 'tien_mat' ? 'Tiền mặt' : 'Chuyển khoản' }}
                    @else
                    _____________
                    @endif
                </span>
            </div>
            <div class="info-line">
                <span class="info-label">Ngày thu:</span>
                <span class="info-value">{{ $phieuThu ->ngaythanhtoan ?? '________________________________________________' }}</span>
            </div>
            <div class="info-line">
                <span class="info-label">Ghi chú:</span>
                <span class="info-value">{{ $phieuThu->ghichu ?? '________________________________________________' }}</span>
            </div>
        </div>

        <!-- Date and Signature Section -->
        <div class="date-signature">
            <div class="date-signature-block">
                <p class="date-text">
                    Ngày {{ date('d', strtotime($phieuThu->ngaythanhtoan)) }}
                    tháng {{ date('m', strtotime($phieuThu->ngaythanhtoan)) }}
                    năm {{ date('Y', strtotime($phieuThu->ngaythanhtoan)) }}
                </p>
                <p class="signer-title" style="font-weight: bold; margin-bottom: 5px;">Người thu tiền</p>
                <p class="signer-name">(Ký và ghi rõ họ tên)</p>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="note-section">
            <!-- <p>* Ghi chú: Đề nghị Sinh viên giữ biên lai cẩn thận và xuất trình khi nhà trường yêu cầu.</p>
            <p>Phát hành theo công văn số 10765/CT-AC ngày 13 tháng 12 năm 2011 của Cục Thuế TP. HCM</p> -->
        </div>

        <!-- Right Side Vertical Text (from image) -->
        <!-- <div class="right-side-text">
            (Cấm sửa chữa, viết thêm, gạch xóa) * Mẫu in theo TT200/2014/TT-BTC * Hotline: 0918 001 788 * www.hoadonxuat.com * MST: 0310336281 * © Công ty TNHH BÌNH MINH PHÁT
        </div> -->
    </div>
</body>

</html>