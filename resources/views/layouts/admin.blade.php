<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>

<style>

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f4f6f9;
}

.sidebar{
    width:230px;
    height:100vh;
    background:#1e293b;
    color:white;
    position:fixed;
    padding:20px;
}

.sidebar h2{
    margin-bottom:30px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:10px;
    margin-bottom:10px;
    border-radius:6px;
}

.sidebar a:hover{
    background:#334155;
}

.main{
    margin-left:250px;
    padding:30px;
}

.topbar{
    background:white;
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse:collapse;
}

table th, table td{
    padding:12px;
    border-bottom:1px solid #ddd;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:6px 10px;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

</style>

</head>

<body>

<div class="sidebar">

<h2>Admin Panel</h2>

<a href="/admin/dashboard">Dashboard</a>
<a href="/admin/users">Users</a>
<a href="/admin/trips">Trips</a>
<a href="/admin/destinations">Destinations</a>
<a href="/admin/bookings">Bookings</a>

</div>


<div class="main">

<div class="topbar">
Welcome Admin
</div>

<div class="card">

@yield('content')

</div>

</div>

</body>
</html>