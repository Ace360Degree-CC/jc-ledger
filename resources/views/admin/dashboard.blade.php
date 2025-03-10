<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    @include('commons.headerlinks')
</head>
<body>
    @include('admin.commons.header')
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    @include('commons.footer')

</body>
</html>
