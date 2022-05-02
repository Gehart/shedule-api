<?php

namespace App\Service\Schedule\Processing\Utils;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
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

    /**
     * @param Worksheet $worksheet
     * @param string $columnIndex
     * @param int $rowIndex
     * @return Cell
     */
    public function getCellByColumnAndRow(Worksheet $worksheet, string $columnIndex, int $rowIndex): Cell
    {
        $currentCoordinates = $columnIndex . $rowIndex;
        return $worksheet->getCell($currentCoordinates);
    }

    public function getCellValueByColumnAndRow(Worksheet $worksheet, string $columnIndex, int $rowIndex): string
    {
        $currentCell = $this->getCellByColumnAndRow($worksheet, $columnIndex, $rowIndex);
        return $this->getCellValueWithinRange($worksheet, $currentCell);
    }

    /**
     * @param string $columnAddress
     * @param int $offset
     * @return string
     * @throws Exception
     */
    public function getIncreasedColumnAddress(string $columnAddress, int $offset = 1): string
    {
        $columnIndex = Coordinate::columnIndexFromString($columnAddress);
        return Coordinate::stringFromColumnIndex($columnIndex + $offset);
    }

    /**
     * @param string $columnAddress
     * @return string
     * @throws Exception
     */
    public function getNextColumn(string $columnAddress): string
    {
        $columnIndex = Coordinate::columnIndexFromString($columnAddress);
        return Coordinate::stringFromColumnIndex(++$columnIndex);
    }
}
