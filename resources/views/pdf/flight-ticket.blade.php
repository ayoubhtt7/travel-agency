<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Flight Ticket</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            padding:40px;
        }

        .ticket{
            border:2px solid #333;
            border-radius:10px;
            padding:30px;
        }

        h1{
            margin-bottom:20px;
        }

        .row{
            margin-bottom:10px;
        }

        .label{
            font-weight:bold;
        }
    </style>
</head>
<body>

<div class="ticket">

    <h1>✈ Flight Ticket</h1>

    <div class="row">
        <span class="label">Passenger:</span>
        {{ $booking->passenger_name }}
    </div>

    <div class="row">
        <span class="label">Flight:</span>
        {{ $booking->flight->airline }}
    </div>

    <div class="row">
        <span class="label">From:</span>
        {{ $booking->flight->departure_city }}
    </div>

    <div class="row">
        <span class="label">To:</span>
        {{ $booking->flight->arrival_city }}
    </div>

    <div class="row">
        <span class="label">Departure:</span>
        {{ $booking->flight->departure_time }}
    </div>

    <div class="row">
        <span class="label">Seat Class:</span>
        {{ ucfirst($booking->seat_class) }}
    </div>

    <div class="row">
        <span class="label">Passengers:</span>
        {{ $booking->passengers }}
    </div>

    <div class="row">
        <span class="label">Payment:</span>
        {{ $booking->payment_method }}
    </div>

    <div class="row">
        <span class="label">Total:</span>
        {{ number_format($booking->total_price, 2) }} DA
    </div>

</div>

</body>
</html>