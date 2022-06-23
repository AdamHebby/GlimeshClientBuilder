<?php

namespace GlimeshClientBuilder\Resolver;

use GlimeshClientBuilder\Schema\Schema;
use GlimeshClientBuilder\Schema\SchemaField;
use GlimeshClientBuilder\Schema\SchemaObject;
use GlimeshClientBuilder\Schema\SchemaType;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaConnectionNodeMapResolverTest extends AbstractBuilderTestCase
{
    public function testSchemaConnectionNodeMapResolverBasic(): void
    {
        $resolver = new SchemaConnectionNodeMapResolver();

        $this->assertNotEmpty($resolver->resolveSchema($this->schema));
    }

    public function testNoNodeFieldsFound(): void
    {
        foreach ($this->schema->schemaObjects as &$object) {
            if ($object->name === 'TagEdge') {
                // Cant modify readonly properties, so make a new object but change the name
                $object = new SchemaObject(
                    $object->kind,
                    'RandomTestingEdge',
                    $object->description,
                    $object->enumValues,
                    [
                        new SchemaField(
                            [],
                            '',
                            'TestingEdge',
                            new SchemaType('OBJECT', 'name', null)
                        )
                    ],
                    $object->possibleTypes,
                    $object->interfaces,
                    $object->inputFields
                );
            }
        }
        $resolver = new SchemaConnectionNodeMapResolver();

        $this->expectException(\Exception::class);
        $resolver->resolveSchema($this->schema);
    }

    public function testNoEdgeFieldsFound(): void
    {
        foreach ($this->schema->schemaObjects as &$object) {
            if ($object->name === 'TagConnection') {
                // Cant modify readonly properties, so make a new object but change the name
                $object = new SchemaObject(
                    $object->kind,
                    'RandomTestingConnection',
                    $object->description,
                    $object->enumValues,
                    [
                        new SchemaField(
                            [],
                            '',
                            'TestingConnection',
                            new SchemaType('OBJECT', 'name', null)
                        )
                    ],
                    $object->possibleTypes,
                    $object->interfaces,
                    $object->inputFields
                );
            }
        }
        $resolver = new SchemaConnectionNodeMapResolver();

        $this->expectException(\Exception::class);
        $resolver->resolveSchema($this->schema);
    }
}
