<?php

namespace App\Notifications\Frontend;

use App\Notifications\BaseNotification;

class OtpForgotPasswordNotification extends BaseNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
     public function __construct($subject, $maildata, $template,$via=[])
    {
        $this->via = ['mail'];
        $this->mailTemplate    = $template;
        $this->mailSubject    = $subject;
        $this->mailData    = $maildata;
        $this->smsBody = 'Hi Kishwan How Are You?...';

    }
}
