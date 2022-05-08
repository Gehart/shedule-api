<?php

namespace App\Service\Schedule;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ScheduleGettingRequest
{
    private Spreadsheet $spreadsheet;
    private \DateTime $dateStart;
    private \DateTime $dateEnd;

    /**
     * @param Spreadsheet $spreadsheet
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     */
    public function __construct(Spreadsheet $spreadsheet, \DateTime $dateStart, \DateTime $dateEnd)
    {
        $this->spreadsheet = $spreadsheet;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return Spreadsheet
     */
    public function getSpreadsheet(): Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }
}
