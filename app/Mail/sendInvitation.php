<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $subject;
    public $otp;
    public $user_name;
    public function __construct($link,$subject,$referrer_name,$referee_name,$target_audience)
    {
        $this->link      = $link;
        $this->subject = $subject;
        $this->referrer_name = $referrer_name;
        $this->referee_name = $referee_name;
        $this->target_audience = $target_audience;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->markdown('frontend.mail.sendInvitation',[
            'link'              => $this->link,
            'referrer_name'     => $this->referrer_name,
            'referee_name'      => $this->referee_name,
            'target_audience'   => $this->target_audience,

        ]);
    }
}
