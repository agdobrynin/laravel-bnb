<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class BookingMade extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private readonly Booking $booking)
    {
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Booking Made. Leave your feedback',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $mailMsg = (new MailMessage)
            ->greeting(sprintf('Hello %s!', $this->booking->personAddress->first_name))
            ->line(
                sprintf(
                    'Your booking "%s" from %s to %s.',
                    $this->booking->bookable->title,
                    $this->booking->start,
                    $this->booking->end,
                )
            )
            ->action(
                'Leave a review with a rating',
                env('APP_URL').'/review/'.$this->booking->review_key
            );

        return (new Content(
            htmlString: $mailMsg->render()
        ))->with('booking', $this->booking);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
