<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;

class TrackMailable extends Mailable
{
    /**
     * @param string $to_address
     * @param string $subject
     * @param string $body
     * @return string
     */
    protected function generateEmailHistory($to_address, $subject, $body = ''): string
    {
        $email_history = \App\Models\EmailHistory::create([
            'from_address'=> env('MAIL_FROM_ADDRESS'),
            'to_address' => $to_address,
            'subject' => $subject,
            'body' => $body,
            'message_id' => (string) Str::uuid(),
        ]);

        return (string) $email_history->message_id;
    }
}
