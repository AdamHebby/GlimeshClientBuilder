<?php

namespace GlimeshClientBuilder\Tests\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\EnumBuilder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class EnumBuilderTest extends AbstractBuilderTestCase
{
    protected EnumBuilder $builder;
    protected SchemaMappingResolver $schemaMappingResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $config = (new BuilderConfig())
            ->setRootDirectory(__DIR__ . '/../../..')
            ->setNamespace('TestingNamespace');

        $this->schemaMappingResolver = new SchemaMappingResolver($this->schema);

        $this->builder = new EnumBuilder();
        $this->builder->setConfig($config);
    }

    public function testBuildEnum(): void
    {
        $enum     = $this->schemaMappingResolver->getEnumByName('ChannelStatus');
        $enumCode = $this->builder->buildEnum($enum);

        $this->assertStringContainsString('enum ChannelStatus: string', $enumCode);
        $this->assertStringContainsString('case LIVE = "LIVE"', $enumCode);
    }
}
