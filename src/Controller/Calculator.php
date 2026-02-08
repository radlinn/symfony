<?php

namespace App\Controller;

class Calculator
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}