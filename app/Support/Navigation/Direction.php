<?php

namespace App\Support\Navigation;

use App\Support\Vectors\Vector2;

enum Direction
{
    case UP;
    case RIGHT;
    case DOWN;
    case LEFT;

    public function turnRight(): Direction
    {
        return match($this) {
            self::UP => self::RIGHT,
            self::RIGHT =>self::DOWN,
            self::DOWN => self::LEFT,
            self::LEFT => self::UP,
        };
    }

    public function turnLeft(): Direction
    {
        return match($this) {
            self::UP => self::LEFT,
            self::RIGHT =>self::UP,
            self::DOWN => self::RIGHT,
            self::LEFT => self::DOWN,
        };
    }

    public function vector(): Vector2
    {
        return match($this) {
            self::UP => new Vector2(0, -1),
            self::RIGHT => new Vector2(1, 0),
            self::DOWN => new Vector2(0, 1),
            self::LEFT => new Vector2(-1, 0),
        };
    }
}
