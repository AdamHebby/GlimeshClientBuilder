<?php

namespace GlimeshClientBuilder\Resolver;

use GlimeshClientBuilder\Schema\Schema;
use GlimeshClientBuilder\Schema\SchemaObject;

/**
 * Resolves Connection objects in the Schema to their corresponding objects
 */
class SchemaMappingResolver
{
    /** @var SchemaObject[] $objects */
    private array $objects = [];

    /** @var SchemaObject[] $interfaces */
    private array $interfaces = [];

    /** @var SchemaObject[] $enums */
    private array $enums = [];

    /** @var SchemaObject[] $inputObjects */
    private array $inputObjects = [];

    /** @var array<string,string> $connectionNodeMap */
    private array $connectionNodeMap = [];

    public function __construct(
        Schema $schema
    ) {
        $this->connectionNodeMap = SchemaConnectionNodeMapResolver::resolveSchema(
            $schema
        );

        foreach ($schema->schemaObjects as $type) {
            if (
                SchemaConnectionNodeMapResolver::isAnEdge($type) ||
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

    public function getInterfaceByName(string $name): ?SchemaObject
    {
        return $this->interfaces[$name] ?? null;
    }

    public function getEnumByName(string $name): ?SchemaObject
    {
        return $this->enums[$name] ?? null;
    }

    public function getInputObjectByName(string $name): ?SchemaObject
    {
        return $this->inputObjects[$name] ?? null;
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
}
