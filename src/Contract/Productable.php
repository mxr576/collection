<?php

declare(strict_types=1);

namespace loophp\collection\Contract;

/**
 * Interface Productable.
 */
interface Productable
{
    /**
     * Get the the cartesian product of items of a collection.
     *
     * @param iterable ...$iterables
     *
     * @return \loophp\collection\Contract\Collection<mixed>
     */
    public function product(iterable ...$iterables): Base;
}
