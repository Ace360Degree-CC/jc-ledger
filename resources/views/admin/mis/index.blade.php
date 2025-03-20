<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    @include('commons.headerlinks')
</head>
<body>
    @include('admin.commons.header')


    <div class="max-w-7xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">MIS Data</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Show Skipped Rows (duplicates) if any -->
    @if(!empty($skippedKOs))
        <div class="bg-yellow-200 text-yellow-800 p-3 rounded mb-4">
            <p class="font-semibold">Skipped Rows (KO ID already existed):</p>
            <ul class="list-disc ml-6">
                @foreach($skippedKOs as $skipped)
                    <li>{{ $skipped }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('mis.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Upload New Excel
        </a>
    </div>

    <table class="min-w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="py-2 px-4 border">KO ID (C)</th>
                <th class="py-2 px-4 border">KO Name (D)</th>
                <th class="py-2 px-4 border">Location (P)</th>
                <th class="py-2 px-4 border">Branch Name (T)</th>
                <th class="py-2 px-4 border">Branch Code (U)</th>
                <th class="py-2 px-4 border">Agreement Data (AQ)</th>
                <th class="py-2 px-4 border">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($misRecords as $record)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4 border">{{ $record->C }}</td>
                    <td class="py-2 px-4 border">{{ $record->D }}</td>
                    <td class="py-2 px-4 border">{{ $record->P }}</td>
                    <td class="py-2 px-4 border">{{ $record->T }}</td>
                    <td class="py-2 px-4 border">{{ $record->U }}</td>
                    <td class="py-2 px-4 border">{{ $record->AQ }}</td>
                    <td class="py-2 px-4 border text-center">
                        <form action="{{ route('mis.destroy', $record->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('commons.footer')

</body>
</html>

