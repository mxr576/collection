<?php

declare(strict_types=1);

namespace spec\loophp\collection\Operation;

use ArrayIterator;
use loophp\collection\Operation\Limit;
use PhpSpec\ObjectBehavior;

class LimitSpec extends ObjectBehavior
{
    public function it_can_set_a_limit()
    {
        $input = range('a', 'e');

        $iterator = new ArrayIterator($input);

        $this
            ->__invoke()(1)(0)($iterator)
            ->shouldHaveCount(1);

        $this
            ->__invoke()(2)(0)($iterator)
            ->shouldHaveCount(2);
    }

    public function it_can_set_an_offset()
    {
        $input = range('a', 'e');

        $iterator = new ArrayIterator($input);

        $this
            ->__invoke()(1)(2)($iterator)
            ->current()
            ->shouldReturn('c');

        $this
            ->__invoke()(2)(2)($iterator)
            ->shouldHaveCount(2);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Limit::class);
    }
}
