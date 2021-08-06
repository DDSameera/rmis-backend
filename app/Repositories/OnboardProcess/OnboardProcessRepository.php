<?php


namespace App\Repositories\OnboardProcess;


class OnboardProcessRepository implements OnboardProcessInterface
{

    private $steps = [0, 20, 40, 50, 70, 90, 99, 100];


    public function getChartCodes(array $userDataArr)
    {
        $data = $this->divideByWeek($userDataArr);
        //  $data = $this->findStepsPercentage($data);


        $result = [];
        for ($i = 0; $i < count($data); $i++) {
            $result[$i]['name'] = ($i + 1) . " weeks later";
            $result[$i]['data'] = $data[$i];
        }

        $result = json_encode($result);
        return view('welcome', compact('result'));


    }

    public function findStepsPercentage(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
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


            if ($nextWeekDay === null || (new \DateTime($createdAt)) >= $nextWeekDay) {

                $nextWeekDay = new \DateTime($createdAt);
                $nextWeekDay->modify('+7 day');
                $weekNumber++;


            }


            if (in_array($percentage, $this->steps)) {
                $result[$weekNumber][] = $percentage;
            }
        }


        return $result;
    }


}