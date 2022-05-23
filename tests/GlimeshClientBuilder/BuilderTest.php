<?php

namespace GlimeshClientBuilder\Tests;

use GlimeshClientBuilder\Builder;
use GlimeshClientBuilder\BuilderConfig;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public static string $outputDir = '/tmp/gcb_output';

    protected function tearDown(): void
    {
        $this->deleteFiles(self::$outputDir);
    }

    private function deleteFiles($filePath): void
    {
        $files = glob($filePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            } else {
                $this->deleteFiles($file);
                rmdir($file);
            }
        }
    }

    public function testBuilds(): void
    {
        $config = (new BuilderConfig())
            ->setApiJsonFilePath(__DIR__ . '/../resources/api_20220520.json')
            ->setOutputDirectory(self::$outputDir)
            ->setNamespace('TestingNamespaceOutput')
            ->setStandardDocBlock([
                ' * @licence FakeLicence'
            ]);

        (new Builder($config))->build();

        // check if some generated files and folders exist
        $spotChecks = [
            '/Interfaces/ChatMessageToken.php',
            '/Objects/ChatMessage.php',
            '/Objects/ChatMessage.php',
            '/Objects/Enums/ChannelStatus.php',
            '/Objects/Input/StreamMetadataInput.php',
            '/Objects/Input/AbstractInputObjectModel.php',
            '/Traits/FieldMappingTrait.php',
            '/Traits/ObjectModelTrait.php',
        ];

        foreach ($spotChecks as $check) {
            $this->assertFileExists(self::$outputDir . $check);
            $this->assertFileIsReadable(self::$outputDir . $check);

            $this->assertGreaterThanOrEqual(
                100,
                strlen(file_get_contents(self::$outputDir . $check))
            );
        }
    }

    /**
     * Pretty hacky but generates files, autoloads them, hydrates objects and checks
     */
    public function testBuildsAndCodeRuns(): void
    {
        $config = (new BuilderConfig())
            ->setApiJsonFilePath(__DIR__ . '/../resources/api_20220520.json')
            ->setOutputDirectory(self::$outputDir)
            ->setNamespace('TestingNamespaceOutput')
            ->setStandardDocBlock([
                ' * @licence FakeLicence'
            ]);

        (new Builder($config))->build();

        $callback = function (string $class) {
            $class = str_replace('\\', '/', $class);
            $class = str_replace('TestingNamespace', '', $class);
            $class = str_replace('Output/', '', $class);

            if (file_exists(self::$outputDir . '/' . $class . '.php')) {
                require_once self::$outputDir . '/' . $class . '.php';
            }
        };

        spl_autoload_register($callback);

        $test = new \TestingNamespaceOutput\Objects\Channel(json_decode(
            file_get_contents(__DIR__ . '/../resources/object_data_hydrate_test.json'),
            true
        ));

        $this->assertSame('Fake Title', $test->title);
        $this->assertSame('en', $test->language);
        $this->assertSame('10003', $test->id);

        $this->assertEquals(1, $test->bans->count());
        $this->assertEquals(1, $test->bans->edgeCount);
        $this->assertEquals(false, $test->bans->pageInfo->hasNextPage);

        $ban = $test->bans->getArrayCopy()[0];

        $this->assertInstanceOf('\TestingNamespaceOutput\Objects\User', $test->streamer);
        $this->assertInstanceOf('\TestingNamespaceOutput\Objects\Category', $test->category);
        $this->assertInstanceOf('\TestingNamespaceOutput\Objects\Channel', $test);
        $this->assertInstanceOf('\TestingNamespaceOutput\Objects\ChannelBan', $ban);
        $this->assertSame('1253', $ban->id);

        spl_autoload_unregister($callback);
    }
}
