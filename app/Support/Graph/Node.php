<?php

namespace App\Support\Graph;

use AllowDynamicProperties;
use App\Support\Vectors\Vector2;
use App\Support\Vectors\Vector3;

class Node
{
    /** @var float[] */
    public array $weights;

    /** @var Node[] */
    public array $nodes;
    public bool $visited;

    public int $weight;
    public Node|null $previous;
    private Graph $graph;

    public function __construct(public readonly string $name, public readonly string|Vector2|Vector3|null $value = null)
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

    public function addNeighbour(Node $node, float $weight = 1): void
    {
        $this->weights[$node->name] = $weight;
        $this->nodes[$node->name] = $node;
    }

    public function removeNeighbour(Node $node): void
    {
        unset($this->weights[$node->name]);
        unset($this->nodes[$node->name]);
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
