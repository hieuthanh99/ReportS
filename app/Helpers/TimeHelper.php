<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function getTimeParameters($type)
    {
        $today = Carbon::now();
        $result = [];

        switch ($type) {
            case 1: // Tuần
                $result = $today->weekOfYear;
                // $result['current'] = $today->weekOfYear;
                // $result['previous'] = $today->copy()->subWeek()->weekOfYear;
                // $result['two_previous'] = $today->copy()->subWeeks(2)->weekOfYear;
                break;

            case 2: // Tháng
                $result = $today->month;
                // $result['previous'] = $today->copy()->subMonth()->month;
                // $result['two_previous'] = $today->copy()->subMonths(2)->month;
                break;

            case 3: // Quý
                $result= $today->quarter;
                // $result['previous'] = $today->copy()->subQuarter()->quarter;
                // $result['two_previous'] = $today->copy()->subQuarters(2)->quarter;
                break;

            case 4: // Năm
                $result = $today->year;
                // $result['previous'] = $today->copy()->subYear()->year;
                // $result['two_previous'] = $today->copy()->subYears(2)->year;
                break;

            default:
                $result['error'] = 'Invalid type';
                break;
        }

        return $result;
    }
}
