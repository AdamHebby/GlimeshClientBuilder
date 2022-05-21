<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaObject;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaObjectTest extends AbstractBuilderTestCase
{
    public function testSchemaContainsObjects(): void
    {
        $this->assertContainsOnlyInstancesOf(
            SchemaObject::class,
            $this->schema->schemaObjects
        );

        foreach ($this->schema->schemaObjects as $type) {
            $this->assertContains(
                $type->kind,
                ['OBJECT', 'ENUM', 'SCALAR', 'INPUT_OBJECT', 'INTERFACE']
            );

            if ($type->name === 'UserSocial') {
                $this->assertNotEmpty($type->fields);
                $this->assertEmpty($type->inputFields);
            }
        }
    }
}
