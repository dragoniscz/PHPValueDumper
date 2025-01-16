<?php

declare(strict_types=1);

namespace DragonisCZ\PHPValueDumper;

class UnableToDumpValueException extends \Exception
{
    public static function notSupportedType(string $type): self
    {
        return new self("Type '$type' is not supported.");
    }

    public static function constructorMissing(string $className): self
    {
        return new self("Class '$className' without public constructor is not supported.");
    }
}
