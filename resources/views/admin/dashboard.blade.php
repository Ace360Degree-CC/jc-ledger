<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    @include('commons.headerlinks')
</head>
<body>
    @include('admin.commons.header')
    <div class="container mx-auto">
        <h2 class="text-2xl font-normal">Welcome, {{ auth()->user()->name }}</h2>
        <br>

        <div class="grid grid-cols-3 gap-10">

            <div>
                <div class="p-5 bg-white border-gray-300 rounded-lg border-solid border">

                    <button class=" p-4 text-2xl bg-gray-300 rounded-md w-auto">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </button>
                    <div class="mt-3">
                        <h4 class="text-md text-gray-500">Customers</h4>
                        <h2 class="text-gray-900 font-bold text-4xl">3,500</h2>
                    </div>    

                </div>
            </div>

            <div>
                <div class="p-5 bg-white border-gray-300 rounded-lg border-solid border">

                    <button class=" p-4 text-2xl bg-gray-300 rounded-md w-auto">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </button>
                    <div class="mt-3">
                        <h4 class="text-md text-gray-500">Customers</h4>
                        <h2 class="text-gray-900 font-bold text-4xl">3,500</h2>
                    </div>    

                </div>
            </div>


        </div>

    </div>

    @include('commons.footer')

</body>
</html>
