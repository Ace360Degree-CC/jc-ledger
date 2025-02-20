<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
</head>
<body>
    <h1>Admin Profile</h1>

    @if(session('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Display current admin info (read-only or in a form) -->
    <div>
        <p><strong>Name:</strong> {{ $admin->name }}</p>
        <p><strong>Email:</strong> {{ $admin->email }}</p>
        <p><strong>Username:</strong> {{ $admin->username }}</p>
        <p><strong>Status:</strong> {{ $admin->status ? 'Active' : 'Inactive' }}</p>
        
        <!-- Display profile image if set -->
        @if($admin->profile)
            <p>
                <strong>Profile Image:</strong><br>
                <img src="{{ asset('storage/'.$admin->profile) }}" alt="Profile" width="100">
            </p>
        @else
            <p><strong>Profile Image:</strong> None</p>
        @endif
    </div>

    <hr>

    <h2>Update Profile</h2>
    <!-- Update Form -->
    <form method="POST" action="{{ route('admin.updateProfile') }}" enctype="multipart/form-data">
        @csrf
        <!-- If using PUT/PATCH method, you can spoof it -->
        <!-- @method('PUT') -->

        <!-- Pass the admin ID (if needed) -->
        <input type="hidden" name="id" value="{{ $admin->id }}">

        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" value="{{ $admin->name }}" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" value="{{ $admin->email }}" required>
        </div>

        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" value="{{ $admin->username }}" required>
        </div>

        <div>
            <label for="profile">Profile Image:</label>
            <input type="file" name="profile">
            <p style="font-size: 0.9rem;">(Leave blank if you don’t want to change it)</p>
        </div>

        <!-- If you also want to update password -->
        <div>
            <label for="password">New Password:</label>
            <input type="password" name="password" placeholder="Enter new password">
        </div>
        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password">
        </div>

        <div>
            <label for="status">Status:</label>
            <select name="status">
                <option value="1" {{ $admin->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $admin->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
