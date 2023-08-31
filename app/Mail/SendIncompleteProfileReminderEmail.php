<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendIncompleteProfileReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $mailData;
    public function __construct($data)
    {
        $this->mailData = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('frontend.mail.incomplete_profile')
                ->subject(notificationTemplates('incomplete_profile')->subject ? notificationTemplates('incomplete_profile')->subject :'Profile Incomplete Reminder')
                ->with($this->mailData);
    }
}
