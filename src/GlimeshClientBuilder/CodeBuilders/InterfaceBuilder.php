<?php

namespace GlimeshClientBuilder\CodeBuilders;

use GlimeshClientBuilder\Schema\SchemaObject;

class InterfaceBuilder extends AbstractBuilder
{
    public function buildInterface(SchemaObject $type): string
    {
        return $this->templateValues(
           $this->config->getRootDirectory() . '/resources/interface.php.txt',
            [
                '%BUILDER_DESCRIPTION%' => $type->description ?? 'Description not provided',
                '%BUILDER_NAME%' => $type->name,
            ]
        );
    }
}
