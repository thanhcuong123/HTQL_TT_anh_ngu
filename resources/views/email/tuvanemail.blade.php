<x-mail::message>
    # Xác nhận Đăng ký Tư vấn Khóa học

    Thân gửi {{ $hoten }},

    Cảm ơn bạn đã đăng ký tư vấn khóa học tại **Anh ngữ River**! Chúng tôi đã nhận được yêu cầu của bạn với thông tin chi tiết như sau:

    **Thông tin liên hệ của bạn:**
    - Họ và tên: {{ $hoten }}
    - Email: {{ $email }}
    - Số điện thoại: {{ $sdt }}
    - Độ tuổi: {{ $dotuoi ?? 'Không cung cấp' }}

    **Thông tin tư vấn:**
    - Khóa học quan tâm: {{ $khoahoc }}
    - Lời nhắn/Câu hỏi:
    {{ $loinhan ?? 'Không có' }}

    Chúng tôi sẽ xem xét yêu cầu của bạn và liên hệ lại trong thời gian sớm nhất để hỗ trợ bạn tốt nhất.
    Mọi thắc mắc xin liên hệ thanhcuongstudent@gmail.com hoặc sdt 0702892014.

    Trân trọng,
    Trung tâm Anh ngữ River
</x-mail::message>