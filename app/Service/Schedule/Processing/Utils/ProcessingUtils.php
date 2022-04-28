<?php

namespace App\Service\Schedule\Processing\Utils;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProcessingUtils
{
    /**
     * @param Worksheet $worksheet
     * @param Cell $cell
     * @return string
     */
    public function getCellValueWithinRange(Worksheet $worksheet, Cell $cell): string
    {
        $mergeRange = $cell->getMergeRange();

        if ($mergeRange) {
            $splitRange = Coordinate::splitRange($mergeRange);
            [$startCell] = $splitRange[0];
            $leftTopCell = $worksheet->getCell($startCell);
            $leftTopCellValue = $leftTopCell->getFormattedValue();
        } else {
            $leftTopCellValue = $cell->getFormattedValue();
        }

        return $leftTopCellValue;
    }
}
