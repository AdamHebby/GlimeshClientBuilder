<?php

namespace GlimeshClientBuilder\CodeBuilders;

use GlimeshClientBuilder\Resolver\ObjectResolver;

class UtilsBuilder extends AbstractBuilder
{
    public function __construct(
        private ObjectResolver $objectResolver
    )
    {
    }

    public function buildFieldMappingTrait(): string
    {
        list($mappingSingle, $mappingMultiple) = $this->objectResolver->buildFieldToObjectMap();

        ksort($mappingMultiple);
        ksort($mappingSingle);

        $mappingMultipleCode = implode("\n", $this->buildClassArray($mappingMultiple));
        $mappingSingleCode   = implode("\n", $this->buildClassArray($mappingSingle));

        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/FieldMappingTrait.php.txt',
            [
                '%BUILDER_MAPPING_MULTIPLE%' => $mappingMultipleCode,
                '%BUILDER_MAPPING_SINGLE%' => $mappingSingleCode,
            ]
        );
    }

    public function buildObjectModelTrait(): string
    {
        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/ObjectModelTrait.php.txt',
            []
        );
    }

    public function buildObjectResolverTrait(): string
    {
        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/ObjectResolverTrait.php.txt',
            []
        );
    }

    public function buildPagedArrayObject(): string
    {
        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/PagedArrayObject.php.txt',
            []
        );
    }

    public function buildAbstractObjectModel(): string
    {
        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/AbstractObjectModel.php.txt',
            []
        );
    }

    public function buildAbstractInputObjectModel(): string
    {
        return $this->templateValues(
            $this->config->getRootDirectory() . '/resources/AbstractInputObjectModel.php.txt',
            []
        );
    }

    /**
     * @param array<string,string> $mapping
     *
     * @return array<int,string>
     */
    private function buildClassArray(array $mapping): array
    {
        $code = [];
        $maxLen = max(array_map('strlen', array_keys($mapping)));

        foreach ($mapping as $key => $object) {
            $tabString = str_repeat(' ', $maxLen - strlen($key));
            $code[] = "        '{$key}'{$tabString} => {$object},";
        }

        return $code;
    }
}

