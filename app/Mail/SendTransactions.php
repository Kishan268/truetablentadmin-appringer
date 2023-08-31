<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportTransactions;

class SendTransactions extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('frontend.mail.transactions')
        ->attach(
            Excel::download(
                new ExportTransactions($this->transactions), 
                'transactions.xlsx'
            )->getFile(), ['as' => 'transactions.xlsx']
        );
    }
}
