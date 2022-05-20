<?php

namespace GlimeshClientBuilder\Schema;

class SchemaInterfacePossibleType extends AbstractSchemaType
{
    public static string $type = 'OBJECT';

    public readonly string $name;
    public readonly ?string $ofType;

    public static function createFromArray(array $schema): self
    {
        $newInterface = new self();
        $newInterface->name   = $schema['name'];
        $newInterface->ofType = $schema['ofType'];

        return $newInterface;
    }
}
