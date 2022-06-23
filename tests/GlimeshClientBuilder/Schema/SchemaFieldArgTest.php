<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaFieldArg;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaFieldArgTest extends AbstractBuilderTestCase
{
    public function testSchemaContainsFieldArgs(): void
    {
        foreach ($this->schema->schemaObjects as $type) {
            if (!empty($type->fields) && !empty($type->fields[0]->args)) {
                $this->assertContainsOnlyInstancesOf(
                    SchemaFieldArg::class,
                    $type->fields[0]->args
                );

                return;
            }
        }

        $this->fail('Field args not found');
    }
}
