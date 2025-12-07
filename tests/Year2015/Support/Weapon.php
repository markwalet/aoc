<?php

namespace Tests\Year2015\Support;

class Weapon
{
    public function __construct(
        public readonly int $cost,
        public readonly int $damage,
        public readonly int $armor
    )
    {
    }
}
