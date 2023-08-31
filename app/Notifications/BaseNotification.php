<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;
use Illuminate\Notifications\Messages\SlackMessage;

class BaseNotification extends Notification
{
   
   /**
     * Get the channels the notification should be sent through.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    protected $via;
    protected $mailTemplate;
    protected $mailSubject;
    protected $mailData;
    protected $smsBody;
    protected $ccMail;

    public function __construct($via)
    {
        $this->via = ['mail','slack','custom_sms','whatsapp'];
        
    }

    public function via($notifiable)
    {
        return $this->via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
         $mail = (new MailMessage)
                     ->subject($this->mailSubject)
                     ->markdown($this->mailTemplate, $this->mailData);
        if ((is_object($this->ccMail) || is_array($this->ccMail)) && count($this->ccMail) > 0) {
            $mail->cc($this->ccMail);
        }
         
        return $mail;
    }

    public function toSlack($notifiable)
    {
        return $notifiable;
        // return $this->buildSlackMessage();
    }

    public function toArray($notifiable)
    {
        return $notifiable;
        // return $this->buildDatabaseMessage();
    }
    public function toCustomSms($notifiable)
    {
        try {
            $sid    = "ACce322368c2af852b619c10592cceabbd";
            $token  = "bfcce43956f29217a19cf94fc251f55b";
            $twilio = new Client($sid, $token);

            $message = $twilio->messages
                ->create(
                    "+918770510126", // to
                    array(
                        "from" => "+15416928884",
                        "body" => $this->smsBody
                    )
                );
        } catch (Exception $e) {
            
        }
        // return $this->buildSmsMessage();
    }

    public function toWhatsApp($notifiable)
    {
        return $notifiable;
        // return $this->buildWhatsappMessage();
    }

    /**
     * Build the mail message for the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    // abstract protected function buildMailMessage($notifiable);
    // abstract protected function buildSlackMessage($notifiable);
    // abstract protected function buildDatabaseMessage($notifiable);
    // abstract protected function buildSmsMessage($notifiable);
    // abstract protected function buildWhatsappMessage($notifiable);
   
}
