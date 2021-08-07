<?php

namespace App\Repositories\OnboardProcess;

use Illuminate\Http\File;

interface OnboardProcessInterface
{

    public function storeSheet(File $excelFile);

    public function getFormattedExcelData(array $excelSheetData);

    public function getChartCodes(array $userDataArr);

    public function findStepsPercentage(array $data): array;

    public function divideByWeek(array $data): array;


}