@extends('layouts.admin')

@section('content')

<h1>Admin Dashboard</h1>

<div class="stats">

<div class="stat-box">
<h3>{{ $users }}</h3>
<p>Users</p>
</div>

<div class="stat-box">
<h3>{{ $trips }}</h3>
<p>Trips</p>
</div>

<div class="stat-box">
<h3>{{ $bookings }}</h3>
<p>Bookings</p>
</div>

<div class="stat-box">
<h3>{{ $destinations }}</h3>
<p>Destinations</p>
</div>

</div>

@endsection