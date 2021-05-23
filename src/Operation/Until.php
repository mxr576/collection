<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace loophp\collection\Operation;

use Closure;
use Generator;
use Iterator;

/**
 * @template TKey of array-key
 * @template T
 *
 * phpcs:disable Generic.Files.LineLength.TooLong
 */
final class Until extends AbstractOperation
{
    /**
     * @return Closure(callable(T, TKey, Iterator<TKey, T>): bool ...): Closure(Iterator<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @psalm-param callable(T, TKey, Iterator<TKey, T>): bool ...$callbacks
             *
             * @return Closure(Iterator<TKey, T>): Generator<TKey, T>
             */
            static fn (callable ...$callbacks): Closure =>
                /**
                 * @psalm-param Iterator<TKey, T> $iterator
                 *
                 * @return Generator<TKey, T>
                 */
                static function (Iterator $iterator) use ($callbacks): Generator {
                    $reducerCallback =
                        /**
                         * @param mixed $key
                         * @psalm-param TKey $key
                         *
                         * @return Closure(T): Closure(Iterator<TKey, T>): Closure(bool, callable(T, TKey, Iterator<TKey, T>): bool): bool
                         */
                        static fn ($key): Closure =>
                            /**
                             * @param mixed $current
                             * @psalm-param T $current
                             *
                             * @return Closure(Iterator<TKey, T>): Closure(bool, callable(T, TKey, Iterator<TKey, T>): bool): bool
                             */
                            static fn ($current): Closure =>
                                /**
                                 * @psalm-param Iterator<TKey, T> $iterator
                                 *
                                 * @return Closure(bool, callable(T, TKey, Iterator<TKey, T>): bool): bool
                                 */
                                static fn (Iterator $iterator): Closure =>
                                    /**
                                     * @psalm-param bool $carry
                                     * @psalm-param callable(T, TKey, Iterator<TKey, T>): bool $callable
                                     */
                                    static fn (bool $carry, callable $callable): bool => $carry || $callable($current, $key, $iterator);

                    foreach ($iterator as $key => $current) {
                        yield $key => $current;

                        $result = array_reduce(
                            $callbacks,
                            $reducerCallback($key)($current)($iterator),
                            false
                        );

                        if (true === $result) {
                            break;
                        }
                    }
                };
    }
}
