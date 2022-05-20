<?php

namespace GlimeshClientBuilder\Tests\Schema;

use GlimeshClientBuilder\Schema\Schema;

abstract class AbstractSchemaTestCase extends \PHPUnit\Framework\TestCase
{
    protected Schema $schema;

    protected function setUp(): void
    {
        $this->schema = $this->loadSchema();
    }

    protected function loadSchema(): Schema
    {
        return Schema::loadFromJsonFile(
            __DIR__ . '/../../resources/api_20220520.json'
        );
    }
}
