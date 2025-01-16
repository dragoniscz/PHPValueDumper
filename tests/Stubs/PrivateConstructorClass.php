<?php

declare(strict_types=1);

namespace DragonisCZ\PHPValueDumper\Tests\Stubs;

class PrivateConstructorClass
{
    private function __construct()
    {
    }

    public static function generate(): self
    {
        return new PrivateConstructorClass();
    }
}
