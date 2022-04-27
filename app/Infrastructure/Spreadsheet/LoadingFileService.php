<?php

namespace App\Infrastructure\Spreadsheet;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LoadingFileService
{
    /**
     * @param string $filepath
     * @return Spreadsheet
     * @throws Exception
     */
    public function load(string $filepath): Spreadsheet
    {
        $reader = IOFactory::createReaderForFile($filepath);
        return $reader->load($filepath);
    }
}
