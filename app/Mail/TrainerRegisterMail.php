<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainerRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

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
    public function build(): TrainerRegisterMail
    {
        return $this->view('emails.trainer_register_email')
            ->subject('ログインIDのお知らせ')
            ->from('test@abihc.com','トレーナー管理システム')
            ->with('data', $this->data);
    }
}
