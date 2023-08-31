<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Config\AppConfig;

class OfflineTTCash extends Mailable{
    use Queueable, SerializesModels;
    protected $company;
    protected $amount;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company, $amount, $user){
        $this->company = $company;
        $this->amount = $amount;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->to(AppConfig::getAdminEmail())
            ->markdown('frontend.offlineTTCashRequest', ['company' => $this->company, 'amount' => $this->amount, 'user' => $this->user])
            ->subject('Request for buying TT-Cash | '.env('APP_NAME'))
            // ->with(['data' => $this->data])
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo(config('mail.from.address'), config('mail.from.name'));
    }
}
