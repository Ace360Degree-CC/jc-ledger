<!DOCTYPE html>
<html>
<head>
    <title>Subadmin | Dashboard</title>
</head>
<body>
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <form action="{{ route('subadmin.logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
