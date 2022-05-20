<?php

namespace GlimeshClientBuilder\Schema;

class Schema
{
    /**
     * @var SchemaObject[]
     */
    public readonly array $schemaObjects;

    public function __construct(array $data)
    {
        $this->schemaObjects = SchemaObject::createMultipleFromArray($data);
    }

    public static function loadFromJsonFile(string $filePath): self
    {
        return new self(
            json_decode(
                file_get_contents($filePath),
                true
            )['data']['__schema']['types']
        );
    }
}
