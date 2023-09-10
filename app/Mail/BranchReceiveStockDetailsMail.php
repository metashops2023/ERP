<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BranchReceiveStockDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $transfer;
    public $mail_note;

    public function __construct($mail_note, $transfer)
    {
        $this->transfer = $transfer;
        $this->mail_note = $mail_note;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $transfer = $this->transfer;
        $mail_note = $this->mail_note;
        return $this->view('mail.branch_stock_receive_mail', compact('mail_note', 'transfer'));
    }
}
