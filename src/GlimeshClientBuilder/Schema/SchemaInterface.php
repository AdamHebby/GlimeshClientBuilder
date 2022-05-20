<?php

namespace GlimeshClientBuilder\Schema;

class SchemaInterface extends AbstractSchemaType
{
    public static string $type = 'INTERFACE';

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
