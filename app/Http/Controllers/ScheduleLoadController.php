<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class ScheduleLoadController
{
    public function test(Request $request)
    {
        $inputFileName = ROOT_DIR . '/storage/files/short_schedule.xlsx';

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $speadsheetList = $spreadsheet->getActiveSheet()->toArray();

        $fileDir = ROOT_DIR . '/storage/files/';
        $adapter = new LocalFilesystemAdapter($fileDir);
        $filesystem = new Filesystem($adapter);
        $filesystem->write('hello', 'prefix' . $inputFileName);
    }
}
