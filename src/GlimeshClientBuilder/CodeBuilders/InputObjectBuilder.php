<?php

namespace GlimeshClientBuilder\CodeBuilders;

class InputObjectBuilder extends AbstractBuilder
{
    public function __construct(
        private FieldBuilder $fieldBuilder
    ) {}

    public function buildInputObject(array $type): string
    {
        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/input_object.php.txt',
            [
                '%BUILDER_DESCRIPTION%' => $type['description'] ?? 'Description not provided',
                '%BUILDER_NAME%' => $type['name'],
                '%BUILDER_FIELDS%' => $this->fieldBuilder->buildFields($type['inputFields']),
            ]
        );
    }
}
