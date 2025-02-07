<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use ArrayIterator;
use Closure;
use Exception;
use Generator;
use loophp\collection\Contract\Operation;

/**
 * @immutable
 *
 * @template TKey
 * @template T
 */
final class Sort extends AbstractOperation
{
    /**
     * @return Closure(int): Closure(null|(callable(T|TKey, T|TKey): int)): Closure(iterable<TKey, T>): Generator<TKey, T>
     */
    public function __invoke(): Closure
    {
        return
            /**
             * @return Closure(null|(callable(T|TKey, T|TKey): int)): Closure(iterable<TKey, T>): Generator<TKey, T>
             */
            static fn (int $type = Operation\Sortable::BY_VALUES): Closure =>
                /**
                 * @param null|(callable(T|TKey, T|TKey): int) $callback
                 *
                 * @return Closure(iterable<TKey, T>): Generator<TKey, T>
                 */
                static function (?callable $callback = null) use ($type): Closure {
                    $callback ??=
                        /**
                         * @param T|TKey $left
                         * @param T|TKey $right
                         */
                        static fn (mixed $left, mixed $right): int => $left <=> $right;

                    return
                        /**
                         * @param iterable<TKey, T> $iterable
                         *
                         * @return Generator<TKey, T>
                         */
                        static function (iterable $iterable) use ($type, $callback): Generator {
                            if (Operation\Sortable::BY_VALUES !== $type && Operation\Sortable::BY_KEYS !== $type) {
                                throw new Exception('Invalid sort type.');
                            }

                            $operations = Operation\Sortable::BY_VALUES === $type ?
                                [
                                    'before' => [(new Pack())()],
                                    'after' => [(new Unpack())()],
                                ] :
                                [
                                    'before' => [(new Flip())(), (new Pack())()],
                                    'after' => [(new Unpack())(), (new Flip())()],
                                ];

                            $sortCallback =
                                /**
                                 * @param callable(T|TKey, T|TKey): int $callback
                                 *
                                 * @return Closure(array{0:TKey|T, 1:T|TKey}, array{0:TKey|T, 1:T|TKey}): int
                                 */
                                static fn (callable $callback): Closure =>
                                    /**
                                     * @param array{0:TKey|T, 1:T|TKey} $left
                                     * @param array{0:TKey|T, 1:T|TKey} $right
                                     */
                                    static fn (array $left, array $right): int => $callback($left[1], $right[1]);

                            /** @var callable(iterable<TKey, T>): Generator<int, array{0:TKey, 1:T}> | callable(iterable<TKey, T>): Generator<int, array{0:T, 1:TKey}> $before */
                            $before = (new Pipe())()(...$operations['before']);

                            $arrayIterator = new ArrayIterator([...$before($iterable)]);
                            $arrayIterator->uasort($sortCallback($callback));

                            yield from (new Pipe())()(...$operations['after'])($arrayIterator);
                        };
                };
    }
}
