<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->text(25),
            'balance' => $this->faker->numberBetween(1,99999),
        ];
    }
}
