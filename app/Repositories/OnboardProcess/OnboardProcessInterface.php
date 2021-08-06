<?php

namespace App\Repositories\OnboardProcess;

interface OnboardProcessInterface
{
    public function getChartCodes(array $userDataArr);

    public function findStepsPercentage(array $data): array;

    public function divideByWeek(array $data): array;
}