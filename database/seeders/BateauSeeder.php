<?php

namespace Database\Seeders;

use App\Models\Bateau;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BateauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boats = [
            'porte-avions' => 5,
            'cuirasse' => 4,
            'destroyer' => 3,
            'sous-marin' => 3,
            'patrouilleur' => 2,
        ];

        foreach ($boats as $boat => $size) {
            Bateau::query()->create([
                'name' => $boat,
                'size' => $size,
            ]);
        }
    }
}
