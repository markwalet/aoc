<?php

namespace App\Support\Graph;

use AllowDynamicProperties;

class Node
{
    /** @var int[] */
    public array $weights;

    /** @var Node[] */
    public array $nodes;
    public bool $visited;

    public int $weight;
    public Node|null $previous;
    private Graph $graph;

    public function __construct(public readonly string $name, public readonly string|null $value = null)
    {
        $this->nodes = [];
        $this->weights = [];
        $this->visited = false;
        $this->weight = PHP_INT_MAX;
        $this->previous = null;
    }

    public function is(Node $node): bool
    {
        return $node->name === $this->name;
    }

    public function addNeighbour(Node $node, int $weight = 1): void
    {
        $this->weights[$node->name] = $weight;
        $this->nodes[$node->name] = $node;
    }

    public function visit(): void
    {
        $this->visited = true;
    }

    public function setGraph(Graph $graph): void
    {
        $this->graph = $graph;
    }

    public function graph(): Graph
    {
        return $this->graph;
    }
}
