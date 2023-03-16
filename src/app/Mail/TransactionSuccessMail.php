<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionSuccessMail extends TrackMailable
{
    use Queueable, SerializesModels;

    protected string $name;

    protected string $email;

    protected $email_history_id;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     *
     * @return void
     */
    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = trans('email.email_transaction.subject');
        $this->email_history_id = $this->generateEmailHistory($email, $this->subject, 'emails.email_transaction.body');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-transaction')
            ->subject($this->subject)
            ->with([
                'name' => $this->name,
            ])
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader(
                    'Custom-Message-ID', $this->email_history_id
                );
            });
    }
}
