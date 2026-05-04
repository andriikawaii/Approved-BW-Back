<?php

namespace App\Mail;

use App\Models\Lead;
use App\Services\IcsGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We got your request — BuiltWell CT',
            replyTo: ['info@builtwellct.com'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-confirmation',
            with: [
                'lead' => $this->lead,
            ],
        );
    }

    public function attachments(): array
    {
        $ics = IcsGenerator::forLead($this->lead);
        if (!$ics) {
            return [];
        }

        return [
            Attachment::fromData(fn () => $ics, 'builtwell-consultation.ics')
                ->withMime('text/calendar; charset=utf-8; method=REQUEST'),
        ];
    }
}
