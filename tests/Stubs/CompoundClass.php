<?php

declare(strict_types=1);

namespace DragonisCZ\PHPValueDumper\Tests\Stubs;

class CompoundClass
{
    public function __construct(
        public IntegerClass $int,
        public string $string = 'foo',
        public float $float = 3.14,
        public bool $bool = false,
        public array $array = [],
        public ?IntegerClass $nullable = null,
    ) {
    }
}
