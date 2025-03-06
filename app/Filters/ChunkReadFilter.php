<?php 

namespace App\Filters;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private int $startRow = 0;
    private int $endRow = 0;

    public function setRows(int $startRow, int $chunkSize): void
    {
        $this->startRow = $startRow;
        $this->endRow   = $startRow + $chunkSize - 1;
    }

    /**
     * Must match signature: readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
     */
    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
    {
        return ($row >= $this->startRow && $row <= $this->endRow);
    }
}
