<?php

namespace common\apiClass;

trait Month
{
    public function getMonth(): array
    {
        return [
            1 => 'JANUARY',
            'FEBRUARY',
            'MARCH',
            'APRIL',
            'MAY',
            'JUNE',
            'JULY',
            'AUGUST',
            'SEPTEMBER',
            'OCTOBER',
            'NOVEMBER',
            'DECEMBER',
        ];
    }
}