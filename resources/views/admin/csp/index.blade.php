<!DOCTYPE html>
<html>
<head>
    <title>JC | Admin - All CSP Agents</title>
    @include('commons.headerlinks')
</head>
<body>

    @include('admin.commons.header')
    

   


    <div class="container mx-auto">
        <div class="w-full bg-white p-4 rounded-md">
        <h2 class="text-center text-2xl">All CSPs</h2>
        @if (session('success'))
        <div class="bg-green-100 text-green-600 p-3 text-center">
            {{ session('success') }}
        </div>
        @endif

        <a href="{{ route('admin.addCSP') }}" class="btn-theme mb-4">Create New CSP</a>

        <table class="w-full mt-5 table-auto text-center" border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($csps as $index=>$csp)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td><img src="{{ asset('storage/' . $csp['profile']) }}" alt="Profile Image" width="100"></td>
                    <td>{{ $csp['name'] }}</td>
                    <td>{{ $csp['email'] }}</td>
                    <td>{{ ($csp['status'])?'Active':'In-Active' }}</td>
                    <td>
                        <!-- Edit link -->
                        <a href="{{ route('admin.editCSP', $csp['id']) }}" ><button class="p-2 px-4 bg-gray-700 text-white rounded-md cursor-pointer">Edit</button></a>

                        <!-- Delete form -->
                        <form action="{{ route('admin.deleteCSP') }}" method="POST" style="display:inline;">
                            @csrf
                            <!-- Put the subadmin ID in a hidden field -->
                            <input type="hidden" name="id" value="{{ $csp['id'] }}">
                            <button type="submit" class="p-2 px-4 bg-red-700 text-white rounded-md cursor-pointer" onclick="return confirm('Are you sure you want to delete this subadmin?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No csps found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    </div>
    </div>

    @include('commons.footer')
    
</body>
</html>
