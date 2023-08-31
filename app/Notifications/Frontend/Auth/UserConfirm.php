<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;


/**
 * Class UserNeedsConfirmation.
 */
class UserConfirm extends VerifyEmail
{
    use Queueable;

    /**
     * @var
     */
    protected $email;
    protected $first_name;
    protected $password;

    /**
     * UserNeedsConfirmation constructor.
     *
     * @param $email
     */
    public function __construct($email, $first_name, $password,$company_admin)
    {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->password = $password;
        $this->company_admin = $company_admin;
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
        $this->confirmation_code = $this->verificationUrl($notifiable);
        
        return (new MailMessage())
            ->subject(app_name().': '. notificationTemplates('user_created')->subject ? notificationTemplates('user_created')->subject : __('exceptions.frontend.auth.confirmation.created_success'))
            ->markdown('frontend.mail.user_created', ['url' => env('FRONTEND_URL','truetalent.io').'/login','email' => $this->email, 'name' => $this->first_name, 'password' => $this->password, 'company_admin' => $this->company_admin]);
    }
}
