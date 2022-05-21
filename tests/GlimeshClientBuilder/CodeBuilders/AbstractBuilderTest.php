<?php

namespace GlimeshClientBuilder\Tests\CodeBuilders;

use GlimeshClientBuilder\BuilderConfig;
use GlimeshClientBuilder\CodeBuilders\AbstractBuilder;
use GlimeshClientBuilder\Tests\AbstractBuilderTestCase;

class AbstractBuilderTest extends AbstractBuilderTestCase
{
    public static function setUpBeforeClass(): void
    {
        mkdir('/tmp/gcb/');
    }

    protected function tearDown(): void
    {
        // remove the generated files from /tmp/gcb
        foreach (glob('/tmp/gcb/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        rmdir('/tmp/gcb/');
    }

    public function testTemplateValues(): void
    {
        $builder = $this->setupBuilder();

        $template = <<<TEMPLATE
        PARAM1: %BUILDER_PARAM_1%
        PARAM2: %BUILDER_PARAM_2%
        NAMESPACE: %BUILDER_NAMESPACE%
        STANDARD_DOCBLOCK:
        /**
        %BUILDER_STANDARD_DOCBLOCK%
         */
        TEMPLATE;
        $expected = <<<EXPECTED
        PARAM1: param1
        PARAM2: param2
        NAMESPACE: TestingNamespace
        STANDARD_DOCBLOCK:
        /**
         *
         * @author TestingAuthor
         * @license TestingLicense
         */
        EXPECTED;

        file_put_contents('/tmp/gcb/test.txt', $template);
        $this->assertEquals($expected, $builder->templateValues(
            '/tmp/gcb/test.txt',
            [
                '%BUILDER_PARAM_1%' => 'param1',
                '%BUILDER_PARAM_2%' => 'param2',
            ]
        ));
    }

    /**
     * @return AbstractBuilder
     */
    private function setupBuilder()
    {
        /** @var AbstractBuilder $builder */
        $builder = $this->getMockBuilder(AbstractBuilder::class)
            ->getMockForAbstractClass();

        $config = (new BuilderConfig())
            ->setNamespace('TestingNamespace')
            ->setRootDirectory('/tmp/gcb/')
            ->setStandardDocBlock([
                ' * @author TestingAuthor',
                ' * @license TestingLicense',
            ]);

        $builder->setConfig($config);

        return $builder;
    }
}
