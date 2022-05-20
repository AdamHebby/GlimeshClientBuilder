<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaInterfacePossibleType;

class SchemaInterfacePossibleTypeTest extends AbstractSchemaTestCase
{
    public function testSchemaContainsObjectsOfInterfaceTypes(): void
    {
        foreach ($this->schema->schemaObjects as $type) {
            if (!empty($type->possibleTypes)) {
                $this->assertContainsOnlyInstancesOf(
                    SchemaInterfacePossibleType::class,
                    $type->possibleTypes
                );

                return;
            }
        }

        $this->fail('Interface possible types not found');
    }
}
