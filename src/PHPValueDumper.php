<?php

declare(strict_types=1);

namespace DragonisCZ\PHPValueDumper;

class PHPValueDumper
{
    /**
     * @throws UnableToDumpValueException
     */
    public static function dump(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        } elseif (\is_scalar($value)) {
            return self::dumpLiteral($value);
        } elseif (\is_array($value)) {
            return self::dumpArray($value);
        } elseif (\is_object($value)) {
            return self::dumpObject($value);
        } else {
            throw UnableToDumpValueException::notSupportedType(\gettype($value));
        }
    }

    private static function dumpLiteral(bool|int|float|string $value): string
    {
        return \var_export($value, true);
    }

    /**
     * @param array<array-key, mixed> $array
     *
     * @throws UnableToDumpValueException
     */
    private static function dumpArray(array $array): string
    {
        $parameters = [];
        foreach ($array as $key => $value) {
            $parameters[] = self::dumpLiteral($key) . '=>' . self::dump($value);
        }

        return '[' . \implode(',', $parameters) . ']';
    }

    /**
     * @throws UnableToDumpValueException
     */
    private static function dumpObject(object $object): string
    {
        $reflection = new \ReflectionClass($object);
        $className = $reflection->getName();

        $constructor = $reflection->getConstructor();
        if ($constructor === null) {
            return "new {$className}()";
        } elseif (!$constructor->isPublic()) {
            throw UnableToDumpValueException::constructorMissing($className);
        }

        $arguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();

            try {
                $value = $reflection->getProperty($name)->getValue($object);
            } catch (\ReflectionException) {
                // Maybe we can utilize magic method.
                $value = $object->{$name};
            }

            $arguments[] = self::dump($value);
        }

        return "new {$className}(" . \implode(',', $arguments) . ')';
    }
}
