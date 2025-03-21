@include('commons.headerlinks')
@include('commons.header')

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Download Certificates</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 border-b text-left">S. No.</th>
                        <th class="py-3 px-4 border-b text-left">Certificate Name</th>
                        <th class="py-3 px-4 border-b text-left">Status</th>
                        <th class="py-3 px-4 border-b text-center">View</th>
                        <th class="py-3 px-4 border-b text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $certificateTypes = [
                            ['id' => 1, 'name' => 'ID Card'],
                            ['id' => 2, 'name' => 'BC Certificate'],
                            ['id' => 3, 'name' => 'BC Agreement'],
                        ];
                        
                        $existingCertificates = [];
                        
                        // Group documents by certificate type
                        if(!empty($cspDocuments)) {
                            foreach($cspDocuments as $doc) {
                                $existingCertificates[$doc['certificate_id']] = $doc;
                            }
                        }
                    @endphp

                    @foreach ($certificateTypes as $index => $certType)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="py-3 px-4 border-b">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 border-b">{{ $certType['name'] }}</td>
                            <td class="py-3 px-4 border-b">
                                @if(isset($existingCertificates[$certType['id']]))
                                    @if($existingCertificates[$certType['id']]['status'] == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                    @elseif($existingCertificates[$certType['id']]['status'] == 'approved')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Approved</span>
                                    @elseif($existingCertificates[$certType['id']]['status'] == 'block')
                                        <span class="px-2 py-1 bg-gray-900 text-white rounded-full text-xs">Temporary Block</span>
                                    @elseif($existingCertificates[$certType['id']]['status'] == 'rejected')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Rejected</span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Not Requested</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b text-center">
                                @if(isset($existingCertificates[$certType['id']]) && $existingCertificates[$certType['id']]['status'] == 'approved' && $existingCertificates[$certType['id']]['status'] == 'approved')
                                    <a href="{{ route('certificate.view', ['id' => $certType['id']]) }}" class="text-blue-500 hover:underline">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded">
                                            View
                                        </button>
                                    </a>
                                @else
                                    <button class="bg-gray-300 text-gray-500 py-1 px-3 rounded cursor-not-allowed" disabled>
                                        View
                                    </button>
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b text-center">
                                @if(isset($existingCertificates[$certType['id']]) && $existingCertificates[$certType['id']]['status'] == 'approved')
                                    <a href="{{ route('certificate.download', ['id' => $certType['id']]) }}" class="text-green-500 hover:underline">
                                        <button class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded">
                                            Download
                                        </button>
                                    </a>
                                @elseif(isset($existingCertificates[$certType['id']]) && $existingCertificates[$certType['id']]['status'] == 'pending')
                                    <button class="bg-gray-300 text-gray-500 py-1 px-3 rounded cursor-not-allowed" disabled>
                                        Pending
                                    </button>
                                @elseif(isset($existingCertificates[$certType['id']]) && $existingCertificates[$certType['id']]['status'] == 'block')
                                    <button class="bg-gray-300 text-gray-500 py-1 px-3 rounded cursor-not-allowed" disabled>
                                        Temporary Block
                                    </button>
                                @else
                                    <button 
                                        class="request-certificate bg-indigo-500 hover:bg-indigo-600 text-white py-1 px-3 rounded"
                                        data-certificate-id="{{ $certType['id'] }}"
                                        data-certificate-name="{{ $certType['name'] }}"
                                    >
                                        Request
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requestButtons = document.querySelectorAll('.request-certificate');
        
        requestButtons.forEach(button => {
            button.addEventListener('click', function() {
                const certificateId = this.getAttribute('data-certificate-id');
                const certificateName = this.getAttribute('data-certificate-name');
                
                Swal.fire({
                    title: 'Confirm Request',
                    text: `Are you sure you want to request the ${certificateName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, request it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX request
                        fetch('{{ route("certificate.request") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                certificate_id: certificateId,
                                certificate_name: certificateName
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Requested!',
                                    `Your ${certificateName} request has been submitted successfully.`,
                                    'success'
                                ).then(() => {
                                    // Reload the page to reflect changes
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message || 'Something went wrong.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                'There was a problem with your request.',
                                'error'
                            );
                            console.error('Error:', error);
                        });
                    }
                });
            });
        });
    });
</script>

@include('commons.footer')