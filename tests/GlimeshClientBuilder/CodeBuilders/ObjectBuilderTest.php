<?php

namespace GlimeshClientBuilder\Tests\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\FieldBuilder;
use GlimeshClientBuilder\CodeBuilders\ObjectBuilder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class ObjectBuilderTest extends AbstractBuilderTestCase
{
    protected ObjectBuilder $builder;
    protected SchemaMappingResolver $schemaMappingResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $config = (new BuilderConfig())
            ->setRootDirectory(__DIR__ . '/../../..')
            ->setNamespace('TestingNamespace');

        $this->schemaMappingResolver = new SchemaMappingResolver($this->schema);

        $fieldBuilder = new FieldBuilder(
            new ObjectResolver($this->schemaMappingResolver),
            $this->schemaMappingResolver
        );

        $fieldBuilder->setConfig($config);

        $this->builder = new ObjectBuilder($fieldBuilder);
        $this->builder->setConfig($config);
    }

    public function testBuildObject(): void
    {
        $object     = $this->schemaMappingResolver->getObjectByName('Channel');
        $objectCode = $this->builder->buildObject($object);

        $this->assertStringContainsString(
            'public readonly ?\ArrayObject $moderationLogs;',
            $objectCode
        );
        $this->assertStringContainsString(
            'class Channel extends AbstractObjectModel',
            $objectCode
        );
        $this->assertStringContainsString(
            'use TestingNamespace\Objects\Enums\ChannelStatus;',
            $objectCode
        );
    }

    public function testBuildObjectWithInterfaces(): void
    {
        $object     = $this->schemaMappingResolver->getObjectByName('EmoteToken');
        $objectCode = $this->builder->buildObject($object);

        $this->assertStringContainsString(
            'public readonly ?string $src;',
            $objectCode
        );
        $this->assertStringContainsString(
            'class EmoteToken extends AbstractObjectModel implements ChatMessageToken',
            $objectCode
        );
        $this->assertStringContainsString(
            'use TestingNamespace\Interfaces\ChatMessageToken;',
            $objectCode
        );
    }
}
