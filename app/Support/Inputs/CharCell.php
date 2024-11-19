<?php

namespace App\Support\Inputs;
class CharCell
{
    public function __construct(
        public readonly string $value,
        public readonly int $x,
        public readonly int $y,
    )
    {
    }
}
