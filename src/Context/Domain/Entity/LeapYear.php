<?php

namespace App\Context\Domain\Entity;

class LeapYear
{
    public function isLeapYear(?int $year = null): bool
    {
        if (null === $year) {
            $year = (int)date('Y');
        }

        return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
    }
}