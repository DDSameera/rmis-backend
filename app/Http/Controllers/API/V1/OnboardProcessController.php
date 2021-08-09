<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExcelFileRequest;
use App\Repositories\OnboardProcess\OnboardProcessInterface;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;


class OnboardProcessController extends Controller
{

    private OnboardProcessInterface $onboardProcess;

    public function __construct(OnboardProcessInterface $onboardProcess)
    {
        $this->onboardProcess = $onboardProcess;
    }

    public function getChart(ExcelFileRequest $excelFileRequest)
    {

        //Store Excel Sheet
        $excelFile = $excelFileRequest->file('excel_file');
        $this->onboardProcess->storeSheet($excelFile);

        //Capture Data From Excel
        $filePath = storage_path('app/excel/export.csv');
        $excelSheetData = Excel::toArray([], $filePath)[0];


        //Format Raw Excel Data
        $formattedExcelSheetDataArr = $this->onboardProcess->getFormattedExcelData($excelSheetData);


        //Get JSON Chart Stats
        $result = $this->onboardProcess->getChartCodes($formattedExcelSheetDataArr);

        // Send Response with Formatted User Data
        return SendResponseTrait::sendSuccessWithToken($result, "Highchart Data", Response::HTTP_OK);


    }


}
