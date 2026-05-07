<?php

namespace App\Mail;

use App\Models\FlightBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(FlightBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this
            ->subject('✈ Your Flight Ticket')
            ->view('emails.flight-ticket');
    }
}