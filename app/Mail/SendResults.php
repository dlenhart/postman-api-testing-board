<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendResults extends Mailable
{
    use Queueable, SerializesModels;
    public $result;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.results')
            ->subject("API Testing Results ( {$this->result->application->name} )");
    }
}
