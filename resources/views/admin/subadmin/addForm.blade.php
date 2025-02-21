<!DOCTYPE html>
<html>
<head>
    <title>JC | Admin</title>
</head>
<body>
    <h2>Create Subadmin</h2>
    <form method="POST" action="{{ route('admin.addSubadmin') }}">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="file" name="profile"/>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <input type="checkbox" name="status" value="1"/>
        <button type="submit">Create</button>
    </form>
    <!-- <a href="{{ route('subadmin.login') }}">Login</a> -->
</body>
</html>
