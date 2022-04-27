<?php

namespace tests\Service\Schedule;

use App\Infrastructure\Spreadsheet\LoadingFileService;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use tests\TestCase;

class TestCaseWithSpreadsheet extends TestCase
{
    protected Spreadsheet $spreadsheet;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        /** @var LoadingFileService $fileLoadingService */
        $fileLoadingService = app()->make(LoadingFileService::class);
        $filepath = ROOT_DIR . '/' . 'tests/Service/Schedule/Assets/test-schedule.xls';

        $this->spreadsheet = $fileLoadingService->load($filepath);
    }

    /**
     * @throws Exception
     */
    protected function getFirstWorksheet(): Worksheet
    {
        return $this->spreadsheet->getSheet(0);
    }
}
