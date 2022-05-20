<?php

namespace GlimeshClientBuilder\Schema;

class SchemaInterface extends AbstractSchemaType
{
    public static string $type = 'INTERFACE';

    public function __construct(
        public readonly string $name,
        public readonly ?string $ofType
    ) {}

    public static function createFromArray(array $schema): self
    {
        return new self(
            $schema['name'],
            $schema['ofType']
        );
    }
}
