<?php

namespace GlimeshClientBuilder\CodeBuilders;

use GlimeshClientBuilder\Schema\SchemaObject;

class InputObjectBuilder extends AbstractBuilder
{
    public function __construct(
        private FieldBuilder $fieldBuilder
    ) {
    }

    public function buildInputObject(SchemaObject $type): string
    {
        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/input_object.php.txt',
            [
                '%BUILDER_DESCRIPTION%' => $type->description ?? 'Description not provided',
                '%BUILDER_NAME%'        => $type->name,
                '%BUILDER_FIELDS%'      => $this->fieldBuilder->buildFields($type->inputFields),
            ]
        );
    }
}
