<?php

namespace App\Support\BinaryTree;

class BinaryTree
{
    public BinaryNode|null $current;

    private array $nodeMap;

    public function __construct()
    {
        $this->nodeMap = [];
    }

    public function add(BinaryNode $node): void
    {
        $this->nodeMap[$node->name] = $node;
    }

    public function get(string $name): BinaryNode
    {
        return $this->nodeMap[$name];
    }

    public function go(BinaryNode $node): void
    {
        $this->current = $node;
    }

    public function goLeft(): void
    {
        $this->go($this->current->left);
    }

    public function goRight(): void
    {
        $this->go($this->current->right);
    }
}
