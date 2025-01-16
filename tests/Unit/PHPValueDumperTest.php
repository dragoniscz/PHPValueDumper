<?php

declare(strict_types=1);

namespace DragonisCZ\PHPValueDumper\Tests\Unit;

use DragonisCZ\PHPValueDumper\PHPValueDumper;
use DragonisCZ\PHPValueDumper\Tests\Stubs\CompoundClass;
use DragonisCZ\PHPValueDumper\Tests\Stubs\IntegerClass;
use DragonisCZ\PHPValueDumper\Tests\Stubs\MagicClass;
use DragonisCZ\PHPValueDumper\Tests\Stubs\NoConstructorClass;
use DragonisCZ\PHPValueDumper\Tests\Stubs\PrivateConstructorClass;
use DragonisCZ\PHPValueDumper\UnableToDumpValueException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PHPValueDumperTest extends TestCase
{
    #[DataProvider('data')]
    public function testDump(?string $expectedOutput, mixed $value): void
    {
        if ($expectedOutput !== null) {
            try {
                static::assertSame($expectedOutput, PHPValueDumper::dump($value));
            } catch (UnableToDumpValueException) {
                static::fail('UnableToDumpValueException was caught.');
            }
        } else {
            static::expectException(UnableToDumpValueException::class);
            PHPValueDumper::dump($value);
        }
    }

    /**
     * @return array<string, array{0: ?string, 1: mixed}>
     */
    public static function data(): array
    {
        // @phpstan-ignore-next-line return.type
        return [
            'null' => ['null', null],
            'true' => ['true', true],
            'false' => ['false', false],
            '0' => ['0', 0],
            '1' => ['1', 1],
            '42' => ['42', 42],
            '0.0' => ['0.0', 0.0],
            '1.0' => ['1.0', 1.0],
            '3.14' => ['3.14', 3.14],
            '3.28e+80' => ['3.28E+80', 3.28e+80],
            '""' => ['\'\'', ''],
            '"abc"' => ['\'abc\'', 'abc'],
            '[]' => ['[]', []],
            '["x", "y"]' => ['[0=>\'x\',1=>\'y\']', ['x', 'y']],
            '["x", 2 => "y"]' => ['[0=>\'x\',2=>\'y\']', ['x', 2 => 'y']],
            '["x" => [], 2 => "y"]' => ['[\'x\'=>[],2=>\'y\']', ['x' => [], 2 => 'y']],
            'NoConstructorClass' => ['new ' . NoConstructorClass::class . '()', new NoConstructorClass()],
            'IntegerClass' => ['new ' . IntegerClass::class . '(42)', new IntegerClass(42)],
            'CompoundClass' => ['new ' . CompoundClass::class . '(new ' . IntegerClass::class . '(7),\'foo\',3.14,false,[],null)', new CompoundClass(new IntegerClass(7))],
            'MagicClass' => ['new ' . MagicClass::class . '(\'foo\',3.14,42)', new MagicClass()],
            'no constructor' => [null, PrivateConstructorClass::generate()],
            'unsupported type' => [null, STDIN],
        ];
    }
}
