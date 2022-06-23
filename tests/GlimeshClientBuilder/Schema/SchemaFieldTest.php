<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaField;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaFieldTest extends AbstractBuilderTestCase
{
    public function testSchemaContainsFields(): void
    {
        foreach ($this->schema->schemaObjects as $type) {
            if (!empty($type->fields)) {
                $this->assertContainsOnlyInstancesOf(
                    SchemaField::class,
                    $type->fields
                );

                return;
            }
        }

        $this->fail('Field types not found');
    }
}
