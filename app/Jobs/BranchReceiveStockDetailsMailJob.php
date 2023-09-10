<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\BranchReceiveStockDetailsMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BranchReceiveStockDetailsMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $transfer;
    public $to;
    public $mail_note;
    public function __construct($to, $mail_note, $transfer)
    {
        $this->to = $to;
        $this->mail_note = $mail_note;
        $this->transfer = $transfer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new BranchReceiveStockDetailsMail($this->mail_note, $this->transfer);
        Mail::to($this->to)->send($email);
    }
}
