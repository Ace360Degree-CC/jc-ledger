<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    @include('commons.headerlinks')
</head>
<body>
    @include('subadmin.commons.header')

    <div class="max-w-2xl mx-auto py-6">
    <h1 class="text-xl font-bold mb-4">Upload MIS Excel</h1>

    @if ($errors->any())
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('subadmin.mis.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="excel_file" class="block mb-2 font-semibold">Select Excel File</label>
        <input type="file" name="excel_file" id="excel_file" class="mb-4 border w-full p-1" required>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Upload
        </button>
    </form>
</div>


@include('commons.footer')

</body>
</html>

