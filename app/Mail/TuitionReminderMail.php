<?php

namespace App\Mail;

use App\Models\HocVien; // Import model HocVien
use App\Models\LopHoc; // Import model LopHoc
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TuitionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $class;
    public $remainingAmount;
    public $paymentDueDate; // Thêm biến này nếu bạn có ngày hẹn đóng

    /**
     * Create a new message instance.
     */
    public function __construct(HocVien $student, LopHoc $class, $remainingAmount, $paymentDueDate = null)
    {
        $this->student = $student;
        $this->class = $class;
        $this->remainingAmount = $remainingAmount;
        $this->paymentDueDate = $paymentDueDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nhắc nhở học phí từ ' . config('app.name'), // Tiêu đề email
        );
    }

    /**
     * Get the message content definition.
     */
    // app/Mail/TuitionReminderMail.php
    public function content(): Content
    {
        return new Content(
            markdown: 'email.tuition-reminder',
            with: [
                'studentName' => optional($this->student)->ten, // <<< Bọc bằng optional()
                'className' => optional($this->class)->tenlophoc, // <<< Bọc bằng optional()
                'remainingAmount' => number_format($this->remainingAmount, 0, ',', '.'),
                'paymentDueDate' => optional($this->paymentDueDate)->format('d/m/Y') ?? 'sớm nhất có thể',

                // 'paymentDueDate' => $this->paymentDueDate ? $this->paymentDueDate->format('d/m/Y') : 'sớm nhất có thể',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
