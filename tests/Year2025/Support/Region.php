<?php

namespace Tests\Year2025\Support;

use App\Support\Inputs\CharMap;
use App\Support\Vectors\Vector2;

class Region
{
    public readonly int $size;

    public readonly CharMap $map;

    public function __construct(public readonly int $width, public readonly int $height, public readonly array $shapeRequirements)
    {
        $this->size = $width * $height;
        $this->map = CharMap::fromSize($width, $height, false);
    }
}
