<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BC Ledger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .table-bordered {
            border: 1px solid #000;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
            padding: 5px 10px;
        }
        .table-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .report-title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .filters {
            margin-bottom: 20px;
        }
        .total-row {
            font-weight: bold;
        }
        .green-row {
            background-color: #d4edda;
        }
        @media print {
            .no-print {
                display: none;
            }
            .report-container {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
    

    <div class="no-print filters row">
    <div class="col-12">
        <a href="{{route('admin.excel.logs')}}"><button class="btn btn-dark d-block ms-auto">Back</button></a>
    </div>
    
    <form action="{{route('bc-ledger.report')}}" method="POST" class="row" id="reportForm">
        @csrf
        <div class="col-md-6">
            <label for="cspAgent" class="form-label">Select CSP Agent:</label>
            <select id="cspAgent" name="cspAgent" class="form-select">
                <option value="">All Agents</option>
                @foreach($agents as $csp)
                <option value="{{ $csp['ko_code'] }}"> {{ $csp['name'] }} </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="monthYear" class="form-label">Select Month-Year:</label>
            <input type="month" id="monthYear" name="monthYear" class="form-control" value="2024-10">
        </div>
        
        <div class="col-md-12 mt-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary me-2">Generate Report</button>
        </div>
    </form>
    
    <div class="col-md-12 mt-3 d-flex justify-content-end">
        <button id="exportPdf" class="btn btn-success">Export PDF</button>
    </div>
</div>

        <div id="reportContent">
            <div class="report-title">BC Ledger Month of Oct-2024</div>
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td colspan="3">11621584</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td colspan="3">Alamgir Valialam Ansari</td>
                </tr>
                <tr>
                    <th>Zone</th>
                    <td colspan="3">Ahmedabad</td>
                </tr>
                <tr>
                    <th>Branch</th>
                    <td colspan="3">Jamalpur</td>
                </tr>
                <tr class="table-header">
                    <th>Sr.No.</th>
                    <th>Description</th>
                    <th>Amount/Count</th>
                    <th>Commission</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>A/c opened in Finacle</td>
                    <td>12</td>
                    <td>240</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Transactions</td>
                    <td>12,100</td>
                    <td>44</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>PMJJBY</td>
                    <td>1</td>
                    <td>23</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>PMSBY</td>
                    <td>4</td>
                    <td>4</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Aadhar Seeding</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Rupay Activation</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>RD</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>TD</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Chque Book Request</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>Stop Cheque Request</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>ChequeCollection Count</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>Apply Debit Card</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>Block Debit Card</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>Mobile Seeding </td>
                    <td>1</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>Passbook Printing </td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>17</td>
                    <td>Jeevan Praman </td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>18</td>
                    <td>APY Enrollment For April to June 24 </td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>19</td>
                    <td>Loan Sanction Amount Upto 20000 </td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>20</td>
                    <td>Loan Sanction Amount Between 20000 And 25000 </td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>21</td>
                    <td>Minimum 25% Loan Disbursement (Sanctioned After Feb-2023)
                    Amount Between 25000 And 1000000Amount </td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>22</td>
                    <td>Minimum 25% Loan Disbursement (Sanctioned After Feb-2023) Amount More Than 1000000 Amount</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>23</td>
                    <td>Minimum 25% Loan Disbursement (Sanctioned After Feb-2023)Amount Between 25000 And 1000000</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>24</td>
                    <td>Minimum 25% Loan Disbursement (Sanctioned After Feb-2023) Amount More Than 1000000</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>25</td>
                    <td>Loan Repayment (All Pending) Amount Between 25000 And 1000000 Amount</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>26</td>
                    <td>Loan Repayment(AllPending)AmountMore
                    Than1000000</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>27</td>
                    <td>Current 
                    Account</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>31</td>
                    <td>NPA Recovery Amount With  Security Age 1 to 1.5 Yrs</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>32</td>
                    <td>NPA Recovery Amount Without Security Age 1 to 1.5 Yrs or With Security
                    Age 1.5 to 2 Y</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>33</td>
                    <td>NPA Recovery Amount Without Security Age 1.5 to 2 Yrs or With Security Age 2 to 3 Yrs</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>34</td>
                    <td>NPA Recovery Amount Without Security Age 2 to 3 Yrs or With Security Age 3 to 5 Yrs</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>35</td>
                    <td>NPA Recovery Amount Without Security Age 3 to 5 Yrs or Over 5 Years</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>36</td>
                    <td>PPF SSA SCSS</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>37</td>
                    <td>NPS</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>38</td>
                    <td>SGB FRSB</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>39</td>
                    <td>Quarterly AVG BAL 
                    Between 500 AND 1000 (Jul 2024)</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>40</td>
                    <td>Quarterly AVG BAL 
                    Between 1000 AND 2000 (Jul 2024)</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>41</td>
                    <td>Quarterly AVG BAL More Than 2000 (Jul 2024)</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>310.50</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>248.40</td>
                </tr>
                <tr class="green-row">
                    <td colspan="3">CSP TDS (5%)</td>
                    <td>12.42</td>
                </tr>
                <tr class="green-row total-row">
                    <td colspan="3">Grand Total Amount</td>
                    <td>235.98</td>
                </tr>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        

        document.getElementById('exportPdf').addEventListener('click', function() {
            // This would trigger your Laravel DOM PDF export
            window.print();
            // In a real implementation, you would send an AJAX request to your Laravel backend
        });
    </script>
</body>
</html>