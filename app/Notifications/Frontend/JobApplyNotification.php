<?php

namespace App\Notifications\Frontend;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;
// use Illuminate\Notifications\Messages\SlackMessage;
use App\Notifications\BaseNotification;

class JobApplyNotification extends BaseNotification
{
    // use Queueable;
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
