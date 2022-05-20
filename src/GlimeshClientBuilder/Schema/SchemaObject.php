<?php

namespace GlimeshClientBuilder\Schema;

class SchemaObject extends AbstractSchemaType
{
    /**
     * @param SchemaEnumValue[]             $enumValues
     * @param SchemaField[]                 $fields
     * @param SchemaInterfacePossibleType[] $possibleTypes
     * @param SchemaInterface[]             $interfaces
     * @param SchemaInputField[]            $inputFields
     */
    public function __construct(
        public readonly string $kind,
        public readonly string $name,
        public readonly ?string $description,
        public readonly array $enumValues,
        public readonly array $fields,
        public readonly array $possibleTypes,
        public readonly array $interfaces,
        public readonly array $inputFields
    ) {}

    public static function createFromArray(array $schema): self
    {
        return new self(
            $schema['kind'],
            $schema['name'],
            $schema['description'],
            ($schema['enumValues'] !== null)
                ? SchemaEnumValue::createMultipleFromArray($schema['enumValues'])
                : [],
            ($schema['fields'] !== null)
                ? SchemaField::createMultipleFromArray($schema['fields'])
                : [],
            ($schema['possibleTypes'] !== null)
                ? SchemaInterfacePossibleType::createMultipleFromArray($schema['possibleTypes'])
                : [],
            ($schema['interfaces'] !== null)
                ? SchemaInterface::createMultipleFromArray($schema['interfaces'])
                : [],
            ($schema['inputFields'] !== null)
                ? SchemaInputField::createMultipleFromArray($schema['inputFields'])
                : [],
        );
    }
}
