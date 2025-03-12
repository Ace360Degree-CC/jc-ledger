<!DOCTYPE html>
<html>
<head>
    <title>JC |Admin</title>
    @include('commons.headerlinks')
</head>
<body>

@include('subadmin.commons.header')

<div class="container mx-auto">
    <div class="bg-white p-4 rounded-md" style="max-width:750px">
    <h2 class="text-center text-2xl mb-5">Create New CSP Agent</h2>
    <form method="POST" action="{{ route('subadmin.updateCSP') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="mb-4" name="id" value="{{$csp->id}}"/>
        <input type="text" name="name" class="mb-4" placeholder="Name" value="{{$csp->name}}" required>
      
        <input type="email" name="email" class="mb-4" placeholder="Email" value="{{$csp->email}}" required>
    
        <input type="password" name="password" class="mb-4" placeholder="Password" >

        <input type="file" class="mb-4" name="profile" />
        <div class="grid grid-cols-12 mb-4">
            <div>Status</div>
            <div><input type="checkbox" name="status" {{ $csp->status ? 'checked' : '' }} value="1"/></div>
        </div>
        

        
        <button type="submit" class="btn-theme">Update</button>
    </form>

</div>
</div>

<button class="bg-gray-700 text-white rounded-md p-2 px-5 block mx-auto mt-5 cursor-pointer" onclick="history.back()">Back</button>
    <!-- <a href="{{ route('subadmin.login') }}">Login</a> -->
</body>
</html>
