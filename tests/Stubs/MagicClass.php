<?php
declare(strict_types=1);

namespace DragonisCZ\PHPValueDumper\Tests\Stubs;

class MagicClass
{
    private array $args = [];

    public function __construct(
        string $string = 'foo',
        float $float = 3.14,
        int $int = 42,
    ) {
        $this->args = [
            'string' => $string,
            'float' => $float,
            'int' => $int,
        ];
    }

    public function __get(string $name)
    {
        return $this->args[$name] ?? null;
    }
}
