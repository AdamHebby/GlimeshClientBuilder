<?php

namespace GlimeshClientBuilder\Tests\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\FieldBuilder;
use GlimeshClientBuilder\CodeBuilders\InputObjectBuilder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class InputObjectBuilderTest extends AbstractBuilderTestCase
{
    protected InputObjectBuilder $builder;
    protected SchemaMappingResolver $schemaMappingResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $config = (new BuilderConfig())
            ->setRootDirectory(__DIR__ . '/../../..')
            ->setNamespace('TestingNamespace');

        $this->schemaMappingResolver = new SchemaMappingResolver($this->schema);

        $fieldBuilder = new FieldBuilder(
            new ObjectResolver(
                $this->schemaMappingResolver,
                $config
            ),
            $this->schemaMappingResolver
        );

        $fieldBuilder->setConfig($config);

        $this->builder = new InputObjectBuilder($fieldBuilder);
        $this->builder->setConfig($config);
    }

    public function testBuildInputObject(): void
    {
        $inputObject = $this->schemaMappingResolver
            ->getInputObjectByName('StreamMetadataInput');

        $inputObjectCode = $this->builder->buildInputObject($inputObject);

        $this->assertStringContainsString(
            'class StreamMetadataInput extends AbstractInputObjectModel',
            $inputObjectCode
        );
        $this->assertStringContainsString(
            'namespace TestingNamespace\Objects\Input',
            $inputObjectCode
        );
        $this->assertStringContainsString(
            'public readonly ?string $audioCodec;',
            $inputObjectCode
        );
    }
}
