<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExcelFileRequest;
use App\Repositories\OnboardProcess\OnboardProcessInterface;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;
use function React\Promise\all;

class OnboardProcessController extends Controller
{

    private OnboardProcessInterface $onboardProcess;

    public function __construct(OnboardProcessInterface $onboardProcess)
    {
        $this->onboardProcess = $onboardProcess;
    }

    public function getChart(ExcelFileRequest $excelFileRequest)
    {

        //Capture Excel Data
        $excelFile = $excelFileRequest->file('excel_file');
        $excelFileName = 'export.csv';
        Storage::disk('local')->putFileAs('/excel', $excelFile, $excelFileName);


        //Capture Data From Excel
        $filePath = storage_path('app/excel/export.csv');
        $userData = Excel::toArray([], $filePath);
        $userDataArr = [];

        foreach ($userData[0] as $key => $ud) {


            if ($key != 0) {

                $id = $ud[0];

                $createdAt = $ud[1];
                $percentage = $ud[2];

                $userDataArr[$key] = [
                    'id' => $id,
                    'createdAt' => $createdAt,
                    'percentage' => $percentage
                ];
            }
        }


        $data = $this->onboardProcess->divideByWeek($userDataArr);
        $data = $this->onboardProcess->findStepsPercentage($data);


        $result = [];
        for ($i = 0; $i < count($data); $i++) {
            $result[$i]['name'] = ($i + 1) . " weeks later";
            $result[$i]['data'] = $data[$i];
        }

        //  Send Response with Formatted User Data
        $response = $result;

        return SendResponseTrait::sendSuccessWithToken($response, "Chart Data", Response::HTTP_CREATED);


    }


}
