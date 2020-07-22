<?php

declare(strict_types=1);

namespace loophp\collection\Operation;

use CallbackFilterIterator;
use Closure;
use Generator;
use Iterator;
use loophp\collection\Contract\Operation;

final class Filter extends AbstractOperation implements Operation
{
    public function __construct(callable ...$callbacks)
    {
        $defaultCallback = static function ($item): bool {
            return true === (bool) $item;
        };

        $this->storage['callbacks'] = [] === $callbacks ?
            [$defaultCallback] :
            $callbacks;
    }

    public function __invoke(): Closure
    {
        return
            /**
             * @param array<int, callable> $callbacks
             */
            static function (Iterator $iterator, array $callbacks): Generator {
                foreach ($callbacks as $callback) {
                    $iterator = new CallbackFilterIterator($iterator, $callback);
                }

                yield from $iterator;
            };
    }
}
