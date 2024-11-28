<?php

namespace App\Support\Generators;

use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class PermutationGenerator
{
    private int|null $length;

    /**
     * @param array<int, scalar> $items
     */
    public function __construct(public readonly array $items)
    {
        $this->length = null;
    }

    /**
     * @param array<int, scalar> $items
     * @return PermutationGenerator
     */
    public static function for(array $items): PermutationGenerator
    {
        return new self(array_values($items));
    }

    /**
     * Set the length for a permutation.
     *
     * @param int $length
     * @return self
     */
    public function length(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return LazyCollection<int, scalar>
     */
    public function generate(): LazyCollection
    {
        return LazyCollection::make(function () {
            foreach($this->_generate($this->items, limit: $this->length ?? count($this->items)) as $item) {
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
    private function _generate(array $options, array $result = [], int $limit = 0): Generator
    {
        if ($limit === 0) {
            yield $result;
        } else {
            for ($i = 0; $i < count($options); $i++) {
                $result[] = $options[$i];

                yield from $this->_generate(array_values(array_diff_key($options, [$i => 0])), $result, $limit - 1);

                array_pop($result);
            }
        }
    }
}
