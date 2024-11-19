<?php

namespace App\Support\Graph;

use App\Support\Queues\InvertedPriorityQueue;

class Dijkstra
{
    public function __construct(public readonly Graph $graph)
    {
    }

    public function solve(Node $start, Node $end): int
    {
        $start->weight = 0;
        $queue = $this->graph->nodes;
        usort($queue, fn (Node $a, Node $b) => $b->weight - $a->weight);
        /** @var Node $cursor */
        $cursor = array_pop($queue);
        while ($cursor !== null) {
            if ($cursor->is($end)) {
                return $end->weight;
            }
            $cursor->visit();
            foreach ($cursor->nodes as $name => $neighbour) {
                if ($neighbour->visited === false) {
                    $neighbour->weight = $cursor->weight + $cursor->weights[$name];
                    $neighbour->previous = $cursor;
                }
            }
            usort($queue, fn (Node $a, Node $b) => $b->weight - $a->weight);
            $cursor = array_pop($queue);
        }

        return -1;
    }

    public function solveOptimised(Node $start, Node $end): int|null
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

            foreach($cursor->nodes as $name => $neighbour) {
                $neighbour->weight = $cursor->weight + $cursor->weights[$name];
                $neighbour->previous = $cursor;
                $queue->insert($neighbour, $neighbour->weight);
            }
        }

        return -1;
    }
}
