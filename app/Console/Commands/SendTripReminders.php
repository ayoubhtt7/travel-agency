<?php

namespace App\Console\Commands;

use App\Mail\BookingReminder;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTripReminders extends Command
{
    protected $signature   = 'bookings:send-reminders';
    protected $description = 'Send reminder emails for trips starting in 2 days';

    public function handle(): void
    {
        $targetDate = now()->addDays(2)->toDateString();

        $bookings = Booking::with(['user', 'trip.destination'])
            ->where('status', 'confirmed')
            ->whereHas('trip', fn($q) => $q->whereDate('start_date', $targetDate))
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No reminders to send today.');
            return;
        }

        foreach ($bookings as $booking) {
            Mail::to($booking->user->email)
                ->send(new BookingReminder($booking));

            $this->info("Reminder sent → {$booking->user->email} (Booking #{$booking->id})");
        }

        $this->info("✅ {$bookings->count()} reminder(s) sent.");
    }
}
