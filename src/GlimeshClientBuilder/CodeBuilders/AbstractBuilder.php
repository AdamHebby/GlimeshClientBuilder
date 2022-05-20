<?php

namespace GlimeshClientBuilder\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;

abstract class AbstractBuilder
{
    public BuilderConfig $config;

    public function templateValues(string $filename, array $replace): string
    {
        $docBlock = $this->config->getStandardDocBlock();

        $extra = [
            '%BUILDER_NAMESPACE%' => $this->config->getNamespace(),
            '%BUILDER_STANDARD_DOCBLOCK%' => (!empty($docBlock)) ? " *\n{$docBlock}" : '',
        ];
        return str_replace(
            [...array_keys($replace), ...array_keys($extra)],
            [...array_values($replace), ...array_values($extra)],
            file_get_contents($filename)
        );
    }

    public function setConfig(BuilderConfig $config): void
    {
        $this->config = $config;
    }
}
