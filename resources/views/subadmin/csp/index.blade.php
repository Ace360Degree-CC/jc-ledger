<!DOCTYPE html>
<html>
<head>
    <title>JC | Subadmin - All CSP Agents</title>
    @include('commons.headerlinks')
</head>
<body>

@include('subadmin.commons.header')


<div class="container mx-auto">
    <div class="bg-white rounded-md p-4 ">

    <h2 class="text-center text-2xl mb-4">All CSPs</h2>

    @if (session('success'))
    <div class="bg-green-100 text-green-500 text-center p-3 rounded-md">
        {{ session('success') }}
    </div>
    @endif

    <!-- If you want a link to create a new subadmin -->
    <p>
        <a href="{{ route('subadmin.addCSP') }}" class="btn-theme">Create New CSP</a>
    </p>

    <div class="theme-table mt-5 text-center">
    <table class="datatable">
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
                    <td>
                    <img 
        src="{{ asset('storage/' . ($csp['profile'] ?? 'csp/profile/noprofile.webp')) }}" 
        alt="Profile Image" 
        style="width:auto;height:80px;margin:auto;display:block">
                    </td>

                    <td>{{ $csp['name'] }}</td>
                    <td>{{ $csp['email'] }}</td>
                    <td>{{ ($csp['status'])?'Active':'In-Active' }}</td>
                    <td>
                        <!-- Edit link -->
                        <a href="{{ route('subadmin.editCSP', $csp['id']) }}"><button class="bg-gray-700 text-white p-2 px-4 rounded-md">Edit</button></a>

                        <!-- Delete form -->
                        <form action="{{ route('subadmin.deleteCSP') }}" method="POST" style="display:inline;">
                            @csrf
                            <!-- Put the subadmin ID in a hidden field -->
                            <input type="hidden" name="id" value="{{ $csp['id'] }}">
                            <button type="submit" class="bg-red-700 text-white p-2 px-4 rounded-md" onclick="return confirm('Are you sure you want to delete this subadmin?')">
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
</div>


    @include('commons.footer')

</body>
</html>
