<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản học viên</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="color: black; text-align: center; padding: 16px; font-size: 20px; font-weight: bold;">
                Trung Tâm Anh Ngữ River - Thông Tin Tài Khoản
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; color: #333;">
                <p>Xin chào <strong>{{ $hocvien->ten }}</strong>,</p>
                <p>Chào mừng bạn đã trở thành học viên tại <strong>Trung Tâm Anh Ngữ River</strong>.
                    Dưới đây là thông tin tài khoản của bạn:</p>

                <table cellpadding="8" cellspacing="0" style="width: 100%; border: 1px solid #ddd; border-collapse: collapse; margin-top: 10px;">
                    <tr style="background-color: #f9f9f9;">
                        <td style="border: 1px solid #ddd; font-weight: bold; width: 40%;">Họ và tên</td>
                        <td style="border: 1px solid #ddd;">{{ $hocvien->ten }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; font-weight: bold;">Email đăng nhập</td>
                        <td style="border: 1px solid #ddd;">{{ $user->email }}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="border: 1px solid #ddd; font-weight: bold;">Mật khẩu</td>
                        <td style="border: 1px solid #ddd;">{{ $password }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; font-weight: bold;">Ngày sinh</td>
                        <td style="border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($hocvien->ngaysinh)->format('d/m/Y') }}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="border: 1px solid #ddd; font-weight: bold;">Số điện thoại</td>
                        <td style="border: 1px solid #ddd;">{{ $hocvien->sdt }}</td>
                    </tr>
                </table>

                <p style="margin-top: 20px;">Bạn có thể đăng nhập tại:
                    <a href="{{ url('/login') }}" style="color: #4CAF50; font-weight: bold;">{{ url('/login') }}</a>
                </p>

                <p style="margin-top: 10px;">Vui lòng đổi mật khẩu sau khi đăng nhập để đảm bảo an toàn.</p>

                <p>Trân trọng,<br>
                    <strong>Trung Tâm Anh Ngữ River</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f4f6f8; text-align: center; padding: 12px; font-size: 12px; color: #777;">
                © {{ date('Y') }} Trung Tâm Anh Ngữ. Mọi quyền được bảo lưu.
            </td>
        </tr>
    </table>
</body>

</html>