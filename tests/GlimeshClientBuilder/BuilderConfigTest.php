<?php

namespace GlimeshClientBuilder\Tests;

use GlimeshClientBuilder\BuilderConfig;
use PHPUnit\Framework\TestCase;

class BuilderConfigTest extends TestCase
{
    public function testConfig(): void
    {
        $config = (new BuilderConfig())
            ->setOutputDirectory('/tmp/gcb/')
            ->setNamespace('TestingNamespace')
            ->setApiJsonFilePath('/tmp/gcb/api.json')
            ->setStandardDocBlock([
                ' * @author TestingAuthor',
                ' * @license TestingLicense',
            ])
            ->setRootDirectory(__DIR__);

        $this->assertEquals('/tmp/gcb/', $config->getOutputDirectory());
        $this->assertEquals('TestingNamespace', $config->getNamespace());
        $this->assertEquals('/tmp/gcb/api.json', $config->getApiJsonFilePath());
        $this->assertEquals(
            " * @author TestingAuthor\n * @license TestingLicense",
            $config->getStandardDocBlock()
        );
        $this->assertEquals(__DIR__, $config->getRootDirectory());
    }
}
