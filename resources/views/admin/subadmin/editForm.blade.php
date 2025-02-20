<!DOCTYPE html>
<html>
<head>
    <title>JC |Admin</title>
</head>
<body>
    <h2>Edit Subadmin</h2>
    <form method="POST" action="{{ route('admin.updateSubadmin') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$subadmin->id}}"/>
        <input type="text" name="name" placeholder="Name" value="{{$subadmin->name}}" required>
        <input type="text" name="username" placeholder="Username" value="{{$subadmin->username}}" required>
        <input type="email" name="email" placeholder="Email" value="{{$subadmin->email}}" required>

        <input type="password" name="password" placeholder="Password" >

        <input type="file" name="profile" />
        <input type="checkbox" name="status" {{ $subadmin->status ? 'checked' : '' }} value="1"/>

        
        <button type="submit">Update</button>
    </form>
    <!-- <a href="{{ route('subadmin.login') }}">Login</a> -->
</body>
</html>
