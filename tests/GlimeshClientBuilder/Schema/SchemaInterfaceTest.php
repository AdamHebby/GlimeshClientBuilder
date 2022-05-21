<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaInterface;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaInterfaceTest extends AbstractBuilderTestCase
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
