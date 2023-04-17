<?php

namespace App\Mail;

use App\Models\LineMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LineRegisterInform extends Mailable
{
    use Queueable, SerializesModels;

    public LineMessage $data;

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
    public function build(): LineRegisterInform
    {
        return $this->view('emails.line_inform_email')
            ->subject('LINEチャンネルの登録が完了しました')
            ->from(config('app.mail_to'))
            ->with('data', $this->data);
    }
}
