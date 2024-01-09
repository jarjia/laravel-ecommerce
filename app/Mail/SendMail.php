<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Text;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $sender;
    public string $title;
    public string $text;

    public function __construct($sender, $title, $text)
    {
        $this->sender = $sender;
        $this->title = $title;
        $this->text = $text;
    }

    public function build()
    {
        return $this->from(address: $this->sender, name: $this->sender)
            ->subject(subject: $this->title)
            ->view('mail.contact', [
                'text' => $this->text
            ]);
    }
}
