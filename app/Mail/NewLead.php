<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLead extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function envelope(): Envelope
    {
        $subject = 'New Lead: ' . $this->lead->name;
        if ($this->lead->town) {
            $subject .= ' (' . $this->lead->town . ')';
        }

        return new Envelope(
            subject: $subject,
            replyTo: [$this->lead->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-lead',
            with: [
                'lead' => $this->lead,
            ],
        );
    }
}
