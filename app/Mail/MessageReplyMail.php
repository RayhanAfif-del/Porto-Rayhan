<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly ContactMessage $contactMessage,
        public readonly string $replyBody,
        public readonly string $adminName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: '.$this->contactMessage->subject,
            replyTo: [
                new Address(
                    config('mail.from.address'),
                    $this->adminName,
                ),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.message-reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
