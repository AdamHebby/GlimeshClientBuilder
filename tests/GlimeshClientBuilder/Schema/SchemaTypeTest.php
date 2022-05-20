<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\SchemaType;

class SchemaTypeTest extends AbstractSchemaTestCase
{
    public function testSchemaContainsSchemaType(): void
    {
        foreach ($this->schema->schemaObjects as $type) {
            if (!empty($type->fields) && !empty($type->fields[0]->type)) {
                $this->assertInstanceOf(
                    SchemaType::class,
                    $type->fields[0]->type
                );

                $this->assertNotNull($type->fields[0]->type->kind);

                return;
            }
        }

        $this->fail('Field args not found');
    }
}
