<?php

namespace Tests\Year2025;

use App\Support\Graph\Graph;
use App\Support\Graph\Node;
use App\Support\UnionFind;
use App\Support\Vectors\Vector3;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day8Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_8_example(): void
    {
        ini_set('memory_limit', '-1');
        $graph = $this->graph($this->lines('example'));
        $resultA = $this->solve($graph, false, 10);
        $resultB = $this->solve($graph, true);

        $this->assertEquals(40, $resultA);
        $this->assertEquals(25272, $resultB);
    }

    #[Test]
    public function it_can_solve_day_8(): void
    {
        ini_set('memory_limit', '-1');
        $graph = $this->graph($this->lines());
        $resultA = $this->solve($graph, false, 1000);
        $resultB = $this->solve($graph, true);

        $this->assertEquals(32103, $resultA);
        $this->assertEquals(8133642976, $resultB);
    }

    private function graph(Collection $lines): Graph
    {
        /** @var Collection<int, Node> $parsed */
        $parsed = $lines->map(fn (string $line) => new Node($line, new Vector3(...explode(',', $line))));

        for ($i = 0; $i < $parsed->count() - 1; $i++) {
            for ($j = $i + 1; $j < $parsed->count(); $j++) {
                $parsed[$i]->addNeighbour($parsed[$j], $parsed[$i]->value->euclideanDistance($parsed[$j]->value));
            }
        }
        $graph = new Graph();
        foreach ($parsed as $node) {
            $graph->addNode($node);
        }

        return $graph;
    }

    private function solve(Graph $graph, bool $isPartTwo, int $connectionsToMake = 0): int
    {
        $uf = new UnionFind();
        $nodes = $graph->all();

        foreach ($nodes as $node) {
            $uf->makeSet($node->name);
        }

        $distances = [];
        foreach ($nodes as $node) {
            foreach ($node->nodes as $neighbour) {
                $distances[] = ['dist' => $node->weights[$neighbour->name], 'pair' => [$node, $neighbour]];
            }
        }

        usort($distances, static fn ($a, $b) => $a['dist'] <=> $b['dist']);

        if ($isPartTwo) {
            foreach ($distances as $item) {
                /** @var Node $node1 */
                /** @var Node $node2 */
                [$node1, $node2] = $item['pair'];

                $uf->union($node1->name, $node2->name);

                if ($uf->getSetCount() === 1) {
                    return $node1->value->x * $node2->value->x;
                }
            }

            return 0;
        }

        $pairsToConnect = array_slice($distances, 0, $connectionsToMake);

        foreach ($pairsToConnect as $item) {
            /** @var Node $node1 */
            /** @var Node $node2 */
            [$node1, $node2] = $item['pair'];

            $uf->union($node1->name, $node2->name);
        }

        $sizes = $uf->getSizes();
        rsort($sizes);

        if (count($sizes) < 3) {
            return array_reduce($sizes, static fn ($carry, $item) => $carry * $item, 1);
        }

        return $sizes[0] * $sizes[1] * $sizes[2];
    }
}
