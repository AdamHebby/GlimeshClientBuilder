<?php

namespace GlimeshClientBuilder\Schema;

class SchemaField extends AbstractSchemaType
{
    public readonly array $args;
    public readonly ?string $description;
    public readonly string $name;
    public readonly SchemaType $type;

    public static function createFromArray(array $schema): self
    {
        $newObject = new self();
        $newObject->args         = $schema['args'];
        $newObject->description  = $schema['description'];
        $newObject->name         = $schema['name'];
        $newObject->type         = SchemaType::createFromArray($schema['type']);

        return $newObject;
    }
}
