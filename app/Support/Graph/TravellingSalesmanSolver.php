<?php

namespace App\Support\Graph;

use Generator;

class TravellingSalesmanSolver
{
    public function __construct(public readonly Graph $graph)
    {
    }

    /**
     * Get the shortest route that is needed to visit all nodes once.
     *
     * @param Node|null $start
     * @return int
     */
    public function shortestRoute(Node|null $start = null): int
    {
        $nodes = $this->getStartNodes($start);
        $score = PHP_INT_MAX;

        foreach ($nodes as $node) {
            $this->resetWeights();
            foreach ($this->weights($node) as $weight) {
                $score = min($score, $weight);
            }
        }

        return $score;
    }

    /**
     * Get the longest route that is needed to visit all nodes once.
     *
     * @param Node|null $start
     * @return int
     */
    public function longestRoute(Node|null $start = null): int
    {
        $nodes = $this->getStartNodes($start);
        $score = PHP_INT_MIN;

        foreach ($nodes as $node) {
            $this->resetWeights();
            foreach ($this->weights($node) as $weight) {
                $score = max($score, $weight);
            }
        }

        return $score;
    }

    /**
     * Get a list of nodes to start from.
     *
     * @param Node|null $start
     * @return Node[]
     */
    private function getStartNodes(Node|null $start = null): array
    {
        return $start === null
            ? $this->graph->nodes
            : [$start];
    }

    /**
     * Set all weights to the graph to 0.
     *
     * @return void
     */
    private function resetWeights(): void
    {
        foreach ($this->graph->nodes as $node) {
            $node->weight = 0;
        }
    }

    /**
     * Generate a list of weights that are travelled to visit all nodes.
     *
     * @param Node $cursor
     * @return Generator
     */
    private function weights(Node $cursor): Generator
    {
        $cursor->visited = true;

        if (count(array_filter($cursor->graph()->nodes, fn (Node $node) => $node->visited === false)) === 0) {
            yield $cursor->weight;
        }

        foreach ($cursor->nodes as $name => $neighbour) {
            if ($neighbour->visited) {
                continue;
            }

            $neighbour->weight = $cursor->weight + $cursor->weights[$name];
            $neighbour->previous = $cursor;

            yield from $this->weights($neighbour);
        }
        $cursor->visited = false;
    }
}
