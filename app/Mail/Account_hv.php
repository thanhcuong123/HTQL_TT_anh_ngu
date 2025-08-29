<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Account_hv extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $hocvien;
    public $user;
    public $password;
    public function __construct($hocvien, $user, $password)
    {
        $this->hocvien = $hocvien;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Thông tin tài khoản học viên')
            ->view('email.account_hv')
            ->with([
                'hocvien' => $this->hocvien,
                'user' => $this->user,
                'password' => $this->password,
            ]);
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
