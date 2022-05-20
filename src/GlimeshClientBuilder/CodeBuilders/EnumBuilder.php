<?php

namespace GlimeshClientBuilder\CodeBuilders;

class EnumBuilder extends AbstractBuilder
{
    public function buildEnum(array $type): string
    {
        $enumValues = array_map(function ($enum) {
            return "    case {$enum['name']} = \"{$enum['name']}\";";
        }, $type['enumValues'] ?? []);

        return $this->templateValues(
           $this->config->getRootDirectory() . '/resources/enum.php.txt',
            [
                '%BUILDER_DESCRIPTION%' => $type['description'] ?? 'Description not provided',
                '%BUILDER_NAME%'        => $type['name'],
                '%BUILDER_ENUM_VALUES%' => implode("\n", $enumValues),
            ]
        );
    }
}
