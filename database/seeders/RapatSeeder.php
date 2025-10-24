<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rapat;
use App\Models\Notulen;
use App\Models\Absensi;
use App\Models\User;

class RapatSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() < 5) {
            User::factory(5)->create();
        }

        $rapats = Rapat::factory(10)->create();

        foreach ($rapats as $rapat) {

            Notulen::factory()->create([
                'rapat_id' => $rapat->id,
            ]);

            $users = User::inRandomOrder()->take(rand(5, 10))->get();
            foreach ($users as $user) {
                Absensi::factory()->create([
                    'rapat_id' => $rapat->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
