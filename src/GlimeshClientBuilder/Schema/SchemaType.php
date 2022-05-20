<?php

namespace GlimeshClientBuilder\Schema;

class SchemaType extends AbstractSchemaType
{
    public function __construct(
        public readonly string $kind,
        public readonly ?string $name,
        public readonly ?SchemaType $ofType
    ) {}

    public static function createFromArray(array $schema): self
    {
        return new self(
            $schema['kind'],
            $schema['name'],
            ($schema['ofType'] !== null)
                ? SchemaType::createFromArray($schema['ofType'])
                : null
        );
    }
}
