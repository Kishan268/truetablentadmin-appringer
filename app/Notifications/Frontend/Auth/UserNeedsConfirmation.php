<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Config\AppConfig;
use Twilio\Rest\Client;

/**
 * Class UserNeedsConfirmation.
 */
class UserNeedsConfirmation extends VerifyEmail
{
    use Queueable;

    /**
     * @var
     */
    protected $confirmation_code;
    protected $first_name;
    protected $password;

    /**
     * UserNeedsConfirmation constructor.
     *
     * @param $confirmation_code
     */
    public function __construct($confirmation_code, $first_name, $password = null, $is_company_user = null, $evaluator = null,$email_otp = null)
    {
        $this->confirmation_code    = $confirmation_code;
        $this->first_name           = $first_name;
        $this->password             = $password;
        $this->is_company_user      = $is_company_user;
        $this->evaluator            = $evaluator;
        $this->email_otp            = $email_otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    

    public function toMail($notifiable)
    {

        try {
            $this->confirmation_code = $this->verificationUrl($notifiable);
            if ($this->is_company_user) {
                return (new MailMessage())
                    ->bcc(AppConfig::getAdminEmail())
                    ->subject(app_name() . ': ' . notificationTemplates('company_register')->subject ? notificationTemplates('company_register')->subject : __('exceptions.frontend.auth.confirmation.confirm'))
                    ->markdown('frontend.mail.confirm', ['url' => $this->confirmation_code, 'name' => $this->first_name, 'password' => $this->password, 'is_company_user' => $this->is_company_user, 'evaluator' => $this->evaluator]);
            } else {
                return (new MailMessage())
                    ->subject(app_name() . ': ' . notificationTemplates('candidates_register')->subject ? notificationTemplates('candidates_register')->subject :__('exceptions.frontend.auth.confirmation.confirm'))
                    ->markdown('frontend.mail.confirm', ['url' => $this->confirmation_code, 'name' => $this->first_name, 'password' => $this->password, 'is_company_user' => $this->is_company_user,'evaluator' => $this->evaluator, 'email_otp' => $this->email_otp]);
            }
        } catch (Exception $e) {
                
        }
    }
    
    // public function toCustomSms($notifiable)
    // {
    //     try {
    //         $sid    = "ACce322368c2af852b619c10592cceabbd";
    //         $token  = "bfcce43956f29217a19cf94fc251f55b";
    //         $twilio = new Client($sid, $token);

    //         $message = $twilio->messages
    //             ->create(
    //                 "+918770510126", // to
    //                 array(
    //                     "from" => "+15416928884",
    //                     "body" => 'test'
    //                 )
    //             );
    //     } catch (Exception $e) {
            
    //     }
        
    // }
    
}
