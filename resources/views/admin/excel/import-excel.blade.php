<!-- resources/views/excel/upload.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload Excel File</div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.excel.upload') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="month_year" class="form-label">Select Month and Year</label>
                                <input type="month" class="form-control @error('month_year') is-invalid @enderror" id="month_year" name="month_year" value="{{ old('month_year') }}">
                                @error('month_year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="excel_file" class="form-label">Excel File</label>
                                <input type="file" class="form-control @error('excel_file') is-invalid @enderror" id="excel_file" name="excel_file">
                                @error('excel_file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="card mt-4">
                        <div class="card-header">View Imported Data</div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>