<?php

namespace GlimeshClientBuilder;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\AbstractBuilder;
use GlimeshClientBuilder\CodeBuilders\FieldBuilder;
use GlimeshClientBuilder\CodeBuilders\ObjectBuilder;
use GlimeshClientBuilder\CodeBuilders\UtilsBuilder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;

/**
 * Project builder class for building all Objects, interfaces, enums etc from
 * the Glimesh API
 *
 * @author Adam Hebden <adam@adamhebden.com>
 * @copyright 2021 Adam Hebden
 * @license GPL-3.0-or-later
 * @package GlimeshClientBuilder
 */
class Builder extends AbstractBuilder
{
    /**
     * Paths config, where to place each object type
     *
     * @var array
     */
    public array $paths = [
        'INTERFACE'     => '/Interfaces',
        'OBJECT'        => '/Objects',
        'INPUT_OBJECT'  => '/Objects/Input',
        'ENUM'          => '/Objects/Enums',
        'TRAIT'         => '/Traits',
    ];

    private SchemaMappingResolver $resolver;
    private ObjectResolver $objectResolver;
    private FieldBuilder $fieldBuilder;
    private ObjectBuilder $objectBuilder;
    public static string $ROOT_DIR;

    /**
     * Constructor, loads the API JSON from path
     *
     * @param string $apiJsonFilePath
     */
    public function __construct(BuilderConfig $config) {
        $this->setConfig($config);

        self::$ROOT_DIR = $this->config->getRootDirectory();

        $schema = json_decode(
            file_get_contents($this->config->getApiJsonFilePath()),
            true
        )['data']['__schema']['types'];

        $this->resolver       = new SchemaMappingResolver($schema);
        $this->objectResolver = new ObjectResolver($this->resolver);
        $this->fieldBuilder   = new FieldBuilder($this->objectResolver, $this->resolver);
        $this->objectBuilder  = new ObjectBuilder($this->fieldBuilder);

        $this->fieldBuilder->setConfig($this->config);
        $this->objectBuilder->setConfig($this->config);
    }

    /**
     * Builds a single Input Object class string, including all fields
     *
     * @param array $type
     *
     * @return string
     */
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

    /**
     * Builds a single Interface string
     *
     * @param array $type
     *
     * @return string
     */
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

    /**
     * Builds a single ENUM object string, including any static fields
     *
     * @param array $type
     *
     * @return string
     */
    public function buildENUM(array $type): string
    {
        $enumValues = array_map(function ($enum) {
            return "    case {$enum['name']} = \"{$enum['name']}\";";
        }, $type['enumValues'] ?? []);

        return $this->templateValues(
           $this->config->getRootDirectory() . '/resources/enum.php.txt',
            [
                '%BUILDER_DESCRIPTION%' => $type['description'] ?? 'Description not provided',
                '%BUILDER_NAME%' => $type['name'],
                '%BUILDER_ENUM_VALUES%' => implode("\n", $enumValues),
            ]
        );
    }

    /**
     * Builds the entire project, putting classes in their correct places
     *
     * @return void
     */
    public function build()
    {
        foreach ($this->resolver->getInterfaces() as $interface) {
            $code = $this->buildEnum($interface);
            $this->writeCode($code, $interface['kind'], $interface['name']);
        }

        foreach ($this->resolver->getEnums() as $enum) {
            $code = $this->buildEnum($enum);
            $this->writeCode($code, $enum['kind'], $enum['name']);
        }

        foreach ($this->resolver->getInputObjects() as $inputs) {
            $code = $this->buildEnum($inputs);
            $this->writeCode($code, $inputs['kind'], $inputs['name']);
        }

        foreach ($this->resolver->getObjects() as $object) {
            $code = $this->objectBuilder->buildObjectCode($object);
            $this->writeCode($code, $object['kind'], $object['name']);
        }

        $utilsBuilder = new UtilsBuilder($this->resolver);
        $utilsBuilder->setConfig($this->config);

        $this->writeCode(
            $utilsBuilder->buildFieldMappingTrait(),
            'TRAIT',
            'FieldMappingTrait'
        );

        $this->writeCode(
            $utilsBuilder->buildObjectModelTrait(),
            'TRAIT',
            'ObjectModelTrait'
        );

        $this->writeCode(
            $utilsBuilder->buildAbstractObjectModel(),
            'OBJECT',
            'AbstractObjectModel'
        );
    }

    private function writeCode(string $code, string $typeKind, string $fileName): void
    {
        $path = $this->config->getOutputDirectory() . '' . $this->paths[$typeKind] ?? null;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents(
            "$path/{$fileName}.php",
            $code
        );
    }
}
