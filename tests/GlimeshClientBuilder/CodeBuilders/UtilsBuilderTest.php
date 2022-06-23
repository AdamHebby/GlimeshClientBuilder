<?php

namespace GlimeshClientBuilder\Tests\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\FieldBuilder;
use GlimeshClientBuilder\CodeBuilders\UtilsBuilder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class UtilsBuilderTest extends AbstractBuilderTestCase
{
    protected UtilsBuilder $builder;
    protected SchemaMappingResolver $schemaMappingResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $config = (new BuilderConfig())
            ->setRootDirectory(__DIR__ . '/../../..')
            ->setNamespace('TestingNamespace');

        $this->schemaMappingResolver = new SchemaMappingResolver($this->schema);

        $this->builder = new UtilsBuilder(
            new ObjectResolver($this->schemaMappingResolver, $config),
        );
        $this->builder->setConfig($config);
    }

    public function testBuildFieldMappingTrait(): void
    {
        $code = $this->builder->buildFieldMappingTrait();

        $this->assertMatchesRegularExpression(
            "/'bans'(\s*)=>\s\\\TestingNamespace\\\Objects\\\ChannelBan::class,/",
            $code
        );
    }

    public function testBuildObjectModelTrait(): void
    {
        $code = $this->builder->buildObjectModelTrait();

        $this->assertStringContainsString('trait ObjectModelTrait', $code);
        $this->assertStringContainsString('namespace TestingNamespace\Traits;', $code);
    }

    public function testBuildAbstractObjectModel(): void
    {
        $code = $this->builder->buildAbstractObjectModel();

        $this->assertStringContainsString('abstract class AbstractObjectModel', $code);
        $this->assertStringContainsString('namespace TestingNamespace\Objects;', $code);
    }

    public function testbuildAbstractInputObjectModel(): void
    {
        $code = $this->builder->buildAbstractInputObjectModel();

        $this->assertStringContainsString('abstract class AbstractInputObjectModel', $code);
        $this->assertStringContainsString('namespace TestingNamespace\Objects\Input;', $code);
    }
}
