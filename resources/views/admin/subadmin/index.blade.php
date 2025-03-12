<!DOCTYPE html>
<html>
<head>
    <title>JC | Admin - All Subadmins</title>
    @include('commons.headerlinks')
</head>
<body>

    @include('admin.commons.header')
    

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- If you want a link to create a new subadmin -->

    <div class="container mx-auto">
        <div class="w-full bg-white p-4 rounded-md">
        <h2 class="text-center text-2xl">All Subadmins</h2>

        <a href="{{ route('admin.addSubadmin') }}" class="btn-theme">Create New Subadmin</a>
        <table  class="w-full mt-5 table-auto text-center" border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subadmins as $index=>$subadmin)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td><img src="{{ asset('storage/' . $subadmin['profile']) }}" alt="Profile Image" width="100"></td>
                    <td>{{ $subadmin['name'] }}</td>
                    <td>{{ $subadmin['username'] }}</td>
                    <td>{{ $subadmin['email'] }}</td>
                    <td>{{ ($subadmin['status'])?"Active":"In-Active" }}</td>
                    <td>
                        <!-- Edit link -->
                        <a href="{{ route('subadmin.edit', $subadmin['id']) }}"><button class="p-2 px-4 bg-gray-700 text-white rounded-md cursor-pointer">Edit</button></a>

                        <!-- Delete form -->
                        <form action="{{ route('admin.deleteSubadmin') }}" method="POST" style="display:inline;">
                            @csrf
                            <!-- Put the subadmin ID in a hidden field -->
                            <input type="hidden" name="id" value="{{ $subadmin['id'] }}">
                            <button type="submit" class="p-2 px-4 bg-red-700 text-white rounded-md cursor-pointer" onclick="return confirm('Are you sure you want to delete this subadmin?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No subadmins found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

        </div>   
    </div>   


    @include('commons.footer')

   
</body>
</html>
