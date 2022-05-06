<?php

namespace Database\Seeders;

use App\Models\Boat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Creates the boats for the battleship game.
 *
 * @author Dany Gagnon
 */
class BoatSeeder extends Seeder
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
            Boat::query()->create([
                'name' => $boat,
                'size' => $size,
            ]);
        }
    }
}
