
<div class="nav-theme">
    <div class="flex row justify-between h-full items-center px-4">
        <div>
            <button id="sidebar-toggle" class="bg-gray-100 p-2 px-4 rounded-md cursor-pointer border-gray-400"><i class="fa-solid fa-expand" id="toggle-icon"></i></button>
        </div>
        <div>
            <div class="flex flex-row items-center">
                <h5>Hi, {{auth()->user()->name}}</h5>
                <div class="profile-launch">
                    <img class="dp-img rounded-full cursor-pointer" src="{{asset('assets/images/profile/profile-default.jpg')}}">
                </div>
                <div class="profile-menu">
                        <h4 class="text-md m-0 font-semibold">{{auth()->user()->name}}</h4>
                        <p class="mb-2 text-sm">{{auth()->user()->email}}</p>
                        <a href="{{route('admin.myprofile')}}"><div class="profile-items px-3 py-2 hover:bg-gray-200 mb-1 cursor-pointer">My Profile</div></a>
                        <!-- <div class="profile-items px-3 py-2 hover:bg-gray-200 mb-1 cursor-pointer">Support</div> -->
                        <hr class="bg-gray-600">
                        <form action="{{ route('admin.logout') }}" method="POST">
                             @csrf
                             <button type="submit" class="profile-items mt-2 text-red-700 profile-items bg-red-100 hover:bg-red-500 hover:text-white cursor-pointer">Sign out</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.commons.sidebar')
