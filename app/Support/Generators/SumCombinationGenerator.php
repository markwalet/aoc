<?php

namespace App\Support\Generators;

use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class SumCombinationGenerator
{
    private bool $repeat = false;

    /**
     * @param array<int, int> $items
     */
    public function __construct(public readonly array $items, public readonly int $sum)
    {
    }

    /**
     * @param array<int, int>|Collection<int, int> $items
     * @param int $sum
     * @return SumCombinationGenerator
     */
    public static function for(array|Collection $items, int $sum): SumCombinationGenerator
    {
        $items = is_array($items) ? array_values($items) : $items->values()->toArray();

        return new self($items, $sum);
    }

    /**
     * Allow for repeating values.
     *
     * @param bool $repeat
     * @return SumCombinationGenerator
     */
    public function repeat(bool $repeat = true): SumCombinationGenerator
    {
        $this->repeat = $repeat;

        return $this;
    }

    /**
     * @return LazyCollection<int, int>
     */
    public function generate(): LazyCollection
    {
        return LazyCollection::make(function () {
            foreach($this->_generate($this->items, $this->sum) as $item) {
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
    private function _generate(array $options, int $goal, array $result = []): Generator
    {
        if ($goal === 0) {
            yield $result;
        } elseif ($goal > 0 && count($options) > 0) {
            $key = array_key_first($options);
            $option = $options[$key];
            if ($this->repeat === false) {
                unset($options[$key]);
            }

            yield from $this->_generate($options, $goal, $result);
            $result[] = $key;
            yield from $this->_generate($options, $goal - $option, $result);
        }
    }
}
