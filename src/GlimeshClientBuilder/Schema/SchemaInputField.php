<?php

namespace GlimeshClientBuilder\Schema;

class SchemaInputField extends AbstractSchemaType
{
    public function __construct(
        public readonly ?string $defaultValue,
        public readonly ?string $description,
        public readonly string $name,
        public readonly SchemaType $type
    ) {
    }

    public static function createFromArray(array $schema): self
    {
        return new self(
            $schema['defaultValue'],
            $schema['description'],
            $schema['name'],
            SchemaType::createFromArray($schema['type'])
        );
    }
}
