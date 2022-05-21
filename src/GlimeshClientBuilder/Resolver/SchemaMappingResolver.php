<?php

namespace GlimeshClientBuilder\Resolver;

use GlimeshClientBuilder\Schema\Schema;
use GlimeshClientBuilder\Schema\SchemaObject;

/**
 * Resolves Connection objects in the Schema to their corresponding objects
 */
class SchemaMappingResolver
{
    private array $objects = [];

    private array $interfaces = [];

    private array $enums = [];

    private array $inputObjects = [];

    private array $connectionNodeMap = [];

    private static array $ignoreTypeNames = [
        'RootMutationType',
    ];

    private static array $acceptsTypeKinds = [
        'INTERFACE',
        'OBJECT',
        'INPUT_OBJECT',
        'ENUM',
    ];

    public function __construct(
        Schema $schema
    ) {
        $schema = $this->unsetUnacceptedObjects($schema);

        $this->connectionNodeMap = SchemaConnectionNodeMapResolver::resolveSchema(
            $schema
        );

        foreach ($schema->schemaObjects as $type) {
            if (SchemaConnectionNodeMapResolver::isAnEdge($type) ||
                SchemaConnectionNodeMapResolver::isANode($type)
            ) {
                continue;
            }

            switch ($type->kind) {
                case 'OBJECT':
                    $this->objects[$type->name] = $type;
                    break;

                case 'INTERFACE':
                    $this->interfaces[$type->name] = $type;
                    break;

                case 'INPUT_OBJECT':
                    $this->inputObjects[$type->name] = $type;
                    break;

                case 'ENUM':
                    $this->enums[$type->name] = $type;
                    break;
            }
        }
    }

    public function getObjectByName(string $name): ?SchemaObject
    {
        return $this->objects[$name] ?? null;
    }

    /**
     * @return SchemaObject[]
     */
    public function getObjects(): array
    {
        return $this->objects;
    }

    /**
     * @return SchemaObject[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @return SchemaObject[]
     */
    public function getEnums(): array
    {
        return $this->enums;
    }

    /**
     * @return SchemaObject[]
     */
    public function getInputObjects(): array
    {
        return $this->inputObjects;
    }

    public function getConnectionNodeMap(): array
    {
        return $this->connectionNodeMap;
    }

    private function unsetUnacceptedObjects(Schema $schema): Schema
    {
        foreach ($schema->schemaObjects as $key => $type) {
            if (!in_array($type->kind, self::$acceptsTypeKinds) ||
                in_array($type->name, self::$ignoreTypeNames) ||
                substr($type->name, 0, 2) === '__'
            ) {
                unset($schema->schemaObjects[$key]);
                continue;
            }
        }

        return $schema;
    }
}
