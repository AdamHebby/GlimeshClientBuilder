<?php

namespace GlimeshClientBuilder\Resolver;

use GlimeshClientBuilder\Schema\SchemaField;
use GlimeshClientBuilder\Schema\SchemaType;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class ObjectResolverTest extends AbstractBuilderTestCase
{
    public function testResolveString(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            'string',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType('SCALAR', 'string', null)
            ))
        );
    }
    public function testResolveBoolean(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            'bool',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType('SCALAR', 'bool', null)
            ))
        );

        $this->assertEquals(
            'bool',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType('SCALAR', 'Boolean', null)
            ))
        );
    }
    public function testResolveDateTime(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            '\DateTime',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType('OBJECT', 'NaiveDateTime', null)
            ))
        );

        $this->assertEquals(
            '\DateTime',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType('OBJECT', 'DateTime', null)
            ))
        );
    }
    public function testResolveIdAsString(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            'string',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType('SCALAR', 'ID', null)
            ))
        );
    }
    public function testResolveConnectionToObject(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            'ChannelBan',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'ban',
                new SchemaType('OBJECT', 'ChannelBanConnection', null)
            ))
        );
    }
    public function testResolveOfType(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            'string',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType(
                    'NON_NULL',
                    null,
                    new SchemaType('SCALAR', 'string', null)
                )
            ))
        );
    }
    public function testResolveOfTypeReplacement(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            '\DateTime',
            $resolver->resolveField(new SchemaField(
                [],
                'description',
                'fieldName',
                new SchemaType(
                    'NON_NULL',
                    null,
                    new SchemaType('OBJECT', 'NaiveDateTime', null)
                )
            ))
        );
    }

    public function testBuildFieldToObjectMap(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);
        list($mappingSingle, $mappingMultiple) = $resolver->buildFieldToObjectMap();

        $this->assertEquals(
            '\TestingNamespace\Objects\Enums\ChannelStatus::class',
            $mappingSingle['status']
        );
        $this->assertEquals(
            '\TestingNamespace\Objects\User::class',
            $mappingSingle['streamer']
        );

        $this->assertEquals(
            '\TestingNamespace\Objects\Tag::class',
            $mappingMultiple['tags']
        );
        $this->assertEquals(
            '\TestingNamespace\Objects\ChannelBan::class',
            $mappingMultiple['bans']
        );
        $this->assertEquals(
            '\TestingNamespace\Objects\Stream::class',
            $mappingMultiple['streams']
        );
        $this->assertEquals(
            '\TestingNamespace\Objects\Category::class',
            $mappingMultiple['categories']
        );
    }

    public function testResolveClassName(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->assertEquals(
            '\TestingNamespace\Objects\Tag::class',
            $resolver->resolveClassName('Tag')
        );
        $this->assertEquals(
            '\TestingNamespace\Objects\Category::class',
            $resolver->resolveClassName('Category')
        );
        $this->assertEquals(
            '\TestingNamespace\Objects\Enums\ChannelStatus::class',
            $resolver->resolveClassName('ChannelStatus')
        );
        $this->assertEquals(
            '\TestingNamespace\Interfaces\ChatMessageToken::class',
            $resolver->resolveClassName('ChatMessageToken')
        );
        $this->assertEquals(
            '\TestingNamespace\Objects\Input\StreamMetadataInput::class',
            $resolver->resolveClassName('StreamMetadataInput')
        );
    }

    public function testResolveClassNameThrows(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema), $this->config);

        $this->expectException(\Exception::class);
        $this->assertEquals(
            '\\ClassDoesntExist::class',
            $resolver->resolveClassName('ClassDoesntExist')
        );
    }
}
