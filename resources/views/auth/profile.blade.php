<!DOCTYPE html>
<html>
<head>
    <title>CSP Agent Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: auto;
        }
        .profile-container {
            text-align: center;
        }
        img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h2>CSP Agent Profile</h2>

        <!-- Profile Image -->
        @if($myProfile->profile)
            <img src="{{ asset('storage/csp/profile/' . $myProfile->profile) }}" alt="Profile Image">
        @else
            <img src="{{ asset('default-avatar.png') }}" alt="Default Profile">
        @endif

        <form method="POST" action="{{ route('auth.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <label>Name:</label>
            <input type="text" name="name" value="{{ $myProfile->name }}" required>

            <label>Email:</label>
            <input type="email" name="email" value="{{ $myProfile->email }}" required>

            <label>Profile Image:</label>
            <input type="file" name="profile" accept="image/*">

            <label>New Password (Leave blank to keep current password):</label>
            <input type="password" name="password">

            <button type="submit">Update Profile</button>
        </form>

        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
    </div>

</body>
</html>
