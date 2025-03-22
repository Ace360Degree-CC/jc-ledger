<?php

namespace App\Http\Controllers;

use App\Models\MisData;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SubadminMisController extends Controller
{
    // Show upload form
    public function create()
    {
        return view('subadmin.mis.create');
    }

    // Process uploaded Excel using PhpSpreadsheet
    public function store(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls,csv'
    ]);

    // 1) Load the uploaded Excel file with PhpSpreadsheet
    $spreadsheet = IOFactory::load($request->file('excel_file')->getRealPath());
    $worksheet   = $spreadsheet->getActiveSheet();

    // 2) Convert sheet to array
    //    toArray(null, true, true, false) => each row is a zero-based array
    //    e.g. row[0] => col A, row[1] => col B, row[2] => col C, etc.
    $rows = $worksheet->toArray(null, true, true, false);

    // 3) Skip the first row if it's a header
    $dataRows = array_slice($rows, 1);

    // Keep track of skipped KO IDs
    $skippedKOs = [];

    // Define letters from B..BF (we're skipping A altogether)
    $letters = [
        'B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q',
        'R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL',
        'AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX',
        'AY','AZ','BA','BB','BC','BD','BE','BF'
    ];

    foreach ($dataRows as $row) {
        // 4) Slice off the first column (A)
        //    Now index[0] => original B, index[1] => original C, etc.
        $rowWithoutA = array_slice($row, 1);

        // 5) The KO ID is in what was originally column C
        //    Since we sliced off A, "column B" is index 0, "column C" is index 1, etc.
        $koId = $rowWithoutA[1] ?? null; // index 1 => column C
        if (!$koId) {
            // If no KO ID found, skip
            continue;
        }

        // 6) Check if this KO ID already exists => skip if duplicate
        if (MisData::where('C', $koId)->exists()) {
            $skippedKOs[] = $koId;
            continue;
        }

        // 7) Build the data array for columns B..BF
        $mappedData = [];
        foreach ($rowWithoutA as $index => $cellValue) {
            // $index 0 => col B, 1 => col C, 2 => col D, etc.
            if (isset($letters[$index])) {
                $colLetter = $letters[$index];
                $mappedData[$colLetter] = $cellValue;
            }
        }

        // 8) Insert
        MisData::create($mappedData);
    }

    // 9) Redirect with info about any skipped duplicates
    return redirect()
        ->route('subadmin.mis.index')
        ->with('success', 'Excel data inserted successfully!')
        ->with('skippedKOs', $skippedKOs);
}

    // Show MIS data
    public function index()
    {
        // Retrieve MIS data from the table, only necessary columns
        $misRecords = MisData::select('id','C','D','P','T','U','AQ')->get();

        // Retrieve the skipped KO IDs from session
        $skippedKOs = session('skippedKOs', []); // if none, get an empty array

        return view('subadmin.mis.index', compact('misRecords', 'skippedKOs'));
    }

    // Optional: Delete a record
    public function destroy($id)
    {
        $record = MisData::findOrFail($id);
        $record->delete();
        return redirect()->route('subadmin.mis.index')->with('success', 'Record deleted successfully!');
    }
}
