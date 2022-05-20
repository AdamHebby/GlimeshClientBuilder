<?php

namespace GlimeshClientBuilder\Schema;

class SchemaEnumValue extends AbstractSchemaType
{
    public readonly string $name;
    public readonly ?string $description;

    public static function createFromArray(array $schema): self
    {
        $newObject = new self();
        $newObject->name        = $schema['name'];
        $newObject->description = $schema['description'];

        return $newObject;
    }
}
