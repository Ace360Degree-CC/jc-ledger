<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\ExcelLog;
use App\Models\ExcelData;

class BCLedgerController extends Controller
{
    public function index()
    {
        // Get CSP agents from your database
        $agents = User::select('id', 'ko_code', 'name')->get()->toArray();
        //print_r("<pre>");print_r($agents);exit;
        
        return view('admin.excel.ledger', compact('agents'));
    }
    
    public function generateReport(Request $request)
    {
        // Validate request
        $request->validate([
            'cspAgent' => 'nullable|string',
            'monthYear' => 'required|string',
        ]);
        
        $agentId = $request->input('cspAgent');
        $monthYear = $request->input('monthYear');
        $exceLogData = ExcelLog::where('month',$monthYear)->first()->toArray();
        $excelUniqID = $exceLogData['uniqID'];

        $ledgerData = ExcelData::where(['uniqID'=>$excelUniqID, 'B'=>$agentId])->first()->toArray();

        // print_r("<pre>");print_r($ledgerData);exit;

        $data['cspAgentID'] = $agentId;
        $data['cspAgentName'] = $ledgerData['I'];
        $data['zone'] = $ledgerData['E'];
        $data['branch'] = $ledgerData['F'];
        $data['accountOpen'] = $ledgerData['O'];
        $data['accountOpenCharges'] = $ledgerData['P'];
        $data['transAmount'] = $ledgerData['R'];
        $data['transCharges'] = $ledgerData['S'];
        $data['PMJJBY_Enroll'] = $ledgerData['X'];
        $data['PMJJBY_Charges'] = $ledgerData['Y'];
        $data['PMSBY_Enroll'] = $ledgerData['Z'];
        $data['PMSBY_Charges'] = $ledgerData['AA'];
        $data['AadharSeedingEnroll'] = $ledgerData['T'];
        $data['AadharSeedingCharges'] = $ledgerData['U'];
        $data['RupayActivationEnroll'] = $ledgerData['V'];
        $data['RupayActivationCharges'] = $ledgerData['W'];
        $data['RD_Count'] = $ledgerData['AB'];
        $data['RD_Commission'] = $ledgerData['AC'];
        $data['TD_Amount'] = $ledgerData['AK'];
        $data['TD_Commission'] = $ledgerData['AL'];
        $data['NewChequeBookReq'] = $ledgerData['AM'];
        $data['NewChequeBookReqCommission'] = $ledgerData['AN'];
        $data['StopChequeBookReq'] = $ledgerData['AO'];
        $data['StopChequeBookReqCommission'] = $ledgerData['AP'];
        $data['ChequeCollectCount'] = $ledgerData['AQ'];
        $data['ChequeCollectCountCommission'] = $ledgerData['AR'];
        $data['ApplyDebirCardCount'] = $ledgerData['AS'];
        $data['ApplyDebirCardCommission'] = $ledgerData['AT'];
        $data['BlockDebitCardCount'] = $ledgerData['AU'];
        $data['BlockDebitCardCommission'] = $ledgerData['AV'];
        $data['MobileSeedingCount'] = $ledgerData['AW'];
        $data['MobileSeedingCommission'] = $ledgerData['AX'];
        $data['PassBookPrintCount'] = $ledgerData['AY'];
        $data['PassBookPrintCommission'] = $ledgerData['AZ'];
        $data['JeevanPramanCount'] = $ledgerData['BA'];
        $data['JeevanPramanCommission'] = $ledgerData['BB'];
        $data['APYEnrollment'] = $ledgerData['BC'];
        $data['APYCommission'] = $ledgerData['BD'];
        $data['LoanUpto20KAmount'] = $ledgerData['BW'];
        $data['LoanUpto20KCommission'] = $ledgerData['BX'];
        $data['LoanUpto20K-25KAmount'] = $ledgerData['BY'];
        $data['LoanUpto20K-25KCommission'] = $ledgerData['BZ'];

        $data['Min25%LoanDisbur25k-100k_Amount'] = $ledgerData['CA'];
        $data['Min25%LoanDisbur25k-100k_Commission'] = $ledgerData['CB'];

        $data['Min25%LoanDisburMore100k_Amount'] = $ledgerData['CC'];
        $data['Min25%LoanDisburMore100k_Commission'] = $ledgerData['CD'];

        $data['LoanRepayBetwn25k-100k_Amount'] = $ledgerData['CI'];
        $data['LoanRepayBetwn25k-100k_Commission'] = $ledgerData['CJ'];

        $data['LoanRepayMore100k_Amount'] = $ledgerData['CK'];
        $data['LoanRepayMore100k_Commission'] = $ledgerData['CL'];


        $data['CurrentAccountOpen_Count'] = $ledgerData['BE'];
        $data['CurrentAccountOpen_Commission'] = $ledgerData['BF'];

        $data['NPA_RecoveryAmnt+SecurityAge1to1-5_Amount'] = $ledgerData['BK'];
        $data['NPA_RecoveryAmnt+SecurityAge1to1-5_Commission'] = $ledgerData['BL'];


        $data['NPA_RecoveryAmntWithoutSecurityAge1to1-5orWithSecurityAge1-5to2_Amount'] = $ledgerData['BM'];
        $data['NPA_RecoveryAmntWithoutSecurityAge1to1-5orWithSecurityAge1-5to2_Commission'] = $ledgerData['BN'];


        $data['NPA_RecoveryAmntWithoutSecurityAge1-5to2orWithSecurityAge2to3_Amount'] = $ledgerData['BO'];
        $data['NPA_RecoveryAmntWithoutSecurityAge1-5to2orWithSecurityAge2to3_Commission'] = $ledgerData['BP'];


        $data['NPA_RecoveryAmntWithoutSecurityAge2to3orWithSecurityAge3to5_Amount'] = $ledgerData['BQ'];
        $data['NPA_RecoveryAmntWithoutSecurityAge2to3orWithSecurityAge3to5_Commission'] = $ledgerData['BR'];


        $data['NPA_RecoveryAmntWithoutSecurityAge3to5orOver5_Amount'] = $ledgerData['BS'];
        $data['NPA_RecoveryAmntWithoutSecurityAge3to5orOver5_Commission'] = $ledgerData['BT'];

        $data['PPF-SSA-SCSS_Count'] = $ledgerData['CP'];
        $data['PPF-SSA-SCSS_Commission'] = $ledgerData['CQ'];

        $data['NPS_Count'] = $ledgerData['CR'];
        $data['NPS_Commission'] = $ledgerData['CS'];

        $data['SGB-FRSB_Amount'] = $ledgerData['CU'];
        $data['SGB-FRSB_Commission'] = $ledgerData['CV'];


        $data['QuarterlyAVG_BAL500-1000_Count'] = $ledgerData['CW'];
        $data['QuarterlyAVG_BAL500-1000_Commission'] = $ledgerData['CX'];

        $data['QuarterlyAVG_BAL1000-2000_Count'] = $ledgerData['CY'];
        $data['QuarterlyAVG_BAL1000-2000_Commission'] = $ledgerData['CZ'];

        $data['QuarterlyAVG_BALMore2000_Count'] = $ledgerData['CA'];
        $data['QuarterlyAVG_BALMore2000_Commission'] = $ledgerData['CB'];



        //print_r("<pre>");print_r($data);exit;
        
        // Parse month and year
        list($year, $month) = explode('-', $monthYear);
        $monthName = date('M', mktime(0, 0, 0, $month, 1));
        
        // Get agent data from DB based on filters
        // This is where you would implement your database query
        // For this example, we're using static data
        $data = [
            'id' => '11621584',
            'name' => 'Alamgir Valialam Ansari',
            'zone' => 'Ahmedabad',
            'branch' => 'Jamalpur',
            'entries' => [
                ['sr_no' => 1, 'description' => 'A/c opened in Finacle', 'amount' => 12, 'commission' => 240],
                ['sr_no' => 2, 'description' => 'Transactions', 'amount' => 12100, 'commission' => 44],
                ['sr_no' => 3, 'description' => 'PMJJBY', 'amount' => 1, 'commission' => 23],
                ['sr_no' => 4, 'description' => 'PMSBY', 'amount' => 4, 'commission' => 4],
                ['sr_no' => 5, 'description' => 'Aadhar Seeding', 'amount' => null, 'commission' => null],
                ['sr_no' => 6, 'description' => 'Rupay Activation', 'amount' => null, 'commission' => null],
                ['sr_no' => 7, 'description' => 'RD', 'amount' => null, 'commission' => null],
                ['sr_no' => 40, 'description' => 'Quarterly AVG BAL Between 1000 AND 2000 (Jul 2024)', 'amount' => null, 'commission' => null],
                ['sr_no' => 41, 'description' => 'Quarterly AVG BAL More Than 2000 (Jul 2024)', 'amount' => null, 'commission' => null],
            ],
            'total' => 310.50,
            'subtotal' => 248.40,
            'tds' => 12.42,
            'grand_total' => 235.98,
            'month' => $monthName,
            'year' => $year
        ];
        
        return view('admin.excel.ledger', compact('data'));
    }
    
    public function exportPdf(Request $request)
    {
        // Similar logic as generateReport to get data
        $request->validate([
            'agent_id' => 'nullable|string',
            'month_year' => 'required|string',
        ]);
        
        $agentId = $request->input('agent_id');
        $monthYear = $request->input('month_year');
        
        // Parse month and year
        list($year, $month) = explode('-', $monthYear);
        $monthName = date('M', mktime(0, 0, 0, $month, 1));
        
        // Get data (same as above)
        $data = [
            'id' => '11621584',
            'name' => 'Alamgir Valialam Ansari',
            'zone' => 'Ahmedabad',
            'branch' => 'Jamalpur',
            'entries' => [
                ['sr_no' => 1, 'description' => 'A/c opened in Finacle', 'amount' => 12, 'commission' => 240],
                ['sr_no' => 2, 'description' => 'Transactions', 'amount' => 12100, 'commission' => 44],
                ['sr_no' => 3, 'description' => 'PMJJBY', 'amount' => 1, 'commission' => 23],
                ['sr_no' => 4, 'description' => 'PMSBY', 'amount' => 4, 'commission' => 4],
                ['sr_no' => 5, 'description' => 'Aadhar Seeding', 'amount' => null, 'commission' => null],
                ['sr_no' => 6, 'description' => 'Rupay Activation', 'amount' => null, 'commission' => null],
                ['sr_no' => 7, 'description' => 'RD', 'amount' => null, 'commission' => null],
                ['sr_no' => 40, 'description' => 'Quarterly AVG BAL Between 1000 AND 2000 (Jul 2024)', 'amount' => null, 'commission' => null],
                ['sr_no' => 41, 'description' => 'Quarterly AVG BAL More Than 2000 (Jul 2024)', 'amount' => null, 'commission' => null],
            ],
            'total' => 310.50,
            'subtotal' => 248.40,
            'tds' => 12.42,
            'grand_total' => 235.98,
            'month' => $monthName,
            'year' => $year
        ];
        
        // Generate PDF using DOM PDF
        $pdf = PDF::loadView('bc-ledger.pdf', compact('data'));
        
        // Set PDF options for better rendering
        $pdf->setPaper('A4');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);
        
        // Download PDF
        return $pdf->download("BC_Ledger_{$data['name']}_{$monthName}_{$year}.pdf");
    }
}