<!-- resources/views/excel/log-table.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    @include('commons.headerlinks')
</head>
<body>
    


@include('subadmin.commons.header')

@section('content')


<div class="container mx-auto">
    <div class="bg-white p-4 rounded-md mb-3">
        <h2 class="text-center text-2xl font-semibold mb-5">Excel Upload Records Admin Side</h2>

        <a href="{{ route('subadmin.excel.form') }}" class="btn-theme">
            <i class="fas fa-plus-circle"></i> Upload New Excel
        </a>


        <div class="theme-table">
        <table class="datatable ">
            <thead>
                <tr class="">
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
                                            <i class="fa-solid fa-file-arrow-down text-2xl"></i>
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
                                            <form class="delete-file"  data-action="{{ route('subadmin.excel.delete', $log->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash text-red-700 text-2xl cursor-pointer"></i>
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
                          
                        @endforelse
                    </tbody>
        </table>
    </div>

    
</div>
{{ $ExcelLogs->links() }}
</div>


@include('commons.footer')

<script>
    $(document).ready(function(){

        function deleteContent(url,formData){
            $.ajax({
                type:'post',
                url:url,
                data:formData,
                processData:false,
                contentType:false,
                cache:false,
                success:function(res){
                    Swal.fire("File Deleted!", "", "success");
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error:function(err){
                    Swal.fire("Something went wrong!", "Something went wrong. Please try again.", "error");
                }
            })
        }


        $('.delete-file').submit(function(e){
            e.preventDefault();
            let url = $(this).data('action');
            let formData = new FormData(this);
            
            Swal.fire({
                icon:'error',
                title: "Are You sure you want to Delete current File?",
                showCancelButton: true,
                confirmButtonText: "Delete",
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    deleteContent(url,formData);
                } else if (result.isDenied) {
                    Swal.fire("Changes are not saved", "", "info");
                }
                });


        })
    })
</script>

</body>
</html>