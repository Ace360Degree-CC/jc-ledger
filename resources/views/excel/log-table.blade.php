<!-- resources/views/excel/log-table.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    @include('commons.headerlinks')
</head>
<body>
    
@include('admin.commons.header')

@section('content')
<div class="container mx-auto">

        <h2 class="text-4xl text-center font-semibold">Excel Records</h2>

   
            <a href="{{ route('excel.form') }}">
                <button class="btn-theme"><i class="fas fa-plus-circle"></i> Upload New Excel </button>
            </a>
        

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

            <div class="table-auto w-full rounded-md p-3 bg-white mt-5">
                <table class="table-auto w-full text-left">
                    <thead class="border-b-1 border-gray-300">
                        <tr>
                            <th>Uploaded By</th>
                            <th>Upload From</th>
                            <th>File</th>
                            <th>Upload On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ExcelLogs as $log)
                            <tr class="py-3">
                                <td>
                                {{ $log->uploaderName }}
                                </td>
                                <td>
                                        {{ $log->uploadFrom ?? 'N/A' }}
                                </td>
                                <td>
                                        @if(isset($log->file) && !empty($log->file))
                                            <a href="{{ asset('storage/excel/' . $log->file) }}" target="_blank">
                                                {{ $log->file }}
                                            </a>
                                        @else
                                            <span class="text-muted">No file</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($log->created_at))
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('F d, Y') }}
                                        @else
                                            <span class="text-muted">Unknown date</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($log->id))
                                            <form action="{{ route('excel.delete', $log->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                <i class="fas fa-ban"></i> Unavailable
                                            </button>
                                        @endif
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 font-normal text-xl" >No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination if needed -->
                {{ $ExcelLogs->links() }}
            </div>
       
</div>



@include('commons.footer')


</body>
</html>