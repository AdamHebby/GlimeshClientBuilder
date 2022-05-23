<?php

namespace GlimeshClientBuilder;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\AbstractBuilder;
use GlimeshClientBuilder\CodeBuilders\EnumBuilder;
use GlimeshClientBuilder\CodeBuilders\FieldBuilder;
use GlimeshClientBuilder\CodeBuilders\InputObjectBuilder;
use GlimeshClientBuilder\CodeBuilders\InterfaceBuilder;
use GlimeshClientBuilder\CodeBuilders\ObjectBuilder;
use GlimeshClientBuilder\CodeBuilders\UtilsBuilder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Schema\Schema;

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
     * @var array<string,string>
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
    private EnumBuilder $enumBuilder;
    private InterfaceBuilder $interfaceBuilder;
    private InputObjectBuilder $inputObjectBuilder;
    public static string $ROOT_DIR;

    /**
     * Constructor, loads the API JSON from path & sets up builders & config
     */
    public function __construct(BuilderConfig $config) {
        $this->setConfig($config);

        self::$ROOT_DIR = $this->config->getRootDirectory();

        $schema = Schema::loadFromJsonFile($this->config->getApiJsonFilePath());

        $this->resolver           = new SchemaMappingResolver($schema);
        $this->objectResolver     = new ObjectResolver($this->resolver, $this->config);
        $this->fieldBuilder       = new FieldBuilder($this->objectResolver, $this->resolver);
        $this->objectBuilder      = new ObjectBuilder($this->fieldBuilder);
        $this->enumBuilder        = new EnumBuilder();
        $this->interfaceBuilder   = new InterfaceBuilder();
        $this->inputObjectBuilder = new InputObjectBuilder($this->fieldBuilder);

        $this->fieldBuilder->setConfig($this->config);
        $this->objectBuilder->setConfig($this->config);
        $this->enumBuilder->setConfig($this->config);
        $this->interfaceBuilder->setConfig($this->config);
        $this->inputObjectBuilder->setConfig($this->config);
    }

    /**
     * Builds the entire project, putting classes in their correct places
     *
     * @return void
     */
    public function build()
    {
        foreach ($this->resolver->getInterfaces() as $interface) {
            $this->writeCode(
                $this->interfaceBuilder->buildInterface($interface),
                $interface->kind,
                $interface->name
            );
        }

        foreach ($this->resolver->getEnums() as $enum) {
            $this->writeCode(
                $this->enumBuilder->buildEnum($enum),
                $enum->kind,
                $enum->name
            );
        }

        foreach ($this->resolver->getInputObjects() as $inputs) {
            $this->writeCode(
                $this->inputObjectBuilder->buildInputObject($inputs),
                $inputs->kind,
                $inputs->name
            );
        }

        foreach ($this->resolver->getObjects() as $object) {
            $this->writeCode(
                $this->objectBuilder->buildObject($object),
                $object->kind,
                $object->name
            );
        }

        $utilsBuilder = new UtilsBuilder($this->objectResolver);
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
            $utilsBuilder->buildObjectResolverTrait(),
            'TRAIT',
            'ObjectResolverTrait'
        );
        $this->writeCode(
            $utilsBuilder->buildAbstractObjectModel(),
            'OBJECT',
            'AbstractObjectModel'
        );
        $this->writeCode(
            $utilsBuilder->buildPagedArrayObject(),
            'OBJECT',
            'PagedArrayObject'
        );
        $this->writeCode(
            $utilsBuilder->buildAbstractInputObjectModel(),
            'INPUT_OBJECT',
            'AbstractInputObjectModel'
        );
    }

    private function writeCode(string $code, string $typeKind, string $fileName): void
    {
        $path = $this->config->getOutputDirectory() . '' . ($this->paths[$typeKind] ?? null);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents(
            "$path/{$fileName}.php",
            $code
        );
    }
}
