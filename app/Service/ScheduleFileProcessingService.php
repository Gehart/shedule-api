<?php

namespace App\Service;

use App\Exceptions\Processing\NotFoundFileException;
use App\Infrastructure\FilesystemAdapter;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ScheduleFileProcessingService
{
    public function __construct(
        private FilesystemAdapter $filesystemAdapter,
    ) {
    }

    /**
     * @param $filepath
     * @return void
     * @throws \Throwable
     */
    public function getScheduleFromFile($filepath): void
    {
        if ($this->filesystemAdapter->fileExists($filepath)) {
            throw new NotFoundFileException();
        }

        $reader = IOFactory::createReaderForFile($filepath);
        $spreadsheet = $reader->load($filepath);
        $worksheets = $spreadsheet->getAllSheets();
        foreach ($worksheets as $worksheet) {
            $mergeCells = $worksheet->getMergeCells();
        }
    }
}
