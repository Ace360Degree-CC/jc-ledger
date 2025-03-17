<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
    @include('commons.headerlinks')
</head>
<body>

    @include('admin.commons.header')


    <div class="container mx-auto ">
        
        <div class="bg-white p-4 rounded-md">
                <h1 class="text-3xl text-center font-semibold mb-4">Admin Profile</h1>
                @if(session('success'))
                <div class="bg-green-100 w-full text-green-600 font-semibold text-center p-4 mx-auto mb-4 rounded-md" style="max-width:750px">
                    {{ session('success') }}
                </div>
                @endif
                
                <div class="grid lg:grid-cols-12 grid-cols-1 gap-4 ">
                    <div class="col-span-2"></div>
                    <div class="col-span-4">
                        <div class=" border border-gray-200 border-1 p-4 rounded-md h-full">
                        <div>
                                <p class="mb-4 text-xl"><strong>Name:</strong> {{ $admin->name }}</p>
                                <p class="mb-4 text-xl"><strong>Email:</strong> {{ $admin->email }}</p>
                                <p class="mb-4 text-xl"><strong>Username:</strong> {{ $admin->username }}</p>
                                <p class="mb-4 text-xl"><strong>Status:</strong> {{ $admin->status ? 'Active' : 'Inactive' }}</p>
                                
                                <!-- Display profile image if set -->
                                @if($admin->profile)
                                    <p class="mb-4 text-xl">
                                        <strong>Profile Image:</strong><br>
                                        <img src="{{ asset('storage/'.$admin->profile) }}" alt="Profile" width="200">
                                    </p>
                                @else
                                    <p class="mb-4 text-xl"><strong>Profile Image:</strong> None</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-span-4">
                        <div class=" border border-gray-200 border-1 p-4 rounded-md">
                        <h2>Update Profile</h2>
                            <!-- Update Form -->
                            <form method="POST" action="{{ route('admin.updateProfile') }}" enctype="multipart/form-data">
                                @csrf
                                <!-- If using PUT/PATCH method, you can spoof it -->
                                <!-- @method('PUT') -->

                                <!-- Pass the admin ID (if needed) -->
                                <input type="hidden" name="id" value="{{ $admin->id }}">

                                <div class="mb-4">
                                    <label for="name">Name:</label>
                                    <input type="text" name="name" value="{{ $admin->name }}" required>
                                </div>

                                <div class="mb-4">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" value="{{ $admin->email }}" required>
                                </div>

                                <div class="mb-4">
                                    <label for="username">Username:</label>
                                    <input type="text" name="username" value="{{ $admin->username }}" required>
                                </div>

                                <div class="mb-4">
                                    <label for="profile">Profile Image:</label>
                                    <input type="file" name="profile">
                                    <p style="font-size: 0.9rem;">(Leave blank if you don’t want to change it)</p>
                                </div>

                                <!-- If you also want to update password -->
                                <div class="mb-4">
                                    <label for="password">New Password:</label>
                                    <input type="password" name="password" placeholder="Enter new password">
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation">Confirm Password:</label>
                                    <input type="password" name="password_confirmation" placeholder="Confirm new password">
                                </div>

                                <div class="mb-4">
                                    <label for="status">Status:</label>
                                    <select name="status" class="px-4 border border-gray-400">
                                        <option value="1" {{ $admin->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $admin->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn-theme block ms-auto">Update Profile</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-span-2"></div>
                </div>    

        </div>
    
    </div>


    


    @include('commons.footer')

</body>
</html>
