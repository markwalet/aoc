<?php

namespace Tests\Year2024\Support;

use App\Support\Vectors\Vector2;

class Bot
{
    public function __construct(
        public Vector2 $position,
        public Vector2 $velocity,
    ) {
    }

    public function move(Vector2 $bounds): void
    {
        $this->position = $this->position->add($this->velocity)->mod($bounds);

        if ($this->position->x < 0) {
            $this->position->x += $bounds->x;
        }
        if ($this->position->y < 0) {
            $this->position->y += $bounds->y;
        }
    }
}
