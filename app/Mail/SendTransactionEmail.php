<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTransactionEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $total;
    protected $gst;
    protected $grand_total;
    protected $email;
    protected $user;
    protected $currency;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment_id,$total,$gst,$grand_total,$user,$currency)
    {
        $this->payment_id = $payment_id;
        $this->total = $total;
        $this->gst = $gst;
        $this->grand_total = $grand_total;
        $this->user = $user;
        $this->currency = $currency;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email)
            ->markdown('frontend.mail.transaction_detail', ['total' => $this->total, 'gst' => $this->gst, 'grand_total' => $this->grand_total, 'user' => $this->user, 'payment_id' => $this->payment_id, 'currency' => $this->currency])
            ->subject('Payment Successfull of '.$this->currency.' '.number_format($this->grand_total,2).' | '.env('APP_NAME'))
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo(config('mail.from.address'), config('mail.from.name'));
    }
}
