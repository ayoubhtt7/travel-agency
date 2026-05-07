<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Flight Ticket</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f4f6f8;
            padding: 20px;
            color: #222;
        }

        .ticket {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #ddd;
            overflow: hidden;
        }

        .header {
            background: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        .content {
            padding: 25px;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .row {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .ticket-box {
            border: 1px dashed #999;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            background: #fafafa;
        }

        .ticket-code {
            font-size: 20px;
            font-weight: bold;
            color: #0d6efd;
            margin-top: 8px;
        }

        .total {
            margin-top: 20px;
            font-size: 22px;
            font-weight: bold;
            text-align: right;
            color: #0d6efd;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }

        .flight-route {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>

<body>

<div class="ticket">

    <div class="header">
        <h1>✈ Flight Ticket</h1>
        <p>Booking #{{ $booking->id }}</p>
    </div>

    <div class="content">

        <div class="flight-route">
            {{ $booking->flight->departureAirport->code ?? 'N/A' }}
            →
            {{ $booking->flight->arrivalAirport->code ?? 'N/A' }}
        </div>

        <div class="section-title">
            Flight Information
        </div>

        <div class="row">
            <span class="label">Airline:</span>
            Air Algerie
        </div>

        <div class="row">
            <span class="label">Departure City:</span>
            {{ $booking->flight->departureAirport->city ?? 'N/A' }}
        </div>

        <div class="row">
            <span class="label">Arrival City:</span>
            {{ $booking->flight->arrivalAirport->city ?? 'N/A' }}
        </div>

        <div class="row">
            <span class="label">Departure Date:</span>
            {{ \Carbon\Carbon::parse($booking->flight->departure_at)->format('d M Y - H:i') }}
        </div>

        <div class="row">
            <span class="label">Class:</span>
            {{ ucfirst($booking->class) }}
        </div>

        <div class="row">
            <span class="label">Trip Type:</span>
            {{ ucfirst($booking->type) }}
        </div>

        <div class="row">
            <span class="label">Payment Method:</span>
            {{ ucfirst($booking->payment_method) }}
        </div>

        <div class="section-title" style="margin-top:25px;">
            Passenger Tickets
        </div>

        @foreach($booking->tickets as $ticket)

            <div class="ticket-box">

                <div class="row">
                    <span class="label">Passenger:</span>
                    {{ $ticket->passenger?->first_name ?? 'N/A' }}
                    {{ $ticket->passenger?->last_name ?? '' }}
                </div>

                <div class="row">
                    <span class="label">Passport:</span>
                    {{ $ticket->passenger?->passport_number ?? 'N/A' }}
                </div>

                <div class="row">
                    <span class="label">Nationality:</span>
                    {{ $ticket->passenger?->nationality ?? 'N/A' }}
                </div>

                <div class="ticket-code">
                    🎟 {{ $ticket->ticket_code }}
                </div>

            </div>

        @endforeach

        <div class="total">
            Total Paid: {{ number_format($booking->total_price, 2) }} DA
        </div>

        <div class="footer">
            Thank you for choosing our airline service ✈ <br>
            Please arrive at the airport 2 hours before departure.
        </div>

    </div>

</div>

</body>
</html>