<?php

namespace GlimeshClientBuilder\Resolver;

use Exception;
use GlimeshClientBuilder\Schema\SchemaObject;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaMappingResolverTest extends AbstractBuilderTestCase
{
    public function testSchemaMappingSeemsCorrect(): void
    {
        $resolver = new SchemaMappingResolver($this->schema);

        $this->assertContainsOnlyInstancesOf(SchemaObject::class, $resolver->getObjects());
        $this->assertContainsOnlyInstancesOf(SchemaObject::class, $resolver->getInterfaces());
        $this->assertContainsOnlyInstancesOf(SchemaObject::class, $resolver->getEnums());
        $this->assertContainsOnlyInstancesOf(SchemaObject::class, $resolver->getInputObjects());

        $this->assertGreaterThan(30, $resolver->getObjects());
        $this->assertGreaterThan(0, $resolver->getInterfaces());
        $this->assertGreaterThan(0, $resolver->getEnums());
        $this->assertGreaterThan(0, $resolver->getInputObjects());

        // assert that all 4 arrays only contain unique objects across all
        $merged = [
            ...array_keys($resolver->getObjects()),
            ...array_keys($resolver->getInterfaces()),
            ...array_keys($resolver->getEnums()),
            ...array_keys($resolver->getInputObjects()),
        ];

        $this->assertCount(
            count($merged),
            array_unique($merged)
        );
    }

    public function testMappingGetByName(): void
    {
        $resolver = new SchemaMappingResolver($this->schema);

        $this->assertSame(
            $resolver->getObjects()['UserSocial'],
            $resolver->getObjectByName('UserSocial')
        );

        $this->assertSame(
            $resolver->getInterfaces()['ChatMessageToken'],
            $resolver->getInterfaceByName('ChatMessageToken')
        );

        $this->assertSame(
            $resolver->getEnums()['ChannelStatus'],
            $resolver->getEnumByName('ChannelStatus')
        );

        $this->assertSame(
            $resolver->getInputObjects()['StreamMetadataInput'],
            $resolver->getInputObjectByName('StreamMetadataInput')
        );
    }

    public function testResolveSchemaThrowsWithWrongEdge(): void
    {
        // Modify an existing Edge to an edge that can't be resolved
        // SchemaMappingResolver should throw an exception
        foreach ($this->schema->schemaObjects as &$object) {
            if ($object->name === 'TagEdge') {
                // Cant modify readonly properties, so make a new object but change the name
                $object = new SchemaObject(
                    $object->kind,
                    'RandomTestingEdge',
                    $object->description,
                    $object->enumValues,
                    $object->fields,
                    $object->possibleTypes,
                    $object->interfaces,
                    $object->inputFields
                );
            }
        }

        $this->expectException(Exception::class);
        new SchemaMappingResolver($this->schema);
    }
}
