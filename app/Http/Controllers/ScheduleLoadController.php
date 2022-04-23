<?php

namespace App\Http\Controllers;

use App\Http\Request\StandardRequest;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ScheduleLoadController
{
    public function test(StandardRequest $request)
    {
        $inputFileName = ROOT_DIR . '/storage/files/short_schedule.xlsx';

        /**  Identify the type of $inputFileName  **/
        $inputFileType = IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = IOFactory::createReader($inputFileType);


        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        $spreadsheet = IOFactory::load($inputFileName);

        $writer = IOFactory::createWriter($spreadsheet, $inputFileType);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $spreadsheetList = $spreadsheet->getActiveSheet()->toArray();

        $fileDir = ROOT_DIR . '/storage/files/';
        $outputFileName = 'processed_file.xlsx';
        $outputFilePath = $fileDir . $outputFileName;
        $writer->save($outputFilePath);
//        $adapter = new LocalFilesystemAdapter($fileDir);
//        $filesystem = new Filesystem($adapter);
//        $filesystem->copy('short_schedule.xlsx', 'prefix_short_schedule.xlsx');
    }
}
