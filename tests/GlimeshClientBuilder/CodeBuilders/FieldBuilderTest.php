<?php

namespace GlimeshClientBuilder\Tests\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\FieldBuilder;
use GlimeshClientBuilder\Resolver\ObjectResolver;
use GlimeshClientBuilder\Resolver\SchemaMappingResolver;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class FieldBuilderTest extends AbstractBuilderTestCase
{
    protected FieldBuilder $builder;
    protected SchemaMappingResolver $schemaMappingResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $config = (new BuilderConfig())
            ->setRootDirectory(__DIR__ . '/../../..')
            ->setNamespace('TestingNamespace');

        $this->schemaMappingResolver = new SchemaMappingResolver($this->schema);

        $this->builder = new FieldBuilder(
            new ObjectResolver(
               $this->schemaMappingResolver
            ),
            $this->schemaMappingResolver
        );
        $this->builder->setConfig($config);
    }

    public function testBuildField(): void
    {
        $fields = $this->schemaMappingResolver->getObjectByName('Channel')->fields;

        foreach ($fields as $field) {
            if ($field->name === 'bans') {
                $fieldCode = $this->builder->buildField($field);

                $this->assertStringContainsString(
                    'public readonly ?\ArrayObject $bans;',
                    $fieldCode
                );
                $this->assertStringContainsString(
                    '@var ?\ArrayObject<ChannelBan>',
                    $fieldCode
                );
            }
        }
    }

    public function testBuildFields(): void
    {
        $fields = $this->schemaMappingResolver->getObjectByName('Channel')->fields;

        $fieldCode = $this->builder->buildFields($fields);

        $this->assertStringContainsString(
            'public readonly ?\ArrayObject $bans;',
            $fieldCode
        );
        $this->assertStringContainsString(
            '@var ?\ArrayObject<ChannelBan>',
            $fieldCode
        );

        $this->assertStringContainsString(
            '@var ?Category',
            $fieldCode
        );
        $this->assertStringContainsString(
            'public readonly ?Category $category;',
            $fieldCode
        );
    }
}
