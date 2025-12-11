<?php

namespace Tests\Year2025;

use App\Support\Graph\Graph;
use App\Support\Graph\Node;
use App\Support\Graph\PathGenerator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day11Test extends TestCase
{
    #[Test]
    public function it_can_solve_day_11a(): void
    {
        $pathCount = $this->countPaths('you', 'out');

        $this->assertEquals(749, $pathCount);
    }

    #[Test]
    public function it_can_solve_day_11b(): void
    {
        $a = $this->countPaths('svr', 'fft', ['out', 'dac']);
        $b = $this->countPaths('fft', 'dac', ['out', 'svr']);
        $c = $this->countPaths('dac', 'out', ['fft', 'svr']);
        $d = $this->countPaths('svr', 'dac', ['out', 'fft']);
        $e = $this->countPaths('dac', 'fft', ['out', 'svr']);
        $f = $this->countPaths('fft', 'out', ['dac', 'svr']);

        $this->assertEquals(420257875695750, $a * $b * $c + $d * $e * $f);
    }

    private function countPaths(string $start, string $end, array $exclude = []): int
    {
        $graph = $this->parse($exclude);
        $start = $graph->get($start);
        $end = $graph->get($end);
        $generator = new PathGenerator($graph);

        return $generator->count($start, $end);
    }

    private function parse(array $exclude): Graph
    {
        $graph = new Graph();
        $lines = $this->lines();
        $lines->each(function (string $line) use (&$graph, &$exclude) {
            [$label,] = explode(': ', $line);
            if (in_array($label, $exclude)) {
                return;
            }
            $node = new Node($label);
            $graph->addNode($node);
        });
        $graph->addNode(new Node('out'));
        $lines->each(function (string $line) use (&$graph, &$exclude) {
            [$label, $neighbours] = explode(': ', $line);
            if (in_array($label, $exclude)) {
                return;
            }
            $neighbours = explode(' ', $neighbours);
            foreach ($neighbours as $n) {
                if (in_array($n, $exclude)) {
                    continue;
                }
                $graph->get($label)->addNeighbour($graph->get($n));
            }
        });

        return $graph;
    }
}
