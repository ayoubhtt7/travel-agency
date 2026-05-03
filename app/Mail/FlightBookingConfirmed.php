<?php

namespace App\Mail;

use App\Models\FlightBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlightBookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FlightBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Flight Booked — ' . $this->booking->flight->flight_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.flight.confirmed',
        );
    }
}
