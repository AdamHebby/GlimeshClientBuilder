<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\AbstractSchemaType;
use GlimeshClientBuilder\Schema\Schema;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class SchemaTest extends AbstractBuilderTestCase
{
    public function testLoadFromJsonFile(): void
    {
        $this->assertInstanceOf(Schema::class, $this->schema);
        $this->assertInstanceOf(AbstractSchemaType::class, $this->schema->schemaObjects[0]);
    }
}
