<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\collection\Contract\Operation;

use loophp\collection\Contract\Collection;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @template TKey of array-key
 * @template T
 */
interface Cacheable
{
    /**
     * @return \loophp\collection\Collection<TKey, T>
     */
    public function cache(?CacheItemPoolInterface $cache = null): Collection;
}
