<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendJobApply extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $subject;
    public $link;
    public $recruiter_name;
    public function __construct($subject, $link, $recruiter_name)
    {
        $this->subject      = $subject;
        $this->link          = $link;
        $this->recruiter_name    = $recruiter_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->markdown('frontend.mail.send_job_apply');
    }
}
