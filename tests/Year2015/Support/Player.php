<?php

namespace Tests\Year2015\Support;

class Player
{
    public function __construct(
        public int $health,
        public int $damage,
        public int $armor
    )
    {
    }
}
