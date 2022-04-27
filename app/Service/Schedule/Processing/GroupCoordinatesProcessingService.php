<?php

namespace App\Service\Schedule\Processing;

use App\Service\Schedule\Exception\CannotFindFirstGroupNameException;
use App\Service\Schedule\Processing\Dto\GroupCoordinatesDto;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GroupCoordinatesProcessingService
{
    public const
        GROUP_NAME_REGEX = '/^\w{1,6}[\/\\\].*$/u',
        STRICT_GROUP_NAME_REGEX = '/^\w{1,6}[\/\\].\-\d,2}-\d-.$/u';

    private const FIRST_GROUP_NAME_SEARCH_DEPTH = 20;

    /**
     * @param Worksheet $worksheet
     * @return array<GroupCoordinatesDto>
     * @throws CannotFindFirstGroupNameException
     * @throws Exception
     */
    public function findAGroupsCoordinate(Worksheet $worksheet): array
    {

        $firstGroupNameCoordinates = $this->getFirstGroupNameCoordinates($worksheet);
        if ($firstGroupNameCoordinates !== null) {
            Log::notice('Find first group name', [
                'coordinate' => $firstGroupNameCoordinates,
            ]);
        } else {
            throw new CannotFindFirstGroupNameException();
        }

        return $this->findGroups($worksheet, $firstGroupNameCoordinates);
    }

    /**
     * @param Worksheet $worksheet
     * @return string|null
     * @throws Exception
     */
    private function getFirstGroupNameCoordinates(Worksheet $worksheet): ?string
    {
        $countDepth = 1;
        $firstGroupNameCoordinate = null;
        while ($countDepth < self::FIRST_GROUP_NAME_SEARCH_DEPTH) {
            $firstGroupNameCoordinate = $this->findGroupNameInSquare($countDepth, $worksheet);

            if ($firstGroupNameCoordinate) {
                break;
            }

            $countDepth++;
        }
        return $firstGroupNameCoordinate;
    }

    /**
     * @param int $countDepth
     * @param Worksheet $worksheet
     * @return string|null
     * @throws Exception
     */
    private function findGroupNameInSquare(int $countDepth, Worksheet $worksheet): ?string
    {
        for ($rowIndex = 0; $rowIndex < $countDepth; $rowIndex++) {
            for ($columnIndex = 0; $columnIndex < $countDepth; $columnIndex++) {
                $cell = $worksheet->getCellByColumnAndRow($columnIndex, $rowIndex);

                $cellStringValue = trim($cell->getFormattedValue());

                if (preg_match(self::GROUP_NAME_REGEX, $cellStringValue)) {
                    return $cell->getCoordinate();
                }
            }
        }
        return null;
    }

    /**
     * @param Worksheet $worksheet
     * @param string $firstGroupNameCoordinates
     * @return array<GroupCoordinatesDto>
     * @throws Exception
     */
    private function findGroups(Worksheet $worksheet, string $firstGroupNameCoordinates): array
    {
        $groups = [];
        [$columnOfFirstGroup, $rowOfFirstGroup] = Coordinate::coordinateFromString($firstGroupNameCoordinates);
        $endColumnOfWorksheet = $worksheet->getHighestColumn();

        $groupNameColumns = $worksheet->getColumnIterator($columnOfFirstGroup, $endColumnOfWorksheet);

        foreach ($groupNameColumns as $column) {
            $columnIndex = $column->getColumnIndex();
            $coordinate = $columnIndex . $rowOfFirstGroup;
            $cell = $worksheet->getCell($coordinate);
            $cellValue = trim($cell->getFormattedValue());
            if (preg_match(self::GROUP_NAME_REGEX, $cellValue)) {
                $groups[] = new GroupCoordinatesDto($cellValue, $coordinate);
            }
        }

        return $groups;
    }
}
