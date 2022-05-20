<?php

namespace GlimeshClientBuilder\Schema;

class SchemaEnumValue extends AbstractSchemaType
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description
    ) {}

    public static function createFromArray(array $schema): self
    {
        return new self(
            $schema['name'],
            $schema['description']
        );
    }
}
