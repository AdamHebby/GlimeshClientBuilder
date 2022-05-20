<?php

namespace GlimeshClientBuilder\Schema;

class SchemaObject extends AbstractSchemaType
{
    public function __construct(
        public readonly string $kind,
        public readonly string $name,
        public readonly ?string $description,
        /**
         * @var ?SchemaEnumValue[]
         */
        public readonly ?array $enumValues,
        /**
         * @var ?SchemaField[]
         */
        public readonly ?array $fields,
        /**
         * @var ?SchemaInterfacePossibleType[]
         */
        public readonly ?array $possibleTypes,
        /**
         * @var ?SchemaInterface[]
         */
        public readonly ?array $interfaces,
        /**
         * @var ?SchemaInputField[]
         */
        public readonly ?array $inputFields
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
