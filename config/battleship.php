<?php

return [
    'size' => 10,
    'weighting' => [
        'none' => -9999,
        'default' => 1,
        'increment' => 1,
        'shot' => 5,
        'stack' => 200,
        'direction' => 100,
    ],
    'direction' => [
        'horizontal' => 1,
        'vertical' => 2,
    ],
    'placement' => [
        'size' => 10,
    ],
    'result' => [
        'miss' => 0,
        'hit' => 1,
        'sunk.porte-avions' => 2,
        'sunk.cuirasse' => 3,
        'sunk.destroyer' => 4,
        'sunk.sous-marin' => 5,
        'sunk.patrouilleur' => 6,
    ],
];
