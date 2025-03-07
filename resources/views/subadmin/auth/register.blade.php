<!DOCTYPE html>
<html>
<head>
    <title>JC |Subadmin Register</title>
    @include('commons.headerlinks')
</head>
<body>


    <div class="container mx-auto px-2">
            <div class="login-flex-box">
                <div class="login-box">   
                    <h2 class="text-center text-4xl font-semibold mb-5">Register</h2>
                    <form method="POST" action="{{ route('subadmin.register') }}">
                        @csrf
                        <input type="text" name="name" class="mb-4" placeholder="Name" required>
                        <input type="email" name="email" class="mb-4" placeholder="Email" required>
                        <input type="password" name="password" class="mb-4" placeholder="Password" required>
                        <input type="password" name="password_confirmation" class="mb-4" placeholder="Confirm Password" required>
                        <button type="submit" class="btn-theme w-full mb-3">Register</button>
                    </form>
                    <p class="text-center">Haven't registered yet? <a href="{{ route('subadmin.login') }}" class="text-theme underline hover:opacity-75">Login</a></p>
                </div> 
            </div>
        </div>
    </body>
</body>
</html>
