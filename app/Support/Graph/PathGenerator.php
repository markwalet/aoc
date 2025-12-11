<?php

namespace App\Support\Graph;

use Generator;

readonly class PathGenerator
{
    public function __construct(public Graph $graph)
    {
    }

    public function all(Node $start, Node $end, array $path = []): Generator
    {
        if ($start->is($end)) {
            yield $path;
        } else {
            $start->visit();
            foreach ($start->nodes as $n) {
                if ($n->visited) {
                    continue;
                }
                yield from $this->all($n, $end, [...$path, $start]);
            }
            $start->unvisit();
        }
    }

    public function count(Node $start, Node $end, array &$cache = []): int
    {
        if ($start->is($end)) {
            return 1;
        }

        if (isset($cache[$start->name])) {
            return $cache[$start->name];
        }

        $start->visit();
        $count = 0;
        foreach ($start->nodes as $n) {
            if ($n->visited) {
                continue;
            }
            $count += $this->count($n, $end, $cache);
        }
        $start->unvisit();
        $cache[$start->name] = $count;

        return $count;
    }
}
