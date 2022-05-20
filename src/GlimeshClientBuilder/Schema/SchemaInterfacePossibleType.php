<?php

namespace GlimeshClientBuilder\Schema;

class SchemaInterfacePossibleType extends AbstractSchemaType
{
    public static string $type = 'OBJECT';

    public function __construct(
        public readonly string $name,
        public readonly ?string $ofType
    ) {
    }

    public static function createFromArray(array $schema): self
    {
        return new self(
            $schema['name'],
            $schema['ofType']
        );
    }
}
