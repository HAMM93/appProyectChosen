<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DisapprovedPhotoMail extends TrackMailable
{
    use Queueable, SerializesModels;

    protected int $id;

    protected string $name;

    protected string $email;

    protected string $token;

    protected $email_history_id;

    /**
     * Create a new message instance.
     *
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function __construct(int $id, string $name, string $email, string $token)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
        $this->subject = trans('email.email_disapproved_photo.subject');
        $this->email_history_id = $this->generateEmailHistory($email, $this->subject, 'emails.email-disapproved_photo');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-disapproved-photo')
            ->subject($this->subject)
            ->with([
                'name' => $this->name,
                'id' => $this->id,
                'token' => $this->token
            ])
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader(
                    'Custom-Message-ID', $this->email_history_id
                );
            });
    }
}
