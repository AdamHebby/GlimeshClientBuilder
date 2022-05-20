<?php

namespace GlimeshClientBuilder\Schema;

class SchemaObject extends AbstractSchemaType
{
    public readonly string $kind;
    public readonly string $name;
    public readonly ?string $description;

    /**
     * @var ?SchemaEnumValue[]
     */
    public readonly ?array $enumValues;
    /**
     * @var ?SchemaField[]
     */
    public readonly ?array $fields;
    /**
     * @var ?SchemaInterfacePossibleType[]
     */
    public readonly ?array $possibleTypes;
    /**
     * @var ?SchemaInterface[]
     */
    public readonly ?array $interfaces;
    /**
     * @var ?SchemaInputField[]
     */
    public readonly ?array $inputFields;

    public static function createFromArray(array $schema): self
    {
        $newObject = new self();
        $newObject->kind          = $schema['kind'];
        $newObject->name          = $schema['name'];
        $newObject->description   = $schema['description'];

        $newObject->enumValues = ($schema['enumValues'] !== null)
            ? SchemaEnumValue::createMultipleFromArray($schema['enumValues'])
            : [];

        $newObject->fields = ($schema['fields'] !== null)
            ? SchemaField::createMultipleFromArray($schema['fields'])
            : [];

        $newObject->possibleTypes = ($schema['possibleTypes'] !== null)
            ? SchemaInterfacePossibleType::createMultipleFromArray($schema['possibleTypes'])
            : [];

        $newObject->interfaces = ($schema['interfaces'] !== null)
            ? SchemaInterface::createMultipleFromArray($schema['interfaces'])
            : [];

        $newObject->inputFields = ($schema['inputFields'] !== null)
            ? SchemaInputField::createMultipleFromArray($schema['inputFields'])
            : [];

        return $newObject;
    }
}
