<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

/**
 * @template TKey of array-key
 * @template T
 */
interface Currentable
{
    /**
     * @return T
     */
    public function current(int $index = 0);
}
