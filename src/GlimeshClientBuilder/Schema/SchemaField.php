<?php

namespace GlimeshClientBuilder\Schema;

class SchemaField extends AbstractSchemaType
{
    /**
     * @param SchemaFieldArg[]  $args
     */
    public function __construct(
        public readonly array $args,
        public readonly ?string $description,
        public readonly ?string $name,
        public readonly ?SchemaType $type
    ) {
    }

    public static function createFromArray(array $schema): self
    {
        return new self(
            SchemaFieldArg::createMultipleFromArray($schema['args']),
            $schema['description'],
            $schema['name'],
            SchemaType::createFromArray($schema['type'])
        );
    }
}
