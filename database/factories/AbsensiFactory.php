<?php

namespace Database\Factories;

use App\Models\Rapat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rapat_id' => Rapat::inRandomOrder()->first()->id ?? Rapat::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'kehadiran' => $this->faker->randomElement(['hadir', 'tidak hadir', 'izin']),
        ];
    }
}
