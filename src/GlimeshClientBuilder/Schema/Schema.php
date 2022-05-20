<?php

namespace GlimeshClientBuilder\Schema;

class Schema
{
    /**
     * @var AbstractSchemaType[]
     */
    public readonly array $schemaObjects;

    public function __construct(array $data)
    {
        $this->schemaObjects = SchemaObject::createMultipleFromArray($data);
    }
}
