<?php

namespace App\Repositories\OnboardProcess;

use Illuminate\Support\Facades\Storage;
use DateTime;
class OnboardProcessRepository implements OnboardProcessInterface
{

    private $steps = [0, 20, 40, 50, 70, 90, 99, 100];


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


    public function findStepsPercentage(array $data): array
    {

        $result = [];

        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $values = array_count_values($value);
                foreach ($this->steps as $step) {
                    if (isset($values[$step])) {
                        $result[$key][] = round(($values[$step] * 100) / count($value));
                    } else {
                        $result[$key][] = 0;
                    }
                }
                $result[$key][0] = 100;
            }
        }

        return $result;
    }


    public function divideByWeek(array $data): array
    {

        $result = [];

        $nextWeekDay = null;
        $weekNumber = -1;

        foreach ($data as $key => $d) {

            $id = $d['id'];
            $createdAt = $d['createdAt'];
            $percentage = $d['percentage'];


            if ($nextWeekDay === null || (new DateTime($createdAt)) >= $nextWeekDay) {

                $nextWeekDay = new DateTime($createdAt);
                $nextWeekDay->modify('+7 day');
                $weekNumber++;


            }


            if (in_array($percentage, $this->steps)) {
                $result[$weekNumber][] = $percentage;
            }
        }


        return $result;
    }

    public function getChartCodes(array $userDataArr)
    {
        $dataWeek = $this->divideByWeek($userDataArr);

        $data = $this->findStepsPercentage($dataWeek);


        $result = [];
        for ($i = 0; $i < count($data); $i++) {
            $result[$i]['name'] = ($i + 1) . " weeks later";
            $result[$i]['data'] = $data[$i];
        }


        return $result;


    }

}