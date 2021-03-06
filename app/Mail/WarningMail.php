<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $delta;
    public $platform;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($platform, $delta)
    {
        $this->delta = $delta;
        $this->platform = $platform;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.warningMail');
    }
}
