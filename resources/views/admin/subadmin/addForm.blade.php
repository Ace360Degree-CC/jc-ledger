<!DOCTYPE html>
<html>
<head>
    <title>JC | Admin</title>
    @include('commons.headerlinks')
</head>
<body>

@include('admin.commons.header')

    <div class="container mx-auto">
        <div class="bg-white p-4 rounded-md mx-auto" style="max-width:750px">
        <h2 class="mb-4 text-center text-2xl">Create Subadmin</h2>
        <form method="POST" action="{{ route('admin.addSubadmin') }}">
            @csrf
            <input type="text" class="mb-4" name="name" placeholder="Name" required>
            <input type="file" class="mb-4" name="profile"/>
            <input type="text" class="mb-4" name="username" placeholder="Username" required>
            <input type="email" class="mb-4" name="email" placeholder="Email" required>
            <input type="password" class="mb-4" name="password" placeholder="Password" required>
            <input type="password" class="mb-4" name="password_confirmation" placeholder="Confirm Password" required>
            <div class="grid grid-cols-12 mb-4">
                <div>Status</div>
                <div><input type="checkbox" class="mt-2" name="status" value="1"/></div>
            </div>
            
            <button type="submit" class="btn-theme">Create</button>
        </form>

        <!-- <a href="{{ route('subadmin.login') }}">Login</a> -->
    </div> 
    </div> 

    <button class="bg-gray-700 text-white rounded-md p-2 px-5 block mx-auto mt-5 cursor-pointer" onclick="history.back()">Back</button>

    @include('commons.footer')
</body>
</html>
