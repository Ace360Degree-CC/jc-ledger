<!DOCTYPE html>
<html>
<head>
    <title>JC |Admin</title>
    @include('commons.headerlinks')
</head>
<body>

@include('admin.commons.header')

    <div class="container mx-auto">
        <div class="bg-white p-4 rounded-md mx-auto" style="max-width:750px">
        <h2 class="text-2xl text-center mb-4">Edit Subadmin</h2>
    <form method="POST" action="{{ route('admin.updateSubadmin') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="mb-4" name="id" value="{{$subadmin->id}}"/>
        <input type="text" class="mb-4" name="name" placeholder="Name" value="{{$subadmin->name}}" required>
        <input type="text" class="mb-4" name="username" placeholder="Username" value="{{$subadmin->username}}" required>
        <input type="email" class="mb-4" name="email" placeholder="Email" value="{{$subadmin->email}}" required>
    
        <input type="password" class="mb-4" name="password" placeholder="Password" >

        <input type="file" class="mb-4" name="profile" />
        <div class="grid grid-cols-12 mb-4">
                <div>Status</div>
                <div><input type="checkbox" name="status" {{ $subadmin->status ? 'checked' : '' }} value="1"/></div>
        </div>
        

        
        <button type="submit" class="btn-theme">Update</button>
    </form>
    </div>
    </div>
    <!-- <a href="{{ route('subadmin.login') }}">Login</a> -->

    <button class="bg-gray-700 text-white rounded-md p-2 px-5 block mx-auto mt-5 cursor-pointer" onclick="history.back()">Back</button>
</body>
</html>
