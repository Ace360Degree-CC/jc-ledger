<!DOCTYPE html>
<html>
<head>
    <title>CSP Agent Profile</title>
    @include('commons.headerlinks')
</head>
<body>

    @include('admin.commons.header')

    <div class="container mx-auto">
        
        <div class="bg-white p-4 rounded-md">
                <h1 class="text-3xl text-center font-semibold mb-4">CSP Agent Profile</h1>
                @if(session('success'))
                <div class="bg-green-100 w-full text-green-600 font-semibold text-center p-4 mx-auto mb-4 rounded-md" style="max-width:750px">
                    {{ session('success') }}
                </div>
                @endif
                
                <div class="grid lg:grid-cols-12 grid-cols-1 gap-4">
                    <div class="col-span-2"></div>
                    <div class="col-span-4">
                        <div class="border border-gray-200 border-1 p-4 rounded-md h-full">
                            <div>
                                <p class="mb-4 text-xl"><strong>Name:</strong> {{ $myProfile->name }}</p>
                                <p class="mb-4 text-xl"><strong>Email:</strong> {{ $myProfile->email }}</p>
                                
                                <!-- Display profile image if set -->
                                @if($myProfile->profile)
                                    <p class="mb-4 text-xl">
                                        <strong>Profile Image:</strong><br>
                                        <img src="{{ asset('storage/csp/profile/'.$myProfile->profile) }}" alt="Profile" width="200" class="rounded-full object-cover">
                                    </p>
                                @else
                                    <p class="mb-4 text-xl">
                                        <strong>Profile Image:</strong><br>
                                        <p style="font-size: 0.9rem;" class="text-red-700">(Profile photo not available please upload)</p>
                                    </p>
                                @endif



                                <!-- Display signature if set -->
                                @if($myProfile->signature)
                                    <p class="mb-4 text-xl">
                                        <strong>Signature:</strong><br>
                                        <img src="{{ asset('storage/csp/signature/'.$myProfile->signature) }}" alt="signature" width="200" class="rounded-full object-cover">
                                    </p>
                                @else
                                    <p class="mb-4 text-xl">
                                        <strong>Signature:</strong><br>
                                
                                    <p style="font-size: 0.9rem;" class="text-red-700">(Signature not available please upload)</p>
                                    </p>
                                @endif



                            </div>


                            


                        </div>
                    </div>
                    
                    <div class="col-span-4">
                        <div class="border border-gray-200 border-1 p-4 rounded-md">
                            <h2>Update Profile</h2>
                            <!-- Update Form -->
                            <form method="POST" action="{{ route('auth.profile.update') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4">
                                    <label for="name">Name:</label>
                                    <input type="text" name="name" value="{{ $myProfile->name }}" required>
                                </div>

                                <div class="mb-4">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" value="{{ $myProfile->email }}" required>
                                </div>

                                <div class="mb-4">
                                    <label for="profile">Profile Image:</label>
                                    <input type="file" name="profile" accept="image/*">
                                    <p style="font-size: 0.9rem;">(Leave blank if you don't want to change it)</p>
                                </div>

                                <!-- Signature update section -->
                                <div class="mb-4">
                                    <label for="signature">Signature:</label>
                                    <input type="file" name="signature" accept="image/*">
                                    <p style="font-size: 0.9rem;">(Leave blank if you don't want to change it)</p>
                                </div>

                                <!-- Password update section -->
                                <div class="mb-4">
                                    <label for="password">New Password:</label>
                                    <input type="password" name="password" placeholder="Enter new password">
                                    <p style="font-size: 0.9rem;">(Leave blank to keep current password)</p>
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