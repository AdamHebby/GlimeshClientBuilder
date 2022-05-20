<?php

namespace GlimeshClientBuilder\Schema;

class SchemaType extends AbstractSchemaType
{
    public readonly string $kind;
    public readonly ?string $name;
    public readonly ?SchemaType $ofType;

    public static function createFromArray(array $schema): self
    {
        $newInterface = new self();
        $newInterface->kind = $schema['kind'];
        $newInterface->name = $schema['name'];

        $newInterface->ofType = $schema['ofType']
            ? SchemaType::createFromArray($schema['ofType'])
            : null;

        return $newInterface;
    }
}
