<?php

namespace App\Repositories\Dto;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractDto implements Arrayable
{
    public function all(): array
    {
        return get_object_vars($this);
    }

    public function toArray(): array
    {
        return $this->all();
    }

    public static function formatCurrencyToDB($number) : int
    {
        return $number * 100;
    }

    public static function formatCurrencyToUser($number) : float
    {
        return number_format($number / 100,2);
    }
}
