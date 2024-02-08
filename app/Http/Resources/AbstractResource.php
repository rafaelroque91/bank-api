<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AbstractResource extends JsonResource
{
    public function formatCurrencyToUser($number) : string
    {
        return number_format($number / 100,2);
    }
    public function formatDateToUser(?string $date) : ?string
    {
        return Carbon::make($date)?->format('Y-m-d H:i:sP');
    }
}
