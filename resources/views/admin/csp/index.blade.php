<!DOCTYPE html>
<html>
<head>
    <title>JC | Admin - All CSP Agents</title>
</head>
<body>
    <h2>All CSPs</h2>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- If you want a link to create a new subadmin -->
    <p>
        <a href="{{ route('admin.addCSP') }}">Create New CSP</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
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
                        <a href="{{ route('admin.editCSP', $csp['id']) }}">Edit</a>

                        <!-- Delete form -->
                        <form action="{{ route('admin.deleteCSP') }}" method="POST" style="display:inline;">
                            @csrf
                            <!-- Put the subadmin ID in a hidden field -->
                            <input type="hidden" name="id" value="{{ $csp['id'] }}">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this subadmin?')">
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
</body>
</html>
