<?php

namespace GlimeshClientBuilder\Schema;

class SchemaFieldArg extends AbstractSchemaType
{
    public readonly string $defaultValue;
    public readonly string $description;
    public readonly string $name;
    public readonly SchemaType $type;

    public static function createFromArray(array $schema): self
    {
        $newObject = new self();
        $newObject->defaultValue = $schema['defaultValue'];
        $newObject->name         = $schema['name'];
        $newObject->description  = $schema['description'];
        $newObject->type         = SchemaType::createFromArray($schema['type']);

        return $newObject;
    }
}
