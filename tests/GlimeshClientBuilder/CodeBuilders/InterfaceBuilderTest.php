<?php

namespace GlimeshClientBuilder\Tests\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\InterfaceBuilder;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class InterfaceBuilderTest extends AbstractBuilderTestCase
{
    protected InterfaceBuilder $builder;
    protected SchemaMappingResolver $schemaMappingResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $config = (new BuilderConfig())
            ->setRootDirectory(__DIR__ . '/../../..')
            ->setNamespace('TestingNamespace');

        $this->schemaMappingResolver = new SchemaMappingResolver($this->schema);

        $this->builder = new InterfaceBuilder();
        $this->builder->setConfig($config);
    }

    public function testBuildInterface(): void
    {
        $interface     = $this->schemaMappingResolver->getInterfaceByName('ChatMessageToken');
        $interfaceCode = $this->builder->buildInterface($interface);

        $this->assertStringContainsString('interface ChatMessageToken', $interfaceCode);
        $this->assertStringContainsString('namespace TestingNamespace\Interfaces', $interfaceCode);
    }
}
