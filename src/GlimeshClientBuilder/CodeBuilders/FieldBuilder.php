<?php

namespace GlimeshClientBuilder\CodeBuilders;

use GlimeshClientBuilder\Builder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Schema\SchemaField;
use GlimeshClientBuilder\Schema\SchemaInputField;

class FieldBuilder extends AbstractBuilder
{
    public function __construct(
        private ObjectResolver $objectResolver,
        private SchemaMappingResolver $resolver
    )
    {

    }

    /**
     * @param SchemaInputField[]|SchemaField[] $fields
     */
    public function buildFields(array $fields): string
    {
        $code = (array_map(function ($field) {
            return $this->buildField($field);
        }, $fields));

        $code = array_filter($code);

        return rtrim(implode("\n", $code));
    }

    public function buildField(SchemaInputField|SchemaField $field): string
    {
        $fieldType = $this->objectResolver->resolveField($field);
        $fieldDoc  = $fieldType;

        if (isset($this->resolver->getConnectionNodeMap()[$field->type->name]) ||
            (isset($field->type->kind) && $field->type->kind === 'LIST')) {
            $fieldDoc = "\ArrayObject<$fieldType>";
            $fieldType = '\ArrayObject';
        }

        $description = $field->description ?? 'Description not provided';

        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/field.php.txt',
            [
                '%BUILDER_FIELD_DESCRIPTION%' => $description,
                '%BUILDER_FIELD_TYPE%' => $fieldDoc,
                '%BUILDER_P_FIELD_TYPE%' => $fieldType,
                '%BUILDER_FIELD_NAME%' => $field->name
            ]
        );
    }
}
