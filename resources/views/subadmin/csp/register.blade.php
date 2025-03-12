<!DOCTYPE html>
<html>
<head>
    <title>JC | Register</title>
    @include('commons.headerlinks')
</head>
<body>

@include('subadmin.commons.header')

<div class="container mx-auto">
    <div class="bg-white p-4 rounded-md" style="max-width:750px">
    <h2 class="text-center text-2xl mb-5">Create New CSP Agent</h2>
    <form method="POST" action="{{ route('subadmin.registerCSP') }}">
        @csrf
        <input type="text" class="mb-4" name="name" placeholder="Name" required>
        <input type="email" class="mb-4" name="email" placeholder="Email" required>
        <input type="password" class="mb-4" name="password" placeholder="Password" required>
        <input type="password" class="mb-4" name="password_confirmation" placeholder="Confirm Password" required>
        <button type="submit" class="btn-theme">Register</button>
    </form>
    <!-- <a href="{{ route('login') }}">Login</a> -->
</div>
</div>

<button class="bg-gray-700 text-white rounded-md p-2 px-5 block mx-auto mt-5 cursor-pointer" onclick="history.back()">Back</button>
</body>
</html>
