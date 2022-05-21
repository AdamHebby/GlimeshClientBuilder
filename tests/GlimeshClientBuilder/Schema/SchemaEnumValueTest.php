<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaEnumValue;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaEnumValueTest extends AbstractBuilderTestCase
{
    public function testSchemaContainsEnumValues(): void
    {
        foreach ($this->schema->schemaObjects as $type) {
            if (!empty($type->enumValues)) {
                $this->assertContainsOnlyInstancesOf(
                    SchemaEnumValue::class,
                    $type->enumValues
                );

                return;
            }
        }

        $this->fail('Enum Values not found');
    }
}
