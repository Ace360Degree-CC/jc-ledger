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
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .display-message {
            color:rgb(26, 24, 23);
            background-color:rgb(243, 208, 144);
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
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
        <a href="{{route('dashboard')}}"><button class="btn btn-dark d-block ms-auto">Back</button></a>
    </div>
    
    <form action="{{route('csp.bc-ledger.report')}}" method="POST" class="row" id="reportForm">
        @csrf
        
        <div class="col-md-6">
            <label for="monthYear" class="form-label">Select Month-Year:</label>
            <input type="month" id="monthYear" name="monthYear" class="form-control" value="{{ isset($monthyear) ? $monthyear : date('Y-m') }}" required>
        </div>

        <div class="col-md-6">
            
        </div>
        
        <div class="col-md-12 mt-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary me-2">Generate Report</button>
        </div>
    </form>
    
    @if(!isset($isindex))
    <div class="col-md-12 mt-3 d-flex justify-content-end">
        <button id="exportPdf" class="btn btn-success">Export PDF</button>
    </div>
    @endif
</div>

        <div id="reportContent">
        
        @if(isset($isindex))
        <div class="display-message">
        {{ $isindex }}
        </div>
           
        

            @elseif(isset($error))
        <div class="error-message">
            {{ $error }}
        </div>
            @else
            <div class="report-title">BC Ledger Month of {{$month}} - {{$year}}</div>
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td colspan="3">{{ $cspAgentID }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td colspan="3"> {{ $cspAgentName??'Not Available' }}</td>
                </tr>
                <tr>
                    <th>Zone</th>
                    <td colspan="3">{{ $zone??'Not Available' }}</td>
                </tr>
                <tr>
                    <th>Branch</th>
                    <td colspan="3">{{ $branch??'Not Available' }}</td>
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
                    <td>{{ $accountOpen??'-' }}</td>
                    <td>{{ $accountOpenCharges??'-' }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Transactions</td>
                    <td>{{ $transAmount??'-' }}</td>
                    <td>{{ $transCharges??'-' }}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>PMJJBY</td>
                    <td>{{ $PMJJBY_Enroll??'-' }}</td>
                    <td>{{ $PMJJBY_Charges??'-' }}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>PMSBY</td>
                    <td>{{ $PMSBY_Enroll??'-' }}</td>
                    <td>{{ $PMSBY_Charges??'-' }}</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Aadhar Seeding</td>
                    <td>{{ $AadharSeedingEnroll??'-' }}</td>
                    <td>{{ $AadharSeedingCharges??'-' }}</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Rupay Activation</td>
                    <td>{{ $RupayActivationEnroll??'-' }}</td>
                    <td>{{ $RupayActivationCharges??'-' }}</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>RD</td>
                    <td>{{ $RD_Count??'-' }}</td>
                    <td>{{ $RD_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>TD</td>
                    <td>{{ $TD_Amount??'-' }}</td>
                    <td>{{ $TD_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Chque Book Request</td>
                    <td>{{ $NewChequeBookReq??'-' }}</td>
                    <td>{{ $NewChequeBookReqCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>Stop Cheque Request</td>
                    <td>{{ $StopChequeBookReq??'-' }}</td>
                    <td>{{ $StopChequeBookReqCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>ChequeCollection Count</td>
                    <td>{{ $ChequeCollectCount??'-' }}</td>
                    <td>{{ $ChequeCollectCountCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>Apply Debit Card</td>
                    <td>{{ $ApplyDebirCardCount??'-' }}</td>
                    <td>{{ $ApplyDebirCardCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>Block Debit Card</td>
                    <td>{{ $BlockDebitCardCount??'-' }}</td>
                    <td>{{ $BlockDebitCardCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>Mobile Seeding </td>
                    <td>{{ $MobileSeedingCount??'-' }}</td>
                    <td>{{ $MobileSeedingCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>Passbook Printing </td>
                    <td>{{ $PassBookPrintCount??'-' }}</td>
                    <td>{{ $PassBookPrintCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>17</td>
                    <td>Jeevan Praman </td>
                    <td>{{ $JeevanPramanCount??'-' }}</td>
                    <td>{{ $JeevanPramanCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>18</td>
                    <td>APY Enrollment For April to June 24 </td>
                    <td>{{ $APYEnrollment??'-' }}</td>
                    <td>{{ $APYCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>19</td>
                    <td>Loan Sanction Amount Upto 20000 </td>
                    <td>{{ $LoanUpto20KAmount??'-' }}</td>
                    <td>{{ $LoanUpto20KCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>20</td>
                    <td>Loan Sanction Amount Between 20000 And 25000 </td>
                    <td>{{ $LoanUpto20K_25KAmount??'-' }}</td>
                    <td>{{ $LoanUpto20K_25KCommission??'-' }}</td>
                </tr>
                <tr>
                    <td>21</td>
                    <td>Minimum 25% Loan Disbursement (Sanctioned After Feb-2023)
                    Amount Between 25000 And 1000000Amount </td>
                    <td>{{ $Min25perLoanDisbur25k_100k_Amount??'-' }}</td>
                    <td>{{ $Min25perLoanDisbur25k_100k_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>22</td>
                    <td>Minimum 25% Loan Disbursement (Sanctioned After Feb-2023) Amount More Than 1000000 Amount</td>
                    <td>{{ $Min25perLoanDisburMore100k_Amount??'-' }}</td>
                    <td>{{ $Min25perLoanDisburMore100k_Commission??'-' }}</td>
                </tr>
                
                <tr>
                    <td>25</td>
                    <td>Loan Repayment (All Pending) Amount Between 25000 And 1000000 Amount</td>
                    <td>{{ $LoanRepayBetwn25k_100k_Amount??'-' }}</td>
                    <td>{{ $LoanRepayBetwn25k_100k_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>26</td>
                    <td>Loan Repayment(AllPending)AmountMore
                    Than1000000</td>
                    <td>{{ $LoanRepayMore100k_Amount??'-' }}</td>
                    <td>{{ $LoanRepayMore100k_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>27</td>
                    <td>Current 
                    Account</td>
                    <td>{{ $CurrentAccountOpen_Count??'-' }}</td>
                    <td>{{ $CurrentAccountOpen_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>31</td>
                    <td>NPA Recovery Amount With  Security Age 1 to 1.5 Yrs</td>
                    <td>{{ $NPA_RecoveryAmnt_SecurityAge1to1_5_Amount??'-' }}</td>
                    <td>{{ $NPA_RecoveryAmnt_SecurityAge1to1_5_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>32</td>
                    <td>NPA Recovery Amount Without Security Age 1 to 1.5 Yrs or With Security
                    Age 1.5 to 2 Y</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge1to1_5orWithSecurityAge1_5to2_Amount??'-' }}</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge1to1_5orWithSecurityAge1_5to2_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>33</td>
                    <td>NPA Recovery Amount Without Security Age 1.5 to 2 Yrs or With Security Age 2 to 3 Yrs</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge1_5to2orWithSecurityAge2to3_Amount??'-' }}</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge1_5to2orWithSecurityAge2to3_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>34</td>
                    <td>NPA Recovery Amount Without Security Age 2 to 3 Yrs or With Security Age 3 to 5 Yrs</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge2to3orWithSecurityAge3to5_Amount??'-' }}</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge2to3orWithSecurityAge3to5_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>35</td>
                    <td>NPA Recovery Amount Without Security Age 3 to 5 Yrs or Over 5 Years</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge3to5orOver5_Amount??'-' }}</td>
                    <td>{{ $NPA_RecoveryAmntWithoutSecurityAge3to5orOver5_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>36</td>
                    <td>PPF SSA SCSS</td>
                    <td>{{ $PPF_SSA_SCSS_Count??'-' }}</td>
                    <td>{{ $PPF_SSA_SCSS_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>37</td>
                    <td>NPS</td>
                    <td>{{ $NPS_Count??'-' }}</td>
                    <td>{{ $NPS_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>38</td>
                    <td>SGB FRSB</td>
                    <td>{{ $SGB_FRSB_Amount??'-' }}</td>
                    <td>{{ $SGB_FRSB_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>39</td>
                    <td>Quarterly AVG BAL 
                    Between 500 AND 1000 (Jul 2024)</td>
                    <td>{{ $QuarterlyAVG_BAL500_1000_Count??'-' }}</td>
                    <td>{{ $QuarterlyAVG_BAL500_1000_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>40</td>
                    <td>Quarterly AVG BAL 
                    Between 1000 AND 2000 (Jul 2024)</td>
                    <td>{{ $QuarterlyAVG_BAL1000_2000_Count??'-' }}</td>
                    <td>{{ $QuarterlyAVG_BAL1000_2000_Commission??'-' }}</td>
                </tr>
                <tr>
                    <td>41</td>
                    <td>Quarterly AVG BAL More Than 2000 (Jul 2024)</td>
                    <td>{{ $QuarterlyAVG_BALMore2000_Count??'-' }}</td>
                    <td>{{ $QuarterlyAVG_BALMore2000_Commission??'-' }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>{{ $Total??'-' }}</td>
                </tr>
                <tr>
                    <td colspan="3">CSP Share (80%)</td>
                    <td>{{ $CSP_Share80??'-' }}</td>
                </tr>
                <tr class="green-row">
                    <td colspan="3">CSP TDS (2%)</td>
                    <td>{{ $CSP_TDS2??'-' }}</td>
                </tr>
                <tr class="green-row ">
                    <td colspan="3">Bonus</td>
                    <td>{{ $Bonus??'-' }}</td>
                </tr>
                <tr class="green-row total-row">
                    <td colspan="3">Grand Total Amount</td>
                    <td>{{ $Net_Payable??'-' }}</td>
                </tr>
            </table>
            @endif
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