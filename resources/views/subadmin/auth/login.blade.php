<!DOCTYPE html>
<html>
<head>
    <title>Subadmin Login</title>
    @include('commons.headerlinks')
</head>
<body>

  <div class="container mx-auto px-2">
        <div class="login-flex-box">
            <div class="login-box">   
                <h2 class="text-center text-3xl font-semibold mb-5">Sub-Admin Login</h2>
                <form method="POST" action="{{ route('subadmin.login') }}">
                    @csrf
                    <input type="email" name="email" class="mb-4" placeholder="Email" required>
                    <div class="relative mb-4">
                        <input type="password" id="passwordInput" class="" name="password" placeholder="Password" required>
                        <button type="button" class="absolute cursor-pointer px-3 rounded-lg right-0 top-0 bottom-0 m-auto" onclick="togglePassword()"><i class="fa-solid fa-eye" id="eyePatch"></i></button>
                    </div>
                    <button type="submit" class="btn-theme mb-4 w-full">Login</button>
                </form>
                
            </div> 
        </div>
    </div>



    <script>
        let psInput = document.querySelector('#passwordInput');
        let eyeIcons = document.querySelector('#eyePatch');
        function togglePassword(){
            let inputType = psInput.getAttribute('type');
            eyeIcons.classList.toggle('fa-eye');
            eyeIcons.classList.toggle('fa-eye-slash');
            inputType=='password'?psInput.setAttribute('type','text'): psInput.setAttribute('type','password');

        }
    </script>


</body>
</html>
