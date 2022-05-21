<?php

namespace GlimeshClientBuilder\Resolver;

use GlimeshClientBuilder\Schema\Schema;
use GlimeshClientBuilder\Schema\SchemaField;
use GlimeshClientBuilder\Schema\SchemaType;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class ObjectResolverTest extends AbstractBuilderTestCase
{
    public function testResolveString(): void
    {
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));

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
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));

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
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));

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
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));

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
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));

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
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));

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
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));

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
        $resolver = new ObjectResolver(new SchemaMappingResolver($this->schema));
        list($mappingSingle, $mappingMultiple) = $resolver->buildFieldToObjectMap();

        $this->assertEquals('\DateTime', $mappingSingle['insertedAt']);
        $this->assertEquals('\DateTime', $mappingSingle['updatedAt']);
        $this->assertEquals('ChannelStatus', $mappingSingle['status']);
        $this->assertEquals('User', $mappingSingle['streamer']);

        $this->assertEquals('Tag', $mappingMultiple['tags']);
        $this->assertEquals('ChannelBan', $mappingMultiple['bans']);
        $this->assertEquals('Stream', $mappingMultiple['streams']);
        $this->assertEquals('Category', $mappingMultiple['categories']);
    }
}
