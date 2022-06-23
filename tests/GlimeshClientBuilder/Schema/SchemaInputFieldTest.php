<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaInputField;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaInputFieldTest extends AbstractBuilderTestCase
{
    public function testSchemaContainsInputFields(): void
    {
        foreach ($this->schema->schemaObjects as $type) {
            if (!empty($type->inputFields)) {
                $this->assertContainsOnlyInstancesOf(
                    SchemaInputField::class,
                    $type->inputFields
                );

                return;
            }
        }

        $this->fail('Input Field types not found');
    }
}
