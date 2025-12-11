<?php

namespace App\Support\Graph;

use App\Support\Queues\InvertedPriorityQueue;
use Illuminate\Support\Collection;

class Graph
{
    public array $nodes;
    public function __construct()
    {
        $this->nodes = [];
    }

    public function addNode(Node $node): void
    {
        $this->nodes[$node->name] = $node;
        $node->setGraph($this);
    }

    public function get(string $name): Node|null
    {
        return $this->has($name) ? $this->nodes[$name] : null;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->nodes);
    }

    public function all(): array
    {
        return $this->nodes;
    }

    public function collect(): Collection
    {
        return collect($this->all());
    }

    public function dijkstraTwist(Node $start, Node $end, int $max): int|null
    {
        $queue = new InvertedPriorityQueue();
        $queue->insert($start, $start->weight);

        while($queue->count() > 0) {
            /** @var Node $cursor */
            $cursor = $queue->extract();
            if ($cursor->is($end)) {
                return $end->weight;
            }
            if ($cursor->visited) {
                continue;
            }
            $cursor->visit();
            $train = [];

            $u = $cursor;
            for($i = 0; $i < $max; $i++) {
                $u = $train[] = $u?->previous;
            }

            foreach($cursor->nodes as $name => $neighbour) {
                $neighbour->weight = $cursor->weight + $cursor->weights[$name];
                $neighbour->previous = $cursor;
                $queue->insert($neighbour, $neighbour->weight);
            }
        }
    }

    public function dd()
    {
        dd(collect($this->nodes)->map(fn (Node $n) => $n->name .': ('.collect($n->nodes)->pluck('name')->implode(', ').')'));
    }
}
