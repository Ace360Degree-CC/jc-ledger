<!DOCTYPE html>
<html lang="en">
<head>
    @include('commons.headerlinks')
    <title>Upload Excel File</title>
</head>
<body>
    @include('admin.commons.header')

    <div class="container mx-auto">

        <div class="bg-white relative overflow-hidden rounded-md p-4 mx-auto" style="max-width:550px">
            <h2 class="text-2xl text-center font-semibold">Upload Excel File</h2>
            <form method="POST" data-action="{{ route('admin.excel.upload') }}" enctype="multipart/form-data">
                            @csrf
                <div class="mb-3">
                    <label for="month_year">Select Month & Year</label>
                    <input type="month" class="form-control @error('month_year') is-invalid @enderror" id="month_year" name="month_year" value="{{ old('month_year') }}" required>
                    @error('month_year')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                <label for="excel_file" class="form-label">Excel File</label>
                                <input type="file" class="form-control @error('excel_file') is-invalid @enderror" id="excel_file" name="excel_file" required>
                                @error('excel_file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            

                <button type="submit" class="btn-theme">Upload</button>

                <div id="alert-box">
                </div>
               
            </form>  
            <div class="processing-indicate hidden mt-4 text-lg text-center ">processing. this may take some time <i class="fa-solid fa-circle-notch fa-spin text-gray-400"></i></div>
            <div class="upload-progress absolute bottom-0 left-0 bg-green-700 py-1" style="width:0">
        </div>

        
        

        <div class="mt-5">
        @if(session('success'))
                    <div class="card mt-4">
                        <div class="card-header">View Imported Data</div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                @endif
        </div>

    </div>

    <a href="{{route('admin.excel.logs')}}"><button class="px-4 py-2 rounded-md hover:bg-gray-500 bg-gray-700 text-white block cursor-pointer mt-4 mx-auto">Back</button></a>

    </div>

    @include('commons.footer')

    <script>
        $(document).ready(function(){

            function showAlert(type,text){
                $('.processing-indicate').addClass('hidden');
                $('.upload-progress').addClass('hidden');

                if(type=='success'){
                    $('#alert-box').html(`<div class="bg-green-100 text-center rounded-md mt-2 text-green-700 p-2">${text}</div>`)
                }
                else{
                    $('#alert-box').html(`<div class="bg-red-100 text-center rounded-md mt-2 text-red-700 p-2">${text}</div>`)
                }

                setTimeout(() => {
                    $('#alert-box').html('');
                    
                }, 3000);
            }


            $('form').on('submit',function(e){
                e.preventDefault();
                let url =  $(this).attr('data-action');
                let data = new FormData(this);
                console.log(data);
                $.ajax({
                    xhr:function(){
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress',function(evt){
                            if(evt.lengthComputable){
                                let percentage = Math.round((evt.loaded/evt.total)*100);
                                $('.processing-indicate').removeClass('hidden');
                                $('.upload-progress').css('width',`${percentage}%`)
                            }
                        },false)
                        return xhr;
                    },
                    url:url,
                    data:data,
                    type:'post',
                    processData:false,
                    contentType:false,
                    cache:false,
                    success:function(res){
                        if(res.status=='success'){
                            showAlert(res.status,res.message);
                            setTimeout(() => {
                                window.location.href = "<?= route('admin.excel.logs');?>"
                            }, 3000);
                            
                        }
                        else{
                            showAlert('error',res.message);
                        }
                    },
                    error:function(err){
                        showAlert('error',err.message);
                    }
                })

            })
        })
    </script>

</body>
</html>