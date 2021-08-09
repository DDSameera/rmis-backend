<?php

namespace App\Repositories\OnboardProcess;

use Illuminate\Support\Facades\Storage;
use DateTime;
class OnboardProcessRepository implements OnboardProcessInterface
{

    private array $stepPercentages = [0, 20, 40, 50, 70, 90, 99, 100];


    public function storeSheet($excelFile)
    {

        $excelFileName = 'export.csv';
        Storage::disk('local')->putFileAs('/excel', $excelFile, $excelFileName);

    }

    public function getFormattedExcelData(array $excelSheetData): array
    {

        $result = [];

        foreach ($excelSheetData as $key => $ud) {


            if ($key != 0) {

                $id = $ud[0];

                $createdAt = $ud[1];
                $percentage = $ud[2];

                if (!empty($createdAt) && !empty($percentage)) {
                    $result[$key] = [
                        'id' => $id,
                        'createdAt' => date_format(date_create($createdAt), "Y-m-d"),
                        'percentage' => $percentage
                    ];
                }
            }
        }

        return $result;

    }


    public function calculateStepsPercentage(array $data): array
    {

        //Initialize Result Array
        $result = [];

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                //Get Total Count of User Percentages
                $totalPercentage = array_count_values($value);


                //Loop Step Percentages
                foreach ($this->stepPercentages as $sp) {

                    //Check Percentage value already exist in percentage array
                    if (isset($totalPercentage[$sp])) {

                        //Percentage Amount Calculation
                        $percentageAmount = ($totalPercentage[$sp] / count($value)) * 100;

                        //Round up percentage value and Assign it to Result Array
                        $result[$key][] = round(($percentageAmount));

                    } else {
                        //If sp value is not exist ,assign Zero
                        $result[$key][] = 0;
                    }
                }

                //Every 0 value should be 100 - (Chart x value should be) ,
                $result[$key][0] = 100;
            }
        }


        return $result;
    }


    public function getWeeklyBasedPercentage(array $data): array
    {

        $result = [];

        $nextWeekDate = null;
        $weekNumber = -1;

        foreach ($data as $key => $d) {

            $id = $d['id'];
            $createdAt = $d['createdAt'];
            $percentage = $d['percentage'];


            if ($nextWeekDate === null || (new DateTime($createdAt)) >= $nextWeekDate) {

                $nextWeekDate = new DateTime($createdAt);
                $nextWeekDate->modify('+7 day');
                $weekNumber++;


            }


            if (in_array($percentage, $this->stepPercentages)) {
                $result[$weekNumber][] = $percentage;
            }
        }


        return $result;
    }

    public function getChartCodes(array $userDataArr): array
    {
        $dataWeek = $this->getWeeklyBasedPercentage($userDataArr);

        $data = $this->calculateStepsPercentage($dataWeek);


        $result = [];
        for ($i = 0; $i < count($data); $i++) {
            $result[$i]['name'] = ($i + 1) . " weeks later";
            $result[$i]['data'] = $data[$i];
        }


        return $result;


    }

}