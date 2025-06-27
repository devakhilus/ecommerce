<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verifyUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email for Mini Amazon')
            ->markdown('mail.verify', [
                'url' => $verifyUrl,
                'notifiable' => $notifiable,
            ]);
    }
}
