<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $user, public $expires, public $token)
    {
    }

    public function build()
    {
        $imagePath = public_path('/assets/logo.png');

        return $this->from(address: 'yourEcommerce@gmail.com', name: 'Your Ecommerce')
            ->subject(subject: 'Email Verification')
            ->view('mail.verify')
            ->attach($imagePath, [
                'as' => 'logo.png',
                'mime' => 'logo/png',
            ]);
    }
}
