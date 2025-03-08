<!-- resources/views/excel/log-table.blade.php -->


@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h2>Excel Upload Records</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('excel.form') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Upload New Excel
            </a>
        </div>
    </div>

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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
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
                            <tr>
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
                                <td colspan="5" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination if needed -->
                {{ $ExcelLogs->links() }}
            </div>
        </div>
    </div>
</div>
