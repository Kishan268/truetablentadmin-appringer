<?php
namespace App\Services;

use Illuminate\Notifications\Notification;

class CustomSmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toCustomSms($notifiable);

        // Implement your custom SMS service logic here
        // Use $data['content'] for the message content
        // Use $data['to'] for the recipient's phone number
    }
}
?>