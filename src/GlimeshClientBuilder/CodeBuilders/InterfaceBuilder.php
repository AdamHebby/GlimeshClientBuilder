<?php

namespace GlimeshClientBuilder\CodeBuilders;

class InterfaceBuilder extends AbstractBuilder
{
    public function buildInterface(array $type): string
    {
        return $this->templateValues(
           $this->config->getRootDirectory() . '/resources/interface.php.txt',
            [
                '%BUILDER_DESCRIPTION%' => $type['description'] ?? 'Description not provided',
                '%BUILDER_NAME%' => $type['name'],
            ]
        );
    }
}
