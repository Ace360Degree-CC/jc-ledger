<!DOCTYPE html>
<html>
<head>
    <title>JC |Admin Register</title>
</head>
<body>
    <h2>Admin Register</h2>
    <form method="POST" action="{{ route('admin.register') }}">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <a href="{{ route('admin.login') }}">Login</a>
</body>
</html>
