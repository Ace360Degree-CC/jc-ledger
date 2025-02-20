<!DOCTYPE html>
<html>
<head>
    <title>JC | Admin - All Subadmins</title>
</head>
<body>
    <h2>All Subadmins</h2>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- If you want a link to create a new subadmin -->
    <p>
        <a href="{{ route('admin.addSubadmin') }}">Create New Subadmin</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>{{ $subadmin['name'] }}</td>
                    <td>{{ $subadmin['username'] }}</td>
                    <td>{{ $subadmin['email'] }}</td>
                    <td>{{ ($subadmin['status'])?"Active":"In-Active" }}</td>
                    <td>
                        <!-- Edit link -->
                        <a href="{{ route('subadmin.edit', $subadmin['id']) }}">Edit</a>

                        <!-- Delete form -->
                        <form action="{{ route('admin.deleteSubadmin') }}" method="POST" style="display:inline;">
                            @csrf
                            <!-- Put the subadmin ID in a hidden field -->
                            <input type="hidden" name="id" value="{{ $subadmin['id'] }}">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this subadmin?')">
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
</body>
</html>
