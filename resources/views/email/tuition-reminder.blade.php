@component('mail::message')
# Nhắc nhở học phí

Xin chào {{ $studentName }},

Chúng tôi xin thông báo rằng học phí của bạn cho lớp **{{ $className }}** vẫn còn thiếu.

**Số tiền còn lại cần thanh toán:** {{ $remainingAmount }} VNĐ

Bạn đã qua hạn thanh toán. Vui lòng hoàn thành thanh toán càng sớm càng tốt, hoặc liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi nào.
@if($paymentDueDate !== 'sớm nhất có thể')
<!-- > Ngày đến hạn thanh toán: {{ $paymentDueDate }} -->
@endif


Trân trọng,
Trung tâm anh ngữ
@endcomponent