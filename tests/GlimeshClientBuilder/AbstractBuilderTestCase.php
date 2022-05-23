<?php

namespace GlimeshClientBuilder\Tests;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\Schema\Schema;

abstract class AbstractBuilderTestCase extends \PHPUnit\Framework\TestCase
{
    protected Schema $schema;
    protected BuilderConfig $config;

    protected function setUp(): void
    {
        $this->schema = $this->loadSchema();
        $this->config = (new BuilderConfig())
            ->setApiJsonFilePath(__DIR__ . '/../resources/api_20220520.json')
            ->setNamespace('TestingNamespace')
            ->setRootDirectory(__DIR__ . '/../../');
    }

    protected function loadSchema(): Schema
    {
        return Schema::loadFromJsonFile(
            __DIR__ . '/../resources/api_20220520.json'
        );
    }
}
