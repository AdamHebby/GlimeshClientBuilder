<?php

namespace GlimeshClientBuilder\Schema;

abstract class AbstractSchemaType
{
    abstract public static function createFromArray(array $schema): self;

    public static function createMultipleFromArray(array $multipleOfType): array
    {
        return array_map(
            fn(array $schema) => static::createFromArray($schema),
            $multipleOfType
        );
    }
}
