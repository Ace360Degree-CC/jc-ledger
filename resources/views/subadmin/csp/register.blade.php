<!DOCTYPE html>
<html>
<head>
    <title>JC | Register</title>
</head>
<body>
    <h2>Create New CSP Agent</h2>
    <form method="POST" action="{{ route('subadmin.registerCSP') }}">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <!-- <a href="{{ route('login') }}">Login</a> -->
</body>
</html>
