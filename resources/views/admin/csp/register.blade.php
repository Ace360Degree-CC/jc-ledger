<!DOCTYPE html>
<html>
<head>
    <title>JC | Register</title>
    @include('commons.headerlinks')
</head>
<body>

    @include('admin.commons.header')
    <div class="container mx-auto">
        <div class="bg-white p-4 rounded-md mx-auto" style="max-width:750px" >
        <h2 class="text-center text-2xl mb-4">Create New CSP Agent</h2>
        <form method="POST" action="{{ route('admin.registerCSP') }}">
            @csrf
            <input type="text" class="mb-4" name="name" placeholder="Name" required>
            <input type="email" class="mb-4" name="email" placeholder="Email" required>
            <input type="password" class="mb-4" name="password" placeholder="Password" required>
            <input type="password" class="mb-4" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit" class="btn-theme mb-4">Register</button>
        </form>
        <!-- <a href="{{ route('login') }}" class="underline" >Login</a> -->
    </div>
    </div>
    
    @include('commons.footer')
</body>
</html>
