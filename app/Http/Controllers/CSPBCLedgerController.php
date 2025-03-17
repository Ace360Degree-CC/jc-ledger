<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\ExcelLog;
use App\Models\ExcelData;
use App\Models\User;

class CSPBCLedgerController extends Controller
{
    public function index()
    {
        
        $data['isindex'] = 'Select Month-Year to view records';
        return view('excel.ledger')->with($data);
    }
    
    public function generateReport(Request $request)
    {
        // Validate request
        $request->validate([
            'monthYear' => 'required|string',
        ]);
        
        //$agentId = $request->input('cspAgent');
        $agentId = Auth::guard('web')->user()->ko_code;
        $monthYear = $request->input('monthYear');
        $exceLogData = ExcelLog::where('month',$monthYear)->first();

        $currentCSPName = User::select('name')->where('ko_code', $agentId)->first()->toArray();
        //print_r($currentCSPName['name']);exit;

        // Parse month and year
        list($year, $month) = explode('-', $monthYear);
        $monthName = date('M', mktime(0, 0, 0, $month, 1));
        $data['month'] = $monthName;
        $data['year'] = $year;
        $data['agents'] = User::select('id', 'ko_code', 'name')->get()->toArray();

        $data['cspAgentID'] = $agentId;
        $data['monthyear'] = $monthYear;

        if(!isset($exceLogData) && !isset($exceLogData['uniqID'])){
            $data['error'] = "Record not found for month ". $monthName .' - '. $year;

            return view('excel.ledger')->with($data);
        }
        
        $excelUniqID = $exceLogData['uniqID'];

        $ledgerData = ExcelData::where(['uniqID'=>$excelUniqID, 'B'=>$agentId])->first();

        if(!isset($ledgerData) ){
            $data['error'] = "Record not found for CSP ".$currentCSPName['name'] ." of month ". $monthName .' - '. $year;

            return view('excel.ledger')->with($data);
        }

        // print_r("<pre>");print_r($ledgerData);exit;

        
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

        $data['Min25perLoanDisbur25k_100k_Amount'] = $ledgerData['CA'];
        $data['Min25perLoanDisbur25k_100k_Commission'] = $ledgerData['CB'];

        $data['Min25perLoanDisburMore100k_Amount'] = $ledgerData['CC'];
        $data['Min25perLoanDisburMore100k_Commission'] = $ledgerData['CD'];

        $data['LoanRepayBetwn25k_100k_Amount'] = $ledgerData['CI'];
        $data['LoanRepayBetwn25k_100k_Commission'] = $ledgerData['CJ'];

        $data['LoanRepayMore100k_Amount'] = $ledgerData['CK'];
        $data['LoanRepayMore100k_Commission'] = $ledgerData['CL'];


        $data['CurrentAccountOpen_Count'] = $ledgerData['BE'];
        $data['CurrentAccountOpen_Commission'] = $ledgerData['BF'];

        $data['NPA_RecoveryAmntSecurityAge1to1_5_Amount'] = $ledgerData['BK'];
        $data['NPA_RecoveryAmntSecurityAge1to1_5_Commission'] = $ledgerData['BL'];


        $data['NPA_RecoveryAmntWithoutSecurityAge1to1_5orWithSecurityAge1_5to2_Amount'] = $ledgerData['BM'];
        $data['NPA_RecoveryAmntWithoutSecurityAge1to1_5orWithSecurityAge1_5to2_Commission'] = $ledgerData['BN'];


        $data['NPA_RecoveryAmntWithoutSecurityAge1_5to2orWithSecurityAge2to3_Amount'] = $ledgerData['BO'];
        $data['NPA_RecoveryAmntWithoutSecurityAge1_5to2orWithSecurityAge2to3_Commission'] = $ledgerData['BP'];


        $data['NPA_RecoveryAmntWithoutSecurityAge2to3orWithSecurityAge3to5_Amount'] = $ledgerData['BQ'];
        $data['NPA_RecoveryAmntWithoutSecurityAge2to3orWithSecurityAge3to5_Commission'] = $ledgerData['BR'];


        $data['NPA_RecoveryAmntWithoutSecurityAge3to5orOver5_Amount'] = $ledgerData['BS'];
        $data['NPA_RecoveryAmntWithoutSecurityAge3to5orOver5_Commission'] = $ledgerData['BT'];

        $data['PPF_SSA_SCSS_Count'] = $ledgerData['CP'];
        $data['PPF_SSA_SCSS_Commission'] = $ledgerData['CQ'];

        $data['NPS_Count'] = $ledgerData['CR'];
        $data['NPS_Commission'] = $ledgerData['CS'];

        $data['SGB_FRSB_Amount'] = $ledgerData['CU'];
        $data['SGB_FRSB_Commission'] = $ledgerData['CV'];


        $data['QuarterlyAVG_BAL500_1000_Count'] = $ledgerData['CW'];
        $data['QuarterlyAVG_BAL500_1000_Commission'] = $ledgerData['CX'];

        $data['QuarterlyAVG_BAL1000_2000_Count'] = $ledgerData['CY'];
        $data['QuarterlyAVG_BAL1000_2000_Commission'] = $ledgerData['CZ'];

        $data['QuarterlyAVG_BALMore2000_Count'] = $ledgerData['CA'];
        $data['QuarterlyAVG_BALMore2000_Commission'] = $ledgerData['CB'];

        $data['Total'] = $ledgerData['DE'];
        $data['CSP_Share80'] = $ledgerData['DH'];
        $data['CSP_TDS2'] = $ledgerData['DI'];
        $data['Bonus'] = '';
        $data['Net_Payable'] = $ledgerData['DK'];
        
        

        //$data['val'] = '';
        // print_r("<pre>");print_r($data);exit;
        
        
        return view('excel.ledger')->with($data);
    }
    
   
}