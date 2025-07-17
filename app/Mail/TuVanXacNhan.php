<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class TuVanXacNhan extends Mailable  implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận Đăng ký Tư vấn Khóa học - Anh ngữ River',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'email.tuvanemail', // Đường dẫn đến Blade view cho email
            with: [
                'hoten'    => $this->data['hoten'],
                'email'     => $this->data['email'],
                'sdt'       => $this->data['sdt'],
                'dotuoi'   => $this->data['dotuoi'],
                'khoahoc'  => $this->data['khoahoc'], // Tên khóa học
                'loinhan'  => $this->data['loinhan'],
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
