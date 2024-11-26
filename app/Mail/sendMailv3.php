<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMailv3 extends Mailable 

{
    use Queueable, SerializesModels;

    public $details;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($details, $subject)
    {
        //
        $this->details = $details;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->view('crosstee', ['details' => $this->details])
                    ->subject($this->subject);
    }

}