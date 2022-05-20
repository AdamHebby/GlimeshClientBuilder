<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\AbstractSchemaType;
use GlimeshClientBuilder\Schema\Schema;

class SchemaTest extends AbstractSchemaTestCase
{
    public function testLoadFromJsonFile()
    {
        $this->assertInstanceOf(Schema::class, $this->schema);
        $this->assertInstanceOf(AbstractSchemaType::class, $this->schema->schemaObjects[0]);
    }
}
