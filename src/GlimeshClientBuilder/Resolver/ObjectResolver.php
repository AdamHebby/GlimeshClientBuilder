<?php

namespace GlimeshClientBuilder\Resolver;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\Schema\SchemaField;
use GlimeshClientBuilder\Schema\SchemaInputField;

class ObjectResolver
{
    public static array $replacements = [
        'Boolean'       => 'bool',
        'NaiveDateTime' => '\DateTime',
        'DateTime'      => '\DateTime',
        'ID'            => 'string',
    ];

    public function __construct(
        private readonly SchemaMappingResolver $resolver,
        private readonly BuilderConfig $config
    )
    {

    }

    /**
     * @return array<int,array<string,string>>
     */
    public function buildFieldToObjectMap(): array
    {
        $objects = $this->resolver->getObjects();
        $connectionNodeMap = $this->resolver->getConnectionNodeMap();

        $mapSingle = $mapMulitple = [];
        foreach ($objects as $object) {
            $fields = $object->fields;

            foreach ($fields as $field) {
                $resolvedType = $this->resolveField($field);
                if (in_array($resolvedType, ['string', 'int', 'bool'])) {
                    continue;
                }

                $className = $this->resolveClassName($resolvedType);
                if ($className === '\DateTime::class') {
                    continue;
                }

                if (
                    (isset($field->type->kind) && $field->type->kind === 'LIST') ||
                    isset($connectionNodeMap[$field->type->name])
                ) {
                    $mapMulitple[$field->name] = $className;
                } else {
                    $mapSingle[$field->name] = $className;
                }
            }
        }

        return [$mapSingle, $mapMulitple];
    }

    public function resolveClassName(string $resolvedType): string
    {
        $resolvedType = str_replace('\\', '', $resolvedType);
        $namespace    = $this->config->getNamespace();

        if (($object = $this->resolver->getObjectByName($resolvedType)) !== null) {
            return "\\{$namespace}\\Objects\\{$object->name}::class";
        }

        if (($input = $this->resolver->getInputObjectByName($resolvedType)) !== null) {
            return "\\{$namespace}\\Objects\\Input\\{$input->name}::class";
        }

        if (($enum = $this->resolver->getEnumByName($resolvedType)) !== null) {
            return "\\{$namespace}\\Objects\\Enums\\{$enum->name}::class";
        }

        if (($interface = $this->resolver->getInterfaceByName($resolvedType)) !== null) {
            return "\\{$namespace}\\Interfaces\\{$interface->name}::class";
        }

        if (class_exists("\\{$resolvedType}")) {
            return "\\{$resolvedType}::class";
        }

        throw new \Exception("Could not resolve class name for {$resolvedType}");
    }

    public function resolveField(SchemaInputField|SchemaField $field): string
    {
        $typeName = isset($field->type->name) ? $field->type->name : '';

        $resolvedType = null;

        $connectionNodeMap = $this->resolver->getConnectionNodeMap();

        if (isset($connectionNodeMap[$typeName])) {
            $resolvedType = $connectionNodeMap[$typeName];
        }

        if ($resolvedType !== null) {
            $resolvedType = preg_replace('/(.*?)\\\([a-z]+)$/i', "$2", $resolvedType);
        }

        if ($resolvedType === null && isset($field->type->ofType->name)) {
            $resolvedType = $field->type->ofType->name;
        }

        if (in_array(strtolower($resolvedType), ['string', 'int'])) {
            $resolvedType = strtolower($resolvedType);
        }

        if (in_array(strtolower($typeName), ['string', 'int'])) {
            $resolvedType = strtolower($typeName);
        }

        if (isset(self::$replacements[$resolvedType])) {
            $resolvedType = self::$replacements[$resolvedType];
        }

        if (isset(self::$replacements[$typeName])) {
            $resolvedType = self::$replacements[$typeName];
        }

        return $resolvedType ?? $typeName;
    }
}
