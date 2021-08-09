<?php

namespace App\Repositories\OnboardProcess;

use Illuminate\Http\File;

interface OnboardProcessInterface
{

    public function storeSheet(File $excelFile);

    public function getFormattedExcelData(array $excelSheetData);

    public function getChartCodes(array $userDataArr);

    public function calculateStepsPercentage(array $data): array;

    public function getWeeklyBasedPercentage(array $data): array;


}