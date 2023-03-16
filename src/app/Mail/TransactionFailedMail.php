<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionFailedMail extends TrackMailable
{
    use Queueable, SerializesModels;

    protected string $name;

    protected string $email;

    protected Donation $data;

    protected $email_history_id;

    //TODO:: (JOEL 28/10) ENVIAR LINK PARA ALTERAR PAGAMENTO / COLOCAR FINAL DO CARTÃO NO EMAIL EX: MASTERCARD-0668

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param Donation $data
     *
     */
    public function __construct(string $name, string $email, Donation $data)
    {
        $this->name = $name;
        $this->email = $email;
        $this->data = $data;
        $this->subject = trans('email.email_transaction_failed.subject');
        $this->email_history_id = $this->generateEmailHistory($email, $this->subject, 'emails.email_transaction_failed.body');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-transaction-failed')
            ->subject($this->subject)
            ->with([
                'name' => $this->name,
                'data' => $this->data,
            ])
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader(
                    'Custom-Message-ID', $this->email_history_id
                );
            });
    }
}
