<?php

namespace App\Http\Controllers;

use App\Filters\ChunkReadFilter;
use App\Models\ExcelData;
use App\Models\ExcelLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SubAdminExcelLogController extends Controller
{
    public function showUploadForm()
    {
        return view('excel/import-excel');
    }

    public function index()
    {
        $ExcelLogs = ExcelLog::with(['admin' => function ($query) {
            $query->select('id', 'name');
        }, 'subadmin' => function ($query) {
            $query->select('id', 'name');
        }])->orderBy('created_at','DESC')->paginate(10);
        // print_r("<pre>");print_r($ExcelLogs);exit;

        return view('excel.log-table', compact('ExcelLogs'));
    }

    /**
     * Delete an excel log record and its associated data
     */
    public function deleteLog($id)
    {
        try {
            // Get the log entry
            $log = ExcelLog::findOrFail($id);

            // Optional: Delete the associated file from storage
            if (Storage::disk('public')->exists('excel/' . $log->file)) {
                Storage::disk('public')->delete('excel/' . $log->file);
            }

            // Optional: Delete associated data from excel_data table
            // DB::table('excel_data')->where('uniqID', $log->uniqID)->delete();
            // ExcelData::delete(['uniqId', $log->uniqID]);
            ExcelData::where('uniqId', $log->uniqID)->delete();

            // Delete the log entry
            $log->delete();

            return redirect()
                ->route('excel.logs')
                ->with('success', 'Record and associated data deleted successfully');
        } catch (\Exception $e) {
            return redirect()
                ->route('excel.logs')
                ->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }

    /*
     * Upload Excel file data in excel_data
     */
    public function uploadExcel(Request $request)
    {
        // Validate the request
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
            'month_year' => 'required|date_format:Y-m',
        ]);

        // Get the month_year value
        $monthYear = $request->input('month_year');
        $crntAdminID = Auth::guard('subadmin')->user()->id;

        // Get the uploaded file path
        $file = $request->file('excel_file');

        // Get the original filename
        $originalFilename = $file->getClientOriginalName();

        // Get file extension
        $extension = $file->getClientOriginalExtension();

        // Get filename without extension
        $filenameWithoutExt = pathinfo($originalFilename, PATHINFO_FILENAME);

        // Create unique filename with timestamp
        $uniqueFilename = $filenameWithoutExt . '_' . time() . '.' . $extension;

        // print_r("<pre>");print_r($originalFilename);exit;
        $filePath = $file->getPathname();

        $storedPath = $file->storeAs('excel', $uniqueFilename, 'public');

        // Optional: Increase memory + max execution time
        @ini_set('memory_limit', '1024M');  // 1 GB
        @ini_set('max_execution_time', '300');  // 5 minutes

        // Prepare the reader + chunk filter
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);  // skip styling for speed
        $chunkFilter = new ChunkReadFilter();
        $reader->setReadFilter($chunkFilter);

        try {
            // Get total rows in the first worksheet
            $worksheetInfo = $reader->listWorksheetInfo($filePath);
            $totalRows = $worksheetInfo[0]['totalRows'];

            // Start from row 4
            $startRow = 4;
            $chunkSize = 1000;
            $importedRowsCount = 0;

            // Start DB transaction
            // DB::beginTransaction();

            // Clear existing data (optional)
            // DB::table('excel_data')->truncate();

            // Generate a unique ID for this upload
            $uniqID = 'JCL' . time();

            // We'll use this flag to stop reading once Column B is empty
            $exitEarly = false;

            while ($startRow <= $totalRows && !$exitEarly) {
                // 1) Tell the filter which rows to load in this chunk
                $chunkFilter->setRows($startRow, $chunkSize);

                // 2) Load just those rows
                $spreadsheet = $reader->load($filePath);
                // If "Summary" is your target sheet name, otherwise adjust
                $worksheet = $spreadsheet->getActiveSheet('Summary');

                // 3) Convert chunk to array
                $chunkData = $worksheet->toArray();
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);

                // 4) Process each row in this chunk
                $batchData = [];
                foreach ($chunkData as $localIndex => $row) {
                    // Actual row number (optional if you need it)
                    $currentExcelRow = $startRow + $localIndex;

                    // 4a) Check if row 3 (index 2) has a value. If none, skip.
                    $hasValueInRow3 = false;
                    foreach ($row as $cellValue) {
                        if (trim($cellValue ?? '') !== '') {
                            $hasValueInRow3 = true;
                            break;
                        }
                    }
                    if (!$hasValueInRow3) {
                        continue;
                    }

                    // 4b) Check if entire row is empty. If so, skip it.
                    $allEmpty = true;
                    for ($i = 1; $i < count($row); $i++) {
                        if (trim($row[$i] ?? '') !== '') {
                            $allEmpty = false;
                            break;
                        }
                    }
                    if ($allEmpty) {
                        continue;
                    }

                    // 4c) Check if Column B (index 1) is empty => stop processing
                    //     (Also guard against a row that might have fewer than 2 columns)
                    $colB = isset($row[1]) ? trim($row[1]) : '';
                    if ($colB === '') {
                        $exitEarly = true;
                        break;  // break out of this foreach
                    }

                    // 4d) Build row data starting from column B (index 1)
                    $rowData = [
                        'uniqID' => $uniqID,
                        // 'created_at' => now(),
                        // 'updated_at' => now(),
                    ];

                    // Loop from B (index 1) through DN (just like your original code)
                    $i = 1;
                    while (true) {
                        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
                        if ($columnLetter === 'DO') {
                            break;  // reached DO, stop
                        }

                        $cellValue = trim($row[$i] ?? '');
                        // If column DN => convert decimal to percentage
                        if ($columnLetter === 'DN' && is_numeric($cellValue)) {
                            $cellValue = number_format($cellValue * 100) . '%';
                        }

                        $rowData[$columnLetter] = $cellValue;
                        $i++;
                    }

                    $batchData[] = $rowData;
                    $importedRowsCount++;

                    // 4e) Batch insert for performance
                    if (count($batchData) >= 100) {
                        DB::table('excel_data')->insert($batchData);
                        $batchData = [];
                    }
                }  // end foreach

                // Insert leftover rows in this chunk
                if (!empty($batchData)) {
                    DB::table('excel_data')->insert($batchData);
                }

                // If we set $exitEarly to true, break the outer while loop
                if ($exitEarly) {
                    // break;

                    // Insert log entry before redirecting
                    DB::table('excel_log')->insert([
                        'uniqID' => $uniqID,
                        'file' => $uniqueFilename,
                        'month' => $monthYear,
                        'uploadFrom' => 'Subadmin',
                        'uploadedBy' => $crntAdminID,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Pass the success message as a variable to the view:
                    return redirect()
                        ->route('excel.logs')
                        ->with('success', "Excel file processed successfully. Imported {$importedRowsCount} rows.");
                }

                // Move to the next chunk
                $startRow += $chunkSize;
            }  // end while

            // Log the upload in excel_log table
            DB::table('excel_log')->insert([
                'uniqID' => $uniqID,
                'file' => $uniqueFilename,
                'month' => $monthYear,
                'uploadFrom' => 'Subadmin',
                'uploadedBy' => $crntAdminID,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Commit DB transaction
            // DB::commit();

            // Return success message (even if we exited early).
            // If you want to differentiate, you can do:
            // if ($exitEarly) { ... } else { ... }
            return redirect()
                ->route('excel.logs')
                ->with('success', "Excel file processed successfully. Imported {$importedRowsCount} rows.");
        } catch (\Exception $e) {
            // If anything genuinely fails, it'll end up here
            // DB::rollBack();
            return redirect()->route('excel.form')->with(
                'error',
                'Error processing file: ' . $e->getMessage()
            );
        }
    }
}
