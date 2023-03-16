<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\TrackMailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TokenRevelation;
use App\Models\Donor;
use App\Models\DonationChildren;


class RevelationMail extends TrackMailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $email;
    protected $token;
    protected $email_history_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Donor $donor, DonationChildren $child)
    {
        $tokenModel = TokenRevelation::createToken($donor->id, $child->id);

        $this->name = $donor->name;
        $this->email = $donor->email;
        $this->token = $tokenModel->token;
        $this->subject = trans('email.email_revelation.subject');
        $this->email_history_id = $this->generateEmailHistory($donor->email, $this->subject, 'emails.email-revelation');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-revelation')
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
