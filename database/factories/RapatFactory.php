<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RapatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(4),
            'pic_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'tanggal' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'waktu_mulai' => $waktuMulai = $this->faker->time('H:i:s'),
            'waktu_selesai' => $this->faker->time('H:i:s', strtotime($waktuMulai) + rand(3600, 14400)),
            'link_zoom' => $this->faker->url(),
            'lokasi' => $this->faker->address(),
            'status' => $this->faker->randomElement(['terjadwal', 'sedang berlangsung', 'selesai', 'dibatalkan']),
        ];
    }
}
