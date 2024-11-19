<?php

namespace App\Support\BinaryTree;

class BinaryNode
{
    public BinaryNode|null $left;
    public BinaryNode|null $right;

    public function __construct(public readonly string $name)
    {
    }
}
