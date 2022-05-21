<?php

namespace GlimeshClientBuilder\CodeBuilders;

use GlimeshClientBuilder\Schema\SchemaEnumValue;
use GlimeshClientBuilder\Schema\SchemaObject;

class EnumBuilder extends AbstractBuilder
{
    public function buildEnum(SchemaObject $type): string
    {
        $enumValues = array_map(function (SchemaEnumValue $enum) {
            return "    case {$enum->name} = \"{$enum->name}\";";
        }, $type->enumValues ?? []);

        return $this->templateValues(
           $this->config->getRootDirectory() . '/resources/enum.php.txt',
            [
                '%BUILDER_DESCRIPTION%' => $type->description ?? 'Description not provided',
                '%BUILDER_NAME%'        => $type->name,
                '%BUILDER_ENUM_VALUES%' => implode("\n", $enumValues),
            ]
        );
    }
}
