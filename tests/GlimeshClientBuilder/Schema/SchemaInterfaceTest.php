<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaInterface;

class SchemaInterfaceTest extends AbstractSchemaTestCase
{
    public function testSchemaContainsInterface(): void
    {
        foreach ($this->schema->schemaObjects as $type) {
            if ($type->name === 'TextToken') {
                $this->assertInstanceOf(
                    SchemaInterface::class,
                    $type->interfaces[0]
                );
                return;
            }
        }

        $this->fail('Interface not found');
    }
}
