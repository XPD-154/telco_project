<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendMailV2 extends Mailable
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
        //return $this->view('index');

        /*
        return $this->subject($subjectMatter)
                    ->view('index');
        */

        return $this->view('crosstee', ['details' => $this->details])
            ->subject($this->subject);
    }
}
