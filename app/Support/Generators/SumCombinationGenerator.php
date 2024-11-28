<?php

namespace App\Support\Generators;

use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class SumCombinationGenerator
{
    /**
     * @param array<int, int> $items
     */
    public function __construct(public readonly array $items, public readonly int $sum)
    {
    }

    /**
     * @param array<int, int>|Collection<int, int> $items
     * @return SumCombinationGenerator
     */
    public static function for(array|Collection $items, int $sum): SumCombinationGenerator
    {
        $items = is_array($items) ? array_values($items) : $items->values()->toArray();


        return new self($items, $sum);
    }

    /**
     * @return LazyCollection<int, int>
     */
    public function generate(): LazyCollection
    {
        return LazyCollection::make(function () {
            foreach($this->_generate($this->items) as $item) {
                yield $item;
            }
        });
    }

    /**
     * Internal generate function to make recursion easier.
     *
     * @param array $options
     * @param array $result
     * @param int $limit
     * @return Generator
     */
    private function _generate(array $options, array $result = []): Generator
    {
        if (array_sum($result) === $this->sum) {
            yield $result;
        } elseif (count($options) > 0) {
            $option = array_shift($options);

            yield from $this->_generate($options, $result);
            $result[] = $option;
            yield from $this->_generate($options, $result);
        }
    }
}
