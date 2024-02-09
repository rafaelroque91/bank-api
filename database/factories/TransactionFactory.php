<?php

namespace Database\Factories;


use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition()
    {
        $status = $this->faker->numberBetween(0,5);
        return [
            'sender_id' => Account::factory()->create()->id,
            'receiver_id' => Account::factory()->create()->id,
            'amount' => $this->faker->numberBetween(1,99999),
            'status' => $status,
            'scheduled_to' => $this->getScheduledDateByStatus($status),
            'error' => $this->getErrorMessageByStatus($status)
        ];
    }

    private function getErrorMessageByStatus(int $status) : ?string
    {
        return match ($status) {
            3 => 'There are no funds',
            4 => 'Unauthorized Transaction',
            5 => 'Unable to charge',
            default => null
        };
    }

    private function getScheduledDateByStatus(int $status) : ?\DateTime
    {
        return match ($status) {
            2 => $this->faker->dateTimeBetween('+1 day', '+10 days'),
            default => null
        };
    }
}
