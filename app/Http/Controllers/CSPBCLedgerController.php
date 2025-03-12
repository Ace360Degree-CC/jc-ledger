<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class CSPBCLedgerController extends Controller
{
    public function index()
    {
        // Get CSP agents from your database
        $agents = [
            ['id' => '11621584', 'name' => 'Alamgir Valialam Ansari'],
            // Add more agents as needed
        ];
        
        return view('excel.ledger', compact('agents'));
    }
    
    public function generateReport(Request $request)
    {
        // Validate request
        $request->validate([
            'agent_id' => 'nullable|string',
            'month_year' => 'required|string',
        ]);
        
        $agentId = $request->input('agent_id');
        $monthYear = $request->input('month_year');
        
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
        
        return view('excel.ledger', compact('data'));
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