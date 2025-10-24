<?php

namespace Database\Factories;

use App\Models\Rapat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotulenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ringkasan' => $this->faker->paragraph(5),
            'lampiran_file' => $this->faker->filePath(),
            'rapat_id' => Rapat::inRandomOrder()->first()->id ?? Rapat::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
