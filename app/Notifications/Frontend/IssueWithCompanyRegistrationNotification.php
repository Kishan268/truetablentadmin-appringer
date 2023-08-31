<?php

namespace App\Notifications\Frontend;

use App\Notifications\BaseNotification;

class IssueWithCompanyRegistrationNotification extends BaseNotification
{

   public function __construct($subject, $maildata, $template,$via=[],$ccMail=[])
    {
        $this->via = ['mail'];
        $this->mailTemplate    = $template;
        $this->mailSubject    = $subject;
        $this->mailData    = $maildata;
        $this->ccMail    = $ccMail;
        $this->smsBody = 'Hi Kishwan How Are You?...';

    }
}
