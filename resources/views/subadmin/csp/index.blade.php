<!DOCTYPE html>
<html>
<head>
    <title>JC | Subadmin - All CSP Agents</title>
    @include('commons.headerlinks')

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        /* Hover dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-item {
            padding: 10px;
            display: block;
            cursor: pointer;
            color: #333;
            text-decoration: none;
        }
        .dropdown-item:hover {
            background-color: #f1f1f1;
        }
        /* Agreement upload modal */
        .agreement-modal {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .agreement-modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            max-width: 500px;
            border-radius: 8px;
        }
    </style>

</head>
<body>

@include('subadmin.commons.header')


<div class="container mx-auto">
    <div class="bg-white rounded-md p-4 ">

    <h2 class="text-center text-2xl mb-4">All CSPs</h2>

    @if (session('success'))
    <div class="bg-green-100 text-green-500 text-center p-3 rounded-md">
        {{ session('success') }}
    </div>
    @endif

    <!-- If you want a link to create a new subadmin -->
    <p>
        <a href="{{ route('subadmin.addCSP') }}" class="btn-theme">Create New CSP</a>
    </p>

    <div class="theme-table mt-5 text-center">
    <table class="datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($csps as $index=>$csp)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>
                    <img 
                        src="{{ asset('storage/csp/profile/' . ($csp['profile'] ?? 'noprofile.webp')) }}" 
                        alt="Profile Image" 
                        style="height:100px;width:auto;display:block;margin:auto">
                    </td>

                    <td>{{ $csp['name'] }}</td>
                    <td>{{ $csp['email'] }}</td>
                    <td>{{ ($csp['status'])?'Active':'In-Active' }}</td>
                    <td>

                    <!-- Documents button -->
                    <button class="p-2 px-4 bg-blue-700 text-white rounded-md cursor-pointer mb-2" 
                                onclick="openDocumentsModal('{{ $csp['name'] }}', '{{ $csp['ko_code'] ?? 'N/A' }}', {{ $csp['id'] }})">
                            Documents
                        </button>

                        <!-- Edit link -->
                        <a href="{{ route('subadmin.editCSP', $csp['id']) }}"><button class="bg-gray-700 text-white p-2 px-4 rounded-md">Edit</button></a>

                        <!-- Delete form -->
                        <form action="{{ route('subadmin.deleteCSP') }}" method="POST" style="display:inline;">
                            @csrf
                            <!-- Put the subadmin ID in a hidden field -->
                            <input type="hidden" name="id" value="{{ $csp['id'] }}">
                            <button type="submit" class="bg-red-700 text-white p-2 px-4 rounded-md" onclick="return confirm('Are you sure you want to delete this subadmin?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No csps found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


</div>
</div>



<!-- Documents Modal -->
<div id="documentsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDocumentsModal()">&times;</span>
            <div class="mb-4">
                <h2 class="text-xl font-bold" id="cspName"></h2>
                <p class="text-gray-600" id="koCode"></p>
            </div>
            
            <form id="documentsForm" action="{{ route('admin.updateDocuments') }}" method="POST">
                @csrf
                <input type="hidden" name="csp_id" id="cspId">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Certificate Name</th>
                            <th class="border p-2">View</th>
                            <th class="border p-2">Verified</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ID Card Row -->
                        <tr>
                            <td class="border p-2">ID Card</td>
                            <td class="border p-2">
                                <div class="dropdown">
                                    <i class="fa fa-eye cursor-pointer" aria-hidden="true"></i>
                                    <div class="dropdown-content">
                                    <a href="#" class="dropdown-item" onclick="viewDocument('idcard')">View ID Card</a>
                                    <a href="#" class="dropdown-item" onclick="viewDocument('pancard')">Pancard</a>
                                    <a href="#" class="dropdown-item" onclick="viewDocument('photo')">Photo</a>
                                    <a href="#" class="dropdown-item" onclick="viewDocument('signature')">Signature</a>
                                        
                                    </div>
                                </div>
                            </td>
                            <td class="border p-2">
                                <input type="checkbox" name="verified[1]" class="form-checkbox h-5 w-5">
                            </td>
                            <td class="border p-2">
                                <select name="status[1]" class="form-select rounded-md border-gray-300 p-2 w-full">
                                    <option>Select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </td>
                            <td class="border p-2">
                                <input type="text" name="reason[1]" class="form-input rounded-md border-gray-300 p-2 w-full">
                            </td>
                        </tr>
                        
                        <!-- BC Certificate Row -->
                        <tr>
                            <td class="border p-2">BC Certificate</td>
                            <td class="border p-2">
                                <div class="dropdown">
                                    <i class="fa fa-eye cursor-pointer" aria-hidden="true" onclick="viewCertificate()"></i>
                                </div>
                            </td>
                            <td class="border p-2">
                                <input type="checkbox" name="verified[2]" class="form-checkbox h-5 w-5">
                            </td>
                            <td class="border p-2">
                                <select name="status[2]" class="form-select rounded-md border-gray-300 p-2 w-full">
                                    <option>Select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </td>
                            <td class="border p-2">
                                <input type="text" name="reason[2]" class="form-input rounded-md border-gray-300 p-2 w-full">
                            </td>
                        </tr>
                        
                        <!-- BC Agreement Row -->
                        <tr>
                            <td class="border p-2">BC Agreement</td>
                            <td class="border p-2" id="agreementViewCell">
                                <!-- This will be populated by JS based on agreement status -->
                            </td>
                            <td class="border p-2">
                                <input type="checkbox" name="verified[3]" class="form-checkbox h-5 w-5">
                            </td>
                            <td class="border p-2">
                                <select name="status[3]" class="form-select rounded-md border-gray-300 p-2 w-full">
                                    <option>Select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </td>
                            <td class="border p-2">
                                <input type="text" name="reason[3]" class="form-input rounded-md border-gray-300 p-2 w-full">
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="mt-4 text-right">
                    <button type="submit" class="p-2 px-6 bg-green-600 text-white rounded-md cursor-pointer">Update</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Agreement Upload Modal -->
    <div id="agreementModal" class="agreement-modal">
        <div class="agreement-modal-content">
            <span class="close" onclick="closeAgreementModal()">&times;</span>
            <h3 class="text-lg font-bold mb-4">Upload Agreement</h3>
            
            <form id="agreementForm" action="{{ route('admin.uploadAgreement') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ko_code" id="agreementCspKoCode">
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Upload Agreement File</label>
                    <input type="file" name="agreement_file" class="w-full p-2 border rounded-md">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Agreement Date</label>
                    <input type="date" name="agreement_date" class="w-full p-2 border rounded-md">
                </div>
                
                <div class="text-right">
                    <button type="submit" class="p-2 px-6 bg-blue-600 text-white rounded-md cursor-pointer">Upload</button>
                </div>
            </form>
        </div>
    </div>


    @include('commons.footer')


    <script>
        // Global variables
        let currentCspId = null;
        let currentCspKoCode = null;
        let documentsData = {};
        
        // Fetch documents data for a CSP 
        async function fetchDocumentsData(cspId) {
            try {
                const response = await fetch(`{{ url('subadmin/csp/documents') }}/${cspId}`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching documents data:', error);
                return {};
            }
        }
        
        // Open documents modal
        async function openDocumentsModal(name, koCode, cspId) {
            currentCspId = cspId;
            currentCspKoCode = koCode;
            document.getElementById('cspName').textContent = name;
            document.getElementById('koCode').textContent = koCode;
            document.getElementById('cspId').value = cspId;
            
            // Fetch documents data
            documentsData = await fetchDocumentsData(cspId);
            

            document.getElementById("documentsForm").reset();
            document.getElementById("agreementForm").reset();

            // Populate the form with existing data
            populateDocumentsForm();
            
            // Show the modal
            document.getElementById('documentsModal').style.display = 'block';
        }
        
        // Close documents modal
        function closeDocumentsModal() {
            document.getElementById('documentsModal').style.display = 'none';
        }
        
        // Populate documents form with data
        function populateDocumentsForm() {
            // Set up BC Agreement cell based on whether it's uploaded
            const agreementCell = document.getElementById('agreementViewCell');
            const hasAgreement = documentsData.hasOwnProperty('3') && documentsData['3'].status !== 'pending';
            
            if (hasAgreement) {
                agreementCell.innerHTML = `
                    <div class="dropdown">
                        <i class="fa fa-eye cursor-pointer" aria-hidden="true"></i>
                        <div class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="viewDocument('agreement')">View Agreement</a>
                            <a href="#" class="dropdown-item" onclick="openAgreementModal()">Upload Agreement</a>
                        </div>
                    </div>
                `;
            } else {
                agreementCell.innerHTML = `
                    <div class="dropdown">
                        <i class="fa fa-upload cursor-pointer" aria-hidden="true" onclick="openAgreementModal()"></i>
                    </div>
                `;
            }
            
            // Populate form values for each document type
            [1, 2, 3].forEach(certificateId => {
                if (documentsData.hasOwnProperty(certificateId)) {
                    const doc = documentsData[certificateId];
                    
                    // Set verified checkbox
                    const verifiedCheckbox = document.querySelector(`input[name="verified[${certificateId}]"]`);
                    if (verifiedCheckbox) {
                        verifiedCheckbox.checked = doc.verified === 1;
                    }
                    
                    // Set status dropdown
                    const statusSelect = document.querySelector(`select[name="status[${certificateId}]"]`);
                    if (statusSelect) {
                        statusSelect.value = doc.status;
                    }
                    
                    // Set reason text
                    const reasonInput = document.querySelector(`input[name="reason[${certificateId}]"]`);
                    if (reasonInput) {
                        reasonInput.value = doc.message || '';
                    }
                }
            });
        }
        
        // View document function
        function viewDocument(docType) {
            koCode = document.getElementById('koCode').textContent;
            //alert(`Viewing ${docType} for KO Code: ${koCode}`);

            if(docType == 'idcard'){
                // Open in new tab
                window.open(`{{ url('/subadmin/csp/document/identity') }}/${koCode}`, '_blank');
                return;
            }

            // Open in new tab
            window.open(`{{ url('/subadmin/csp/document') }}/${docType}/${koCode}`, '_blank');

        }
        
        // View certificate function
        function viewCertificate() {
            koCode = document.getElementById('koCode').textContent;
            // Open in new tab
            window.open(`{{ url('/subadmin/csp/document/certificate') }}/${koCode}`, '_blank');
        }
        
        // // View agreement function
        // function viewAgreement() {
        //     window.open(`{{ url('admin/csp/agreement') }}/${currentCspId}`, '_blank');
        // }
        
        // Open agreement modal
        function openAgreementModal() {
            document.getElementById('agreementCspKoCode').value = currentCspKoCode;
            document.getElementById('agreementModal').style.display = 'block';
        }
        
        // Close agreement modal
        function closeAgreementModal() {
            document.getElementById('agreementModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const documentsModal = document.getElementById('documentsModal');
            const agreementModal = document.getElementById('agreementModal');
            
            if (event.target === documentsModal) {
                documentsModal.style.display = 'none';
            }
            
            if (event.target === agreementModal) {
                agreementModal.style.display = 'none';
            }
        }
    </script>


</body>
</html>
