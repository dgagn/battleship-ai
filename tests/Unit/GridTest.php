<?php

use App\Ai\Grid;

test('grid', function () {
    $shots = collect([
        'A-1',
        'B-3',
        'C-4',
    ]);

    $shipSizes = collect([5, 4, 3, 3, 2]);
    $grid = new Grid($shipSizes, $shots);

    //dd($grid->targetmap(collect(['E-4', 'F-5', 'E-6', 'D-5']), collect(['E-4' => 5, 'F-5' => 10])));
});
