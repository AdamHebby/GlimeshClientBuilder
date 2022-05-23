<?php

namespace GlimeshClientBuilder\Schema;

class Schema
{
    /**
     * @var SchemaObject[]
     */
    public array $schemaObjects;

    private static array $ignoreTypeNames = [
        'RootMutationType',
        'RootSubscriptionType',
    ];

    private static array $acceptsTypeKinds = [
        'INTERFACE',
        'OBJECT',
        'INPUT_OBJECT',
        'ENUM',
    ];

    public function __construct(array $data)
    {
        $this->schemaObjects = self::unsetUnacceptedObjects(
            SchemaObject::createMultipleFromArray($data)
        );
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

    private static function unsetUnacceptedObjects(array $schemaObjects): array
    {
        foreach ($schemaObjects as $key => $type) {
            if (!in_array($type->kind, self::$acceptsTypeKinds) ||
                in_array($type->name, self::$ignoreTypeNames) ||
                substr($type->name, 0, 2) === '__'
            ) {
                unset($schemaObjects[$key]);
                continue;
            }
        }

        return array_values($schemaObjects);
    }
}
