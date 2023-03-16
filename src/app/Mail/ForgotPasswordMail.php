<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use App\Mail\TrackMailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends TrackMailable
{
    use Queueable, SerializesModels;

    protected string $name;

    protected string $email;

    protected string $token;

    protected $email_history_id;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function __construct(string $name, string $email, string $token)
    {
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
        $this->subject = trans('email.email_password.subject');
        $this->email_history_id = $this->generateEmailHistory($email, $this->subject, 'emails.email_password.body_reset_password');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-password')
            ->subject($this->subject)
            ->with([
                'name' => $this->name,
                'token' => $this->token
            ])
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader(
                    'Custom-Message-ID', $this->email_history_id
                );
            });
    }
}
