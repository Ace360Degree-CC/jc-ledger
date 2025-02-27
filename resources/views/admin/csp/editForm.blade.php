<!DOCTYPE html>
<html>
<head>
    <title>JC |Admin</title>
</head>
<body>
    <h2>Edit CSP Agent</h2>
    <form method="POST" action="{{ route('admin.updateCSP') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$csp->id}}"/>
        <input type="text" name="name" placeholder="Name" value="{{$csp->name}}" required>
      
        <input type="email" name="email" placeholder="Email" value="{{$csp->email}}" required>
    
        <input type="password" name="password" placeholder="Password" >

        <input type="file" name="profile" />
        <input type="checkbox" name="status" {{ $csp->status ? 'checked' : '' }} value="1"/>

        
        <button type="submit">Update</button>
    </form>
    <!-- <a href="{{ route('subadmin.login') }}">Login</a> -->
</body>
</html>
