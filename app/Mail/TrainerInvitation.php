<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainerInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): TrainerInvitation
    {
        return $this->view('emails.trainer_invitation_template')
            ->subject('トレーナーの招待が届いています')
            ->from('test@abihc.com', 'トレーナー管理システム')
            ->with('data', $this->data);
    }
}
